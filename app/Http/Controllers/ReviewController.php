<?php

namespace App\Http\Controllers;

use App\Models\Review;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;

class ReviewController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        //
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
        $validator = Validator::make($request->all(), [
            'book_id' => 'required|exists:books,id',
            'rating' => 'required|integer|between:1,5',
            'comment' => 'nullable|string',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'message' => __($validator->errors()->first()),
            ], 422);
        }

        $userId = Auth::id();
        if (!$userId) {
            return response()->json([
                'success' => false,
                'message' => __('Please login to submit a review.')
            ], 401);
        }

        $bookId = $request->input('book_id');
        Review::updateOrCreate(
            [
                'user_id' => $userId,
                'book_id' => $bookId
            ],
            [
                'rating' => $request->input('rating'),
                'comment' => $request->input('comment')
            ]
        );

        return response()->json(['success' => true, 'message' => __('Review submitted successfully!')]);
    }

    /**
     * Display the specified resource.
     */
    public function show(Review $review)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Review $review)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Review $review)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Review $review)
    {
        //
    }
}
