<?php

namespace App\Http\Controllers;

use App\Models\Book;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class CartController extends Controller
{
    /**
     * Display the cart contents.
     *
     * @return \Illuminate\View\View
     */
    public function index()
    {
        $cart = session('cart', []);
        return view('cart.index', compact('cart'));
    }

    /**
     * Add a book to the cart.
     *
     * @param \Illuminate\Http\Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function add(Request $request)
    {
        $cart = session('cart', []);
        $validator = Validator::make($request->all(), [
            'book_id' => 'required|exists:books,id',
            'quantity' => 'required|integer|min:1',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'message' => __($validator->errors()->first()),
                'cart' => array_values($cart),
            ], 422);
        }

        $bookId = $request->input('book_id');
        $quantity = $request->input('quantity');
        $book = Book::with(['images', 'author'])
            ->select('id', 'title', 'sale_price', 'stock_quantity')
            ->findOrFail($bookId);

        $newQuantity = isset($cart[$bookId]) ? $cart[$bookId]['quantity'] + $quantity : $quantity;

        if ($book->stock_quantity < $newQuantity) {
            return response()->json([
                'success' => false,
                'message' => __('Insufficient stock quantity!'),
                'cart' => array_values($cart),
            ], 400);
        }

        $imagePath = $book->images->first()->image_path ?? '';

        $cart[$bookId] = [
            'title' => $book->title,
            'author' => $book->author->name ?? '',
            'image' => $imagePath,
            'unit_price' => $book->sale_price,
            'quantity' => $newQuantity,
            'amount' => $book->sale_price * $newQuantity,
        ];

        session()->put('cart', $cart);

        return response()->json([
            'success' => true,
            'message' => __('Book added to cart successfully!'),
            'cart' => array_values($cart),
        ]);
    }

    /**
     * Update the quantity of a book in the cart.
     *
     * @param \Illuminate\Http\Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function update(Request $request)
    {
        $cart = session('cart', []);
        $validator = Validator::make($request->all(), [
            'book_id' => 'required|exists:books,id',
            'quantity' => 'required|integer|min:1',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'message' => __($validator->errors()->first()),
                'cart' => array_values($cart),
            ], 422);
        }

        $bookId = $request->input('book_id');
        $quantity = $request->input('quantity');

        if (!isset($cart[$bookId])) {
            return response()->json([
                'success' => false,
                'message' => __('Book not found in cart!'),
                'cart' => array_values($cart),
            ], 404);
        }

        $book = Book::select('id', 'stock_quantity')->findOrFail($bookId);

        if ($book->stock_quantity < $quantity) {
            return response()->json([
                'success' => false,
                'message' => __('Insufficient stock quantity!'),
                'cart' => array_values($cart),
            ], 400);
        }

        $cart[$bookId]['quantity'] = $quantity;
        $cart[$bookId]['amount'] = $quantity * $cart[$bookId]['unit_price'];
        session()->put('cart', $cart);

        return response()->json([
            'success' => true,
            'message' => __('Cart updated successfully!'),
            'cart' => array_values($cart),
        ]);
    }

    /**
     * Remove a book from the cart.
     *
     * @param \Illuminate\Http\Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function remove(Request $request)
    {
        $cart = session('cart', []);
        $validator = Validator::make($request->all(), [
            'book_id' => 'required',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'message' => __($validator->errors()->first()),
                'cart' => array_values($cart),
            ], 422);
        }

        $bookId = $request->input('book_id');

        if (isset($cart[$bookId])) {
            unset($cart[$bookId]);
            session()->put('cart', $cart);

            return response()->json([
                'success' => true,
                'message' => __('Book removed from cart successfully!'),
                'cart' => array_values($cart),
            ]);
        }

        return response()->json([
            'success' => false,
            'message' => __('Book not found in cart!'),
            'cart' => array_values($cart),
        ], 404);
    }

    /**
     * Clear the entire cart.
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function clear()
    {
        session()->forget('cart');

        return response()->json([
            'success' => true,
            'message' => __('Cart cleared successfully!'),
            'cart' => [],
        ]);
    }

    /**
     * Display the payment page.
     *
     * @return \Illuminate\View\View
     */
    public function payment()
    {
        $cart = session('cart', []);
        return view('cart.payment', compact('cart'));
    }

    /**
     * Display the payment success page.
     *
     * @return \Illuminate\View\View
     */
    public function success()
    {
        return view('cart.success');
    }
}
