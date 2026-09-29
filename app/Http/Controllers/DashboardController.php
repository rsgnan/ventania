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

        return view('dashboard', [
            'productsCount' => $productsCount,
            'stockQuantity' => $stockQuantity,
            'pendingSalesCount' => $pendingSalesCount,
            'completedSalesCount' => $completedSalesCount,
        ]);
    }
}
