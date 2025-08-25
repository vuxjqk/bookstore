<?php

namespace App\Http\Controllers;

use App\Models\Book;
use App\Models\Order;
use App\Models\PurchaseOrder;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;

class DashboardController extends Controller
{
    public function index()
    {
        $today = Carbon::today();

        $ordersToday = Order::whereDate('order_date', $today);
        $totalOrdersToday = $ordersToday->count();
        $totalRevenueToday = $ordersToday->sum(DB::raw('total_amount - discount_amount'));

        $totalPurchaseCostToday = PurchaseOrder::whereDate('order_date', $today)->sum(DB::raw('total_amount - discount_amount'));

        $totalBooks = Book::sum('stock_quantity');

        $monthlyRevenue = Order::selectRaw("DATE_FORMAT(order_date, '%Y-%m') as month, SUM(total_amount - discount_amount) as revenue")
            ->where('order_date', '>=', Carbon::now()->subMonths(6)->startOfMonth())
            ->groupBy('month')
            ->orderBy('month')
            ->pluck('revenue', 'month');

        $topBooks = DB::table('order_items')
            ->join('books', 'order_items.book_id', '=', 'books.id')
            ->select('books.title', DB::raw('SUM(order_items.quantity) as total_sold'))
            ->groupBy('books.id', 'books.title')
            ->orderByDesc('total_sold')
            ->get();

        $topN = 3;
        $topBooksLimited = $topBooks->take($topN);
        $otherTotal = $topBooks->skip($topN)->sum('total_sold');
        if ($otherTotal > 0) {
            $topBooksLimited->push((object)[
                'title' => __('Other'),
                'total_sold' => $otherTotal,
            ]);
        }

        $recentOrders = Order::latest('updated_at')->take(4)->get();

        return view('dashboard.index', compact(
            'totalOrdersToday',
            'totalRevenueToday',
            'totalPurchaseCostToday',
            'totalBooks',
            'monthlyRevenue',
            'topBooksLimited',
            'recentOrders'
        ));
    }
}
