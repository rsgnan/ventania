<?php

namespace App\Repository;

use PDO;

class DashboardRepository
{
    public function __construct(private PDO $pdo) {}

    public function countSalesCurrentMonth(): int
    {
        $stmt = $this->pdo->prepare(
            'SELECT COUNT(*)
            FROM `sales`
            WHERE YEAR(`created_at`) = YEAR(CURDATE())
            AND MONTH(`created_at`) = MONTH(CURDATE())'
        );

        $stmt->execute();

        return (int) $stmt->fetchColumn();
    }

    public function getRevenueCurrentMonth(): float
    {
        $stmt = $this->pdo->prepare(
            'SELECT COALESCE(SUM(`total_amount`), 0)
            FROM `sales`
            WHERE YEAR(`created_at`) = YEAR(CURDATE())
            AND MONTH(`created_at`) = MONTH(CURDATE())'
        );

        $stmt->execute();

        return (float) $stmt->fetchColumn();
    }

    public function countSalesPreviousMonth(): int
    {
        $stmt = $this->pdo->prepare(
            'SELECT COUNT(*)
            FROM `sales`
            WHERE YEAR(`created_at`) = YEAR(CURDATE() - INTERVAL 1 MONTH)
            AND MONTH(`created_at`) = MONTH(CURDATE() - INTERVAL 1 MONTH)'
        );

        $stmt->execute();

        return (int) $stmt->fetchColumn();
    }

    public function getRevenuePreviousMonth(): float
    {
        $stmt = $this->pdo->prepare(
            'SELECT COALESCE(SUM(`total_amount`), 0)
            FROM `sales`
            WHERE YEAR(`created_at`) = YEAR(CURDATE() - INTERVAL 1 MONTH)
            AND MONTH(`created_at`) = MONTH(CURDATE() - INTERVAL 1 MONTH)'
        );

        $stmt->execute();

        return (float) $stmt->fetchColumn();
    }

    public function countPendingSales(): int
    {
        $stmt = $this->pdo->prepare(
            'SELECT COUNT(*)
            FROM `sales`
            WHERE `status` = :status'
        );

        $stmt->bindValue(':status', 'pending');
        $stmt->execute();

        return (int) $stmt->fetchColumn();
    }

    public function countLowStockProducts(int $limit = 5): int
    {
        $stmt = $this->pdo->prepare(
            'SELECT COUNT(*)
            FROM `products`
            WHERE `stock` <= :limit'
        );

        $stmt->bindValue(':limit', $limit, PDO::PARAM_INT);
        $stmt->execute();

        return (int) $stmt->fetchColumn();
    }

    public function getLatestSales(int $limit = 5): array
    {
        $stmt = $this->pdo->prepare(
            'SELECT
                `sales`.`id`,
                `sales`.`created_at`,
                `sales`.`total_amount`,
                `sales`.`status`,
                COALESCE(SUM(`sale_items`.`quantity`), 0) AS `items_quantity`
            FROM `sales`
            LEFT JOIN `sale_items`
                ON `sale_items`.`sale_id` = `sales`.`id`
            GROUP BY
                `sales`.`id`,
                `sales`.`created_at`,
                `sales`.`total_amount`,
                `sales`.`status`
            ORDER BY `sales`.`created_at` DESC
            LIMIT :limit'
        );

        $stmt->bindValue(':limit', $limit, PDO::PARAM_INT);
        $stmt->execute();

        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }
}
