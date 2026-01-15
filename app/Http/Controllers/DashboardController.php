<?php

namespace App\Http\Controllers;

use App\Models\Product;
use App\Models\Transaction;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class DashboardController extends Controller
{
    public function index()
    {
        // Today's sales
        $todaySales = Transaction::whereDate('created_at', today())->sum('total');

        // Today's transactions count
        $todayTransactions = Transaction::whereDate('created_at', today())->count();

        // Cash vs Online sales today
        $cashSales = Transaction::whereDate('created_at', today())
            ->where('payment_method', 'cash')
            ->sum('total');

        $onlineSales = Transaction::whereDate('created_at', today())
            ->where('payment_method', '!=', 'cash')
            ->sum('total');

        // Best selling products today
        $bestSelling = DB::table('transaction_items')
            ->join('transactions', 'transaction_items.transaction_id', '=', 'transactions.id')
            ->join('products', 'transaction_items.product_id', '=', 'products.id')
            ->whereDate('transactions.created_at', today())
            ->select('products.name', DB::raw('SUM(transaction_items.quantity) as total_quantity'))
            ->groupBy('products.id', 'products.name')
            ->orderBy('total_quantity', 'desc')
            ->limit(5)
            ->get();

        // Tripay payment status
        $tripayPending = Transaction::where('payment_status', 'pending')->count();
        $tripayPaid = Transaction::where('payment_status', 'paid')->count();

        return view('dashboard', compact(
            'todaySales',
            'todayTransactions',
            'cashSales',
            'onlineSales',
            'bestSelling',
            'tripayPending',
            'tripayPaid'
        ));
    }
}
