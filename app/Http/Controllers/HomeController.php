<?php

namespace App\Http\Controllers;

use App\Models\Book;
use App\Services\BookAttributesService;
use Illuminate\Http\Request;

class HomeController extends Controller
{
    protected $bookAttributes;

    public function __construct(BookAttributesService $bookAttributes)
    {
        $this->bookAttributes = $bookAttributes;
    }

    public function index()
    {
        return view('home.index');
    }

    public function autocomplete(Request $request)
    {
        $term = $request->query('term');
        $books = Book::where('title', 'like', '%' . $term . '%')
            ->select('slug', 'title')
            ->take(5)
            ->get()
            ->map(function ($book) {
                return [
                    'id' => $book->slug,
                    'label' => $book->title,
                    'value' => $book->title,
                ];
            });
        return response()->json($books);
    }

    public function show(Book $book)
    {
        $coverTypes = $this->bookAttributes->getCoverTypes();
        $book->load(['author', 'publisher', 'categories', 'images']);

        $related_books = Book::where('id', '!=', $book->id)
            ->inRandomOrder()
            ->limit(4)
            ->get();

        return view('home.show', compact('book', 'coverTypes', 'related_books'));
    }
}
