<?php

namespace App\Controller;

use App\Core\ViewController;
use App\Repository\DashboardRepository;
use App\Support\AuthService;

class DashboardController extends ViewController
{
    public function __construct(
        AuthService $authService,
        private DashboardRepository $dashboardRepository
    ) {
        parent::__construct($authService);
    }

    public function index(): void
    {
        // Dados do mês atual e anteriores usados nos indicadores de variação
        $salesCurrentMonth = $this->dashboardRepository->countSalesCurrentMonth();
        $salesPreviousMonth = $this->dashboardRepository->countSalesPreviousMonth();

        $revenueCurrentMonth = $this->dashboardRepository->getRevenueCurrentMonth();
        $revenuePreviousMonth = $this->dashboardRepository->getRevenuePreviousMonth();

        $salesChange = $this->calculatePercentageChange(
            $salesCurrentMonth,
            $salesPreviousMonth
        );

        $revenueChange = $this->calculatePercentageChange(
            $revenueCurrentMonth,
            $revenuePreviousMonth
        );

        // Demais informações exibidas no dashboard
        $pendingSalesCount = $this->dashboardRepository->countPendingSales();
        $lowStockCount = $this->dashboardRepository->countLowStockProducts();
        $latestSales = $this->dashboardRepository->getLatestSales();
        $bestSellingProducts = $this->dashboardRepository->getBestSellingProducts();
        $topSellingCategories = $this->dashboardRepository->getTopSellingCategories();

        $this->render('dashboard/index', [
            'salesCurrentMonth' => $salesCurrentMonth,
            'revenueCurrentMonth' => $revenueCurrentMonth,
            'salesChange' => $salesChange,
            'revenueChange' => $revenueChange,
            'pendingSalesCount' => $pendingSalesCount,
            'lowStockCount' => $lowStockCount,
            'latestSales' => $latestSales,
            'bestSellingProducts' => $bestSellingProducts,
            'topSellingCategories' => $topSellingCategories
        ]);
    }

    private function calculatePercentageChange(
        float|int $current,
        float|int $previous
    ): float {
        if ($previous == 0) {
            return $current > 0 ? 100 : 0;
        }

        return (($current - $previous) / $previous) * 100;
    }
}
