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

    public function index()
    {
        $salesCurrentMonth = $this->dashboardRepository->countSalesCurrentMonth();
        $revenueCurrentMonth = $this->dashboardRepository->getRevenueCurrentMonth();

        $salesPreviousMonth = $this->dashboardRepository->countSalesPreviousMonth();
        $revenuePreviousMonth = $this->dashboardRepository->getRevenuePreviousMonth();
        
        $salesChange = $this->calculatePercentageChange(
            $salesCurrentMonth,
            $salesPreviousMonth
        );

        $revenueChange = $this->calculatePercentageChange(
            $revenueCurrentMonth,
            $revenuePreviousMonth
        );

        $pendingSalesCount = $this->dashboardRepository->countPendingSales();
        $lowStockCount = $this->dashboardRepository->countLowStockProducts();
        $latestSales = $this->dashboardRepository->getLatestSales();

        $this->render('dashboard/index', [
            'salesCurrentMonth' => $salesCurrentMonth,
            'revenueCurrentMonth' => $revenueCurrentMonth,
            'salesChange' => $salesChange,
            'revenueChange' => $revenueChange,
            'pendingSalesCount' => $pendingSalesCount,
            'lowStockCount' => $lowStockCount,
            'latestSales' => $latestSales
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