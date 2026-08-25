<?php

namespace App\Repository;

use PDO;
use App\Model\SaleModel;

class SaleRepository
{
    public function __construct(private PDO $pdo) {}

    public function getAll(): array
    {
        $stmt = $this->pdo->prepare(
            'SELECT * 
            FROM `sales` 
            ORDER BY `id` DESC'
        );

        $stmt->execute();

        return $stmt->fetchAll(PDO::FETCH_CLASS, SaleModel::class);
    }

    public function getList(): array
    {
        $stmt = $this->pdo->prepare(
            'SELECT 
                `sales`.*,
                COUNT(`sale_items`.`id`) AS `item_count`,
                COALESCE(SUM(`sale_items`.`quantity`), 0) AS `items_quantity`
            FROM `sales`
            LEFT JOIN `sale_items` 
                ON `sale_items`.`sale_id` = `sales`.`id`
            GROUP BY `sales`.`id`
            ORDER BY `sales`.`id` DESC'
        );

        $stmt->execute();

        return $stmt->fetchAll(PDO::FETCH_CLASS, SaleModel::class);
    }

    public function getById(int $id): ?SaleModel
    {
        $stmt = $this->pdo->prepare(
            'SELECT * 
            FROM `sales`
            WHERE `id` = :id'
        );

        $stmt->bindValue(':id', $id, PDO::PARAM_INT);
        $stmt->execute();

        $stmt->setFetchMode(PDO::FETCH_CLASS, SaleModel::class);

        $sale = $stmt->fetch();

        return $sale !== false ? $sale : null;
    }

    public function create(
        string $customerName,
        float $discountAmount,
        float $totalAmount,
        string $status,
        ?int $userId
    ): int {
        $stmt = $this->pdo->prepare(
            'INSERT INTO `sales`
            (`customer_name`, `discount_amount`, `total_amount`, `status`, `user_id`)
            VALUES
            (:customer_name, :discount_amount, :total_amount, :status, :user_id)'
        );

        $stmt->bindValue(':customer_name', $customerName);
        $stmt->bindValue(':discount_amount', $discountAmount);
        $stmt->bindValue(':total_amount', $totalAmount);
        $stmt->bindValue(':status', $status);
        $stmt->bindValue(
            ':user_id',
            $userId,
            $userId === null ? PDO::PARAM_NULL : PDO::PARAM_INT
        );

        $stmt->execute();

        return (int) $this->pdo->lastInsertId();
    }

    public function update(
        int $saleId,
        string $customerName,
        float $discountAmount,
        float $totalAmount,
        string $status
    ): void {
        $stmt = $this->pdo->prepare(
            'UPDATE `sales`
            SET
                `customer_name` = :customer_name,
                `discount_amount` = :discount_amount,
                `total_amount` = :total_amount,
                `status` = :status
            WHERE `id` = :id'
        );

        $stmt->bindValue(':id', $saleId, PDO::PARAM_INT);
        $stmt->bindValue(':customer_name', $customerName);
        $stmt->bindValue(':discount_amount', $discountAmount);
        $stmt->bindValue(':total_amount', $totalAmount);
        $stmt->bindValue(':status', $status);

        $stmt->execute();
    }

    public function updateStatus(int $saleId, string $status): void
    {
        $stmt = $this->pdo->prepare(
            'UPDATE `sales`
            SET `status` = :status
            WHERE `id` = :id'
        );

        $stmt->bindValue(':id', $saleId, PDO::PARAM_INT);
        $stmt->bindValue(':status', $status);

        $stmt->execute();
    }
}
