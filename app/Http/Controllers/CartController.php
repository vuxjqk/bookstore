<?php

namespace App\Http\Controllers;

use App\Models\Book;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class CartController extends Controller
{
    public function index()
    {
        return view('cart.index', ['cart' => session('cart', [])]);
    }

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
                'cart' => $cart,
            ], 422);
        }

        $bookId = $request->input('book_id');
        $quantity = $request->input('quantity');
        $book = Book::select('id', 'author_id', 'title', 'sale_price', 'stock_quantity')
            ->with([
                'author:id,name',
                'images:id,book_id,image_path,alt_text',
            ])
            ->findOrFail($bookId);

        $newQuantity = isset($cart[$bookId]) ? $cart[$bookId]['quantity'] + $quantity : $quantity;

        if ($book->stock_quantity < $newQuantity) {
            return response()->json([
                'success' => false,
                'message' => __('Insufficient stock quantity!'),
                'cart' => $cart,
            ], 400);
        }

        $image = $book->images->first();
        $cart[$bookId] = [
            'title' => $book->title,
            'author' => $book->author->name ?? '',
            'image' => $image?->image_path,
            'alt' => $image?->alt_text,
            'unit_price' => $book->sale_price,
            'quantity' => $newQuantity,
            'subtotal' => $book->sale_price * $newQuantity,
        ];

        session()->put('cart', $cart);

        return response()->json([
            'success' => true,
            'message' => __('Book added to cart successfully!'),
            'cart' => $cart,
        ]);
    }

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
                'cart' => $cart,
            ], 422);
        }

        $bookId = $request->input('book_id');
        $quantity = $request->input('quantity');

        if (!isset($cart[$bookId])) {
            return response()->json([
                'success' => false,
                'message' => __('Book not found in cart!'),
                'cart' => $cart,
            ], 404);
        }

        $book = Book::select('id', 'stock_quantity')->findOrFail($bookId);

        if ($book->stock_quantity < $quantity) {
            return response()->json([
                'success' => false,
                'message' => __('Insufficient stock quantity!'),
                'cart' => $cart,
            ], 400);
        }

        $cart[$bookId]['quantity'] = $quantity;
        $cart[$bookId]['subtotal'] = $quantity * $cart[$bookId]['unit_price'];
        session()->put('cart', $cart);

        return response()->json([
            'success' => true,
            'message' => __('Cart updated successfully!'),
            'cart' => $cart,
        ]);
    }

    public function remove(Request $request)
    {
        $cart = session('cart', []);
        $validator = Validator::make($request->all(), [
            'book_id' => 'required|exists:books,id',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'message' => __($validator->errors()->first()),
                'cart' => $cart,
            ], 422);
        }

        $bookId = $request->input('book_id');

        if (!isset($cart[$bookId])) {
            return response()->json([
                'success' => false,
                'message' => __('Book not found in cart!'),
                'cart' => $cart,
            ], 404);
        }

        unset($cart[$bookId]);
        session()->put('cart', $cart);

        return response()->json([
            'success' => true,
            'message' => __('Book removed from cart successfully!'),
            'cart' => $cart,
        ]);
    }

    public function clear()
    {
        session()->forget('cart');

        return response()->json([
            'success' => true,
            'message' => __('Cart cleared successfully!'),
            'cart' => [],
        ]);
    }

    public function payment()
    {
        $cart = session('cart', []);
        if (empty($cart)) {
            return redirect()->route('cart.index')->with('error', __('Your cart is empty.'));
        }

        return view('cart.payment', ['cart' => $cart]);
    }

    public function success()
    {
        session()->forget('cart');
        return view('cart.success');
    }
}
