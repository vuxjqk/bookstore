<?php

use App\Http\Controllers\Auth\SocialiteController;
use App\Http\Controllers\AuthorController;
use App\Http\Controllers\BookController;
use App\Http\Controllers\CartController;
use App\Http\Controllers\CategoryController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\FavoriteController;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\InventoryTransactionController;
use App\Http\Controllers\OrderController;
use App\Http\Controllers\PDFController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\PublisherController;
use App\Http\Controllers\PurchaseOrderController;
use App\Http\Controllers\ReviewController;
use App\Http\Controllers\SupplierController;
use App\Http\Controllers\UserController;
use App\Models\Book;
use Illuminate\Support\Facades\Route;

Route::get('/lang/{locale}', function ($locale) {
    if (in_array($locale, ['vi', 'en'])) {
        session()->put('locale', $locale);
        return redirect()->back()->with('success', __('Language changed'));
    }
    return redirect()->back()->with('error', __('Invalid language'));
})->name('change.locale');

Route::get('/', function () {
    $related_books = Book::inRandomOrder()
        ->take(4)
        ->get();
    return view('index', compact('related_books'));
});

Route::get('/home', [HomeController::class, 'index'])->name('home.index');
Route::get('/home/autocomplete', [HomeController::class, 'autocomplete'])->name('home.autocomplete');
Route::get('/home/{book:slug}', [HomeController::class, 'show'])->name('home.show');

Route::post('/cart/add', [CartController::class, 'add'])->name('cart.add');
Route::get('/cart', [CartController::class, 'index'])->name('cart.index');
Route::post('/cart/update', [CartController::class, 'update'])->name('cart.update');
Route::post('/cart/remove', [CartController::class, 'remove'])->name('cart.remove');
Route::post('/cart/clear', [CartController::class, 'clear'])->name('cart.clear');
Route::get('/cart/payment', [CartController::class, 'payment'])->name('cart.payment');
Route::get('/cart/success', [CartController::class, 'success'])->name('cart.success');
Route::post('/orders/store', [OrderController::class, 'store'])->name('orders.store');

Route::get('/auth/{provider}', [SocialiteController::class, 'redirect']);
Route::get('/auth/{provider}/callback', [SocialiteController::class, 'callback']);

Route::middleware(['auth', 'verified'])->group(function () {
    Route::get('/welcome', function () {
        return view('welcome');
    })->name('dashboard');

    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');

    Route::get('/favorites', [FavoriteController::class, 'index'])->name('favorites.index');
    Route::post('/favorites/store', [FavoriteController::class, 'store'])->name('favorites.store');
    Route::post('/favorites/destroy', [FavoriteController::class, 'destroy'])->name('favorites.destroy');

    Route::post('/reviews/store', [ReviewController::class, 'store'])->name('reviews.store');

    Route::middleware(['role:admin'])->group(function () {
        Route::get('dashboard', [DashboardController::class, 'index'])->name('dashboard.index');
        Route::resource('categories', CategoryController::class);
        Route::resource('authors', AuthorController::class);
        Route::resource('publishers', PublisherController::class);
        Route::resource('suppliers', SupplierController::class);

        Route::resource('books', BookController::class);
        Route::get('/books-export', [BookController::class, 'export'])->name('books.export');

        Route::get('/orders', [OrderController::class, 'index'])->name('orders.index');
        Route::get('/orders/{order}', [OrderController::class, 'show'])->name('orders.show');
        Route::put('/orders/{order}', [OrderController::class, 'update'])->name('orders.update');
        Route::delete('/orders/{order}', [OrderController::class, 'destroy'])->name('orders.destroy');
        Route::get('/orders/{order}/export', [PDFController::class, 'exportOrderInvoice'])->name('orders.export');

        Route::get('/purchase_orders', [PurchaseOrderController::class, 'index'])->name('purchase_orders.index');
        Route::get('/purchase_orders/{purchaseOrder}', [PurchaseOrderController::class, 'show'])->name('purchase_orders.show');
        Route::put('/purchase_orders/{purchaseOrder}', [PurchaseOrderController::class, 'update'])->name('purchase_orders.update');
        Route::delete('/purchase_orders/{purchaseOrder}', [PurchaseOrderController::class, 'destroy'])->name('purchase_orders.destroy');
        Route::get('/purchase_orders/{purchaseOrder}/export', [PDFController::class, 'exportPurchaseOrderInvoice'])->name('purchase_orders.export');

        Route::get('/inventory_transactions', [InventoryTransactionController::class, 'index'])->name('inventory_transactions.index');

        Route::resource('users', UserController::class);

        Route::resource('settings', BookController::class);
        Route::resource('statistics', BookController::class);
        Route::resource('reports', BookController::class);
        Route::resource('system', BookController::class);
        Route::resource('permissions', BookController::class);
    });
});

require __DIR__ . '/auth.php';
