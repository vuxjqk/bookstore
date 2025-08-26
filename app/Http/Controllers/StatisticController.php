<?php

namespace App\Http\Controllers;

use App\Models\Book;
use App\Models\Order;
use App\Models\PurchaseOrder;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class StatisticController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $today = Carbon::today();
        $filter = $request->input('filter', 'today');

        $ordersQuery = Order::query();
        $purchaseOrdersQuery = PurchaseOrder::query();

        switch ($filter) {
            case 'week':
                $startDate = $today->startOfWeek();
                break;
            case 'month':
                $startDate = $today->startOfMonth();
                break;
            case 'custom':
                $startDate = Carbon::parse($request->input('start_date'));
                $endDate = Carbon::parse($request->input('end_date'));
                $ordersQuery->whereBetween('order_date', [$startDate, $endDate]);
                $purchaseOrdersQuery->whereBetween('order_date', [$startDate, $endDate]);
                break;
            case 'today':
            default:
                $startDate = $today;
                break;
        }

        if (!isset($endDate)) {
            $ordersQuery->whereDate('order_date', $startDate);
            $purchaseOrdersQuery->whereDate('order_date', $startDate);
        }

        $totalOrdersToday = $ordersQuery->count();
        $totalRevenueToday = $ordersQuery->sum(DB::raw('total_amount - discount_amount'));
        $totalPurchaseCostToday = $purchaseOrdersQuery->sum(DB::raw('total_amount - discount_amount'));
        $totalBooks = Book::sum('stock_quantity');

        $monthlyRevenue = Order::selectRaw("DATE_FORMAT(order_date, '%Y-%m') as month, SUM(total_amount - discount_amount) as revenue")
            ->where('order_date', '>=', $startDate)
            ->groupBy('month')
            ->orderBy('month')
            ->pluck('revenue', 'month');

        $topBooks = DB::table('order_items')
            ->join('books', 'order_items.book_id', '=', 'books.id')
            ->select('books.title', DB::raw('SUM(order_items.quantity) as total_sold'))
            ->whereIn('order_items.order_id', $ordersQuery->select('id'))
            ->groupBy('books.id', 'books.title')
            ->orderByDesc('total_sold')
            ->get();

        $topN = 3;
        $topBooksLimited = $topBooks->take($topN);
        $otherTotal = $topBooks->skip($topN)->sum('total_sold');
        if ($otherTotal > 0) {
            $topBooksLimited->push((object)['title' => __('Other'), 'total_sold' => $otherTotal]);
        }

        $recentOrders = $ordersQuery->latest('updated_at')->take(4)->get();

        $endDate = $endDate ?? null;
        return view('statistics.index', compact(
            'totalOrdersToday',
            'totalRevenueToday',
            'totalPurchaseCostToday',
            'totalBooks',
            'monthlyRevenue',
            'topBooksLimited',
            'recentOrders',
            'filter',
            'startDate',
            'endDate'
        ));
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
        //
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
    public function edit(string $id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        //
    }
}
