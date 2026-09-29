<?php

namespace App\Http\Controllers;

use App\Models\Product;
use App\Models\Sale;
use Illuminate\View\View;

class DashboardController extends Controller
{
    public function index(): View
    {
        $productsCount = Product::count();

        $stockQuantity = Product::sum('stock');

        $pendingSalesCount = Sale::where('status', 'pending')->count();

        $completedSalesCount = Sale::where('status', 'completed')->count();

        $recentSales = Sale::orderByDesc('created_at')
            ->limit(5)
            ->get();

        $lowStockProducts = Product::where('minimum_stock', '>', 0)
            ->whereColumn('stock', '<=', 'minimum_stock')
            ->orderByRaw('(minimum_stock - stock) DESC')
            ->limit(5)
            ->get();

        return view('dashboard', [
            'productsCount' => $productsCount,
            'stockQuantity' => $stockQuantity,
            'pendingSalesCount' => $pendingSalesCount,
            'completedSalesCount' => $completedSalesCount,
            'recentSales' => $recentSales,
            'lowStockProducts' => $lowStockProducts,
        ]);
    }
}
