<?php

namespace App\Http\Controllers;

use App\Http\Requests\BookRequest;
use App\Models\Books;
use Illuminate\Http\Request;

class BookController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        //
        $books = Books::paginate(10);
        return view('books.index', ['books' => $books]);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
        return view('books.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(BookRequest $request)
    {
        //
        $book = $request->all();
        $book['picture_path'] = $request->file('picture_path')->store('assets/book', 'public');

        Books::create($book);
        return redirect()->route('books.index');
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Books $book)
    {
        //

        return view('books.edit', ['book' => $book]);

    }

    /**
     * Update the specified resource in storage.
     */
    public function update(BookRequest $request, Books $book)
    {
        //
        $data = $request->all();

        if ($request->file('picture_path')) {
            $data['picture_path'] = $request->file('picture_path')->store('assets/book', 'public');
        }
        $book->update($data);

        return redirect()->route('books.index');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Books $book)
    {
        //
        $book->delete();
        return redirect()->route('books.index');
    }
}
