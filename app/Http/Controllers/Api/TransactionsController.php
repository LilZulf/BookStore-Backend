<?php

namespace App\Http\Controllers\Api;

use App\Helpers\ResponseFormatter;
use App\Http\Controllers\Controller;
use App\Models\Transactions;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;
use Midtrans\Config;
use Midtrans\Snap;


class TransactionsController extends Controller
{
    //
    public function all(Request $request)
    {
        $id = $request->input('id');
        $limit = $request->input('limit', 6);
        $book_id = $request->input('book_id');
        $status = $request->input('status');


        if ($id) {
            $transactions = Transactions::with(['books', 'users'])->find($id);

            if ($transactions) {
                return ResponseFormatter::success(
                    $transactions,
                    'Data transaksi berhasil diambil'
                );
            } else {
                return ResponseFormatter::error(
                    null,
                    'Data transaksi tidak ada',
                    404
                );
            }
        }

        $transactions = Transactions::with(['books', 'user'])
            ->where('user_id', Auth::user()->id);

        if ($book_id) {
            $transactions->where('book_id', $book_id);
        }

        if ($status) {
            $transactions->where('status', $status);
        }

        return ResponseFormatter::success(
            $transactions->paginate($limit),
            'Data list transaksi berhasil diambil'
        );
    }

    public function update(Request $request, $id)
    {
        try {
            //code...
            $transaction = Transactions::findOrFail($id);
            $transaction->update($request->all());
            return ResponseFormatter::success(
                $transaction,
                'Data transaksi berhasil diupdate'
            );
        } catch (\Exception $exception) {
            //throw $th;
            return ResponseFormatter::error(
                $exception,
                'Something Went Wrong',
            );
        }
    }

    public function checkout(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'book_id' => 'required|exists:books,id',
            'user_id' => 'required|exists:users,id',
            'quantity' => 'required',
            'total' => 'required',
            'status' => 'required'
        ]);

        if ($validator->fails()) {
            return ResponseFormatter::error([
                'message' => 'Validator Error',
                'error' => $validator->errors()
            ]);
        }

        $transactions = Transactions::create([
            'book_id' => $request->book_id,
            'user_id' => $request->user_id,
            'quantity' => $request->quantity,
            'total' => $request->total,
            'status' => $request->status,
            'payment_url' => ''
        ]);

        // midtrans config
        Config::$serverKey = config('services.midtrans.serverKey');
        Config::$isProduction = config('services.midtrans.isProduction');
        Config::$isSanitized = config('services.midtrans.isSanitized');
        Config::$is3ds = config('services.midtrans.is3ds');

        // call transaction
        $transactions = Transactions::with(['books', 'user'])->find($transactions->id);

        // make midtrans transactions
        $midtrans = [
            'transaction_details' => [
                'order_id' => $transactions->id,
                'gross_amount' => $transactions->total,
            ],
            'customer_details' => [
                'first_name' => $transactions->user->name,
                'email' => $transactions->user->email
            ],
            'enabled_payments' => [
                'enabled_payments' => ['gopay', 'bank_transfer'],
                'vtweb' => []
            ]
        ];

        try {
            //code...
            $paymentUrl = Snap::createTransaction($midtrans)->redirect_url;
            $transactions->payment_url = $paymentUrl;
            $transactions->save();
            return ResponseFormatter::success(
                $transactions,
                'Transaksi berhasil'
            );
        } catch (\Exception $exception) {
            //throw $th;
            return ResponseFormatter::error(
                $exception->getMessage(),
                'Transaksi gagal'
            );
        }

    }
}
