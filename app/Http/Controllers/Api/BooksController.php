<?php

namespace App\Http\Controllers\Api;

use App\Helpers\ResponseFormatter;
use App\Http\Controllers\Controller;
use App\Models\Books;
use Illuminate\Http\Request;

class BooksController extends Controller
{
    //
    public function all(Request $request)
    {
        $id = $request->input('id');
        $limit = $request->input('limit', 6);
        $title = $request->input('title');
        $genre = $request->input('genre');

        $price_from = $request->input('price_from');
        $price_to = $request->input('price_to');

        // $date_from = $request->input('date_from');
        // $date_to = $request->input('date_to');

        if ($id) {
            $book = Books::find($id);

            if ($book) {
                return ResponseFormatter::success(
                    $book,
                    'Data buku berhasil diambil'
                );
            } else {
                return ResponseFormatter::error(
                    null,
                    'Data buku tidak ada',
                    404
                );
            }
        }

        $book = Books::query();

        if ($title) {
            $book->where('title', 'like', '%' . $title, '%');
        }

        if ($genre) {
            $book->where('genre', 'like', '%' . $genre, '%');
        }

        if ($price_from) {
            $book->where('price', '>=', $price_from);
        }

        if ($price_to) {
            $book->where('price', '<=', $price_from);
        }

        return ResponseFormatter::success(
            $book->paginate($limit),
            'Data list buku berhasil diambil'
        );
    }
}
