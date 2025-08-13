<?php

namespace App\Http\Controllers;

use App\Models\Favorite;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class FavoriteController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $favorites = Favorite::where('user_id', Auth::id())
            ->with(['book.author', 'book.images'])
            ->get()
            ->pluck('book')
            ->map(function ($book) {
                $firstImage = $book->images->first();

                return [
                    'id' => $book->id,
                    'title' => $book->title,
                    'author' => $book->author->name ?? __('N/A'),
                    'sale_price' => $book->sale_price,
                    'image' => $firstImage?->image_path,
                    'alt' => $firstImage?->alt_text,
                ];
            });

        return view('favorites.index', compact('favorites'));
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
    public function store(Request $request)
    {
        $request->validate(['book_id' => 'required|exists:books,id']);

        $userId = Auth::id();
        if (!$userId) {
            return response()->json(['success' => false, 'message' => __('Please login to add to favorites.')], 401);
        }

        $bookId = $request->input('book_id');
        Favorite::firstOrCreate([
            'user_id' => $userId,
            'book_id' => $bookId,
        ]);

        return response()->json(['success' => true, 'message' => __('Added to favorites successfully!')]);
    }

    /**
     * Display the specified resource.
     */
    public function show(Favorite $favorite)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Favorite $favorite)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Favorite $favorite)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Request $request)
    {
        $request->validate(['book_id' => 'required|exists:books,id']);

        $userId = Auth::id();
        if (!$userId) {
            return response()->json(['success' => false, 'message' => __('Please login to remove from favorites.')], 401);
        }

        $deleted = Favorite::where('user_id', $userId)
            ->where('book_id', $request->input('book_id'))
            ->delete();

        if ($deleted) {
            return response()->json(['success' => true, 'message' => __('Removed from favorites successfully!')]);
        }

        return response()->json(['success' => false, 'message' => __('Item not found in favorites.')], 404);
    }
}
