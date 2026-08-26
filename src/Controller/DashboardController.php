<?php

namespace App\Controller;

use App\Core\ViewController;
use App\Repository\ProductRepository;
use App\Repository\SaleRepository;
use App\Support\AuthService;

class DashboardController extends ViewController
{
    public function __construct(
        AuthService $authService,
        private ProductRepository $productRepository,
        private SaleRepository $saleRepository
    ) {
        parent::__construct($authService);
    }

    public function index()
    {
        $salesCurrentMonth = $this->saleRepository->countCurrentMonth();
        $revenueCurrentMonth = $this->saleRepository->getTotalCurrentMonth();
        $salesPreviousMonth = $this->saleRepository->countPreviousMonth();
        $revenuePreviousMonth = $this->saleRepository->getTotalPreviousMonth();
        $productsCount = $this->productRepository->countAll();
        $lowStockCount = $this->productRepository->countLowStock();

        $salesChange = $this->calculatePercentageChange(
            $salesCurrentMonth,
            $salesPreviousMonth
        );

         $revenueChange = $this->calculatePercentageChange(
            $revenueCurrentMonth,
            $revenuePreviousMonth
        );

        $latestSales = $this->saleRepository->getLastest();


        $this->render('dashboard/index', [
            'salesCurrentMonth' => $salesCurrentMonth,
            'revenueCurrentMonth' => $revenueCurrentMonth,
            'salesChange' => $salesChange,
            'revenueChange' => $revenueChange,
            'productsCount' => $productsCount,
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