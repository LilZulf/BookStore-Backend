<?php

namespace App\Http\Controllers;

use App\Models\Transactions;
use Illuminate\Http\Request;

class TransactionController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        //
        $transaction = Transactions::with(['books', 'user'])->paginate(10);
        return view('transactions.index', [
            'transactions' => $transaction
        ]);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store()
    {
        //

    }

    /**
     * Display the specified resource.
     */
    public function show(Transactions $transaction)
    {
        //
        return view('transactions.detail', ['item' => $transaction]);
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        //
    }

    public function changeStatus(Request $request, $id, $status)
    {
        $transaction = Transactions::findOrFail($id);

        $transaction->status = $status;
        $transaction->save();

        return redirect()->route('transactions.index');
    }
}
