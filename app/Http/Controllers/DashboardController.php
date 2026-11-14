<?php

namespace App\Http\Controllers;

use App\Enums\ProductType;
use App\Enums\SaleStatus;
use App\Models\AuditLog;
use App\Models\Product;
use App\Models\Sale;
use Illuminate\Http\Request;
use Inertia\Inertia;
use Inertia\Response;

class DashboardController extends Controller
{
    /**
     * Carrega os KPIs executivos, Top Produtos, Últimas Vendas, Alertas de Estoque e Trilha de Auditoria.
     */
    public function __invoke(Request $request): Response
    {
        $completedSalesCount = Sale::where('status', SaleStatus::Completed)->count();
        $totalRevenue = (float) Sale::where('status', SaleStatus::Completed)->sum('total_amount');

        // Ranking dos 5 Produtos/Kits mais vendidos (apenas de vendas Concluídas)
        $topProducts = Product::query()
            ->select(['products.id', 'products.name', 'products.sku', 'products.type', 'products.price'])
            ->join('sale_items', 'sale_items.product_id', '=', 'products.id')
            ->join('sales', 'sales.id', '=', 'sale_items.sale_id')
            ->where('sales.status', SaleStatus::Completed->value)
            ->groupBy('products.id', 'products.name', 'products.sku', 'products.type', 'products.price')
            ->selectRaw('SUM(sale_items.quantity) as total_sold, SUM(sale_items.subtotal) as total_revenue')
            ->orderByDesc('total_sold')
            ->take(5)
            ->get();

        // Últimas 5 vendas registradas no balcão
        $recentSales = Sale::query()
            ->with('user:id,name')
            ->withCount('items')
            ->latest('id')
            ->take(5)
            ->get();

        // Produtos físicos (PRO) que estão em alerta de reposição (estoque <= mínimo)
        $lowStockProducts = Product::query()
            ->with('category:id,name')
            ->where('type', ProductType::Simple)
            ->where('is_active', true)
            ->whereColumn('stock_quantity', '<=', 'min_stock')
            ->orderBy('stock_quantity')
            ->take(5)
            ->get(['id', 'category_id', 'name', 'sku', 'type', 'stock_quantity', 'min_stock']);

        // Últimos 8 eventos da Trilha de Auditoria do ERP
        $recentLogs = AuditLog::query()
            ->with('user:id,name,role')
            ->latest('id')
            ->take(8)
            ->get(['id', 'user_id', 'action', 'auditable_type', 'auditable_id', 'ip_address', 'created_at']);

        return Inertia::render('Dashboard', [
            'stats' => [
                'revenue' => $totalRevenue,
                'completed_sales' => $completedSalesCount,
                'average_ticket' => $completedSalesCount > 0 ? round($totalRevenue / $completedSalesCount, 2) : 0,
                'pending_sales' => Sale::where('status', SaleStatus::Pending)->count(),
                'pending_amount' => (float) Sale::where('status', SaleStatus::Pending)->sum('total_amount'),
                'stock_value' => (float) Product::where('type', ProductType::Simple)
                    ->selectRaw('COALESCE(SUM(price * stock_quantity), 0) as total')
                    ->value('total'),
                'physical_units' => (int) Product::where('type', ProductType::Simple)->sum('stock_quantity'),
                'low_stock_count' => Product::where('type', ProductType::Simple)
                    ->where('is_active', true)
                    ->whereColumn('stock_quantity', '<=', 'min_stock')
                    ->count(),
                'total_products' => Product::count(),
            ],
            'topProducts' => $topProducts,
            'recentSales' => $recentSales,
            'lowStockProducts' => $lowStockProducts,
            'recentLogs' => $recentLogs,
        ]);
    }
}
