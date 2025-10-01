<?php

namespace App\Http\Controllers;

use App\Models\Book;
use App\Models\Category;
use App\Models\Publisher;
use App\Services\BookAttributesService;
use Illuminate\Http\Request;

class HomeController extends Controller
{
    protected $bookAttributes;

    public function __construct(BookAttributesService $bookAttributes)
    {
        $this->bookAttributes = $bookAttributes;
    }

    public function index(Request $request)
    {
        $books = Book::filter(request()->all())
            ->with('author', 'images', 'reviews')
            ->paginate(12)
            ->appends($request->query());

        $categories = Category::withCount('books')->get();
        $publishers = Publisher::withCount('books')->get();
        $languages = Book::distinct()->pluck('language');

        return view('home.index', compact('books', 'categories', 'publishers', 'languages'));
    }

    public function autocomplete(Request $request)
    {
        $term = $request->query('term');
        $books = Book::where('title', 'like', $term . '%')
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
            ->take(4)
            ->get();

        return view('home.show', compact('book', 'coverTypes', 'related_books'));
    }
}
