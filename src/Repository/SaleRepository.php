<?php

namespace App\Repository;

use PDO;
use App\Model\SaleModel;

class SaleRepository
{
    public function __construct(private PDO $pdo) {}

    public function getAll(int $limit, int $offset): array
    {
        $stmt = $this->pdo->prepare(
            'SELECT 
                `sales`.*,
                COALESCE(SUM(`sale_items`.`quantity`), 0) AS `items_quantity`
            FROM `sales`
            LEFT JOIN `sale_items` 
                ON `sale_items`.`sale_id` = `sales`.`id`
            GROUP BY `sales`.`id`
            ORDER BY `sales`.`created_at` DESC
            LIMIT :limit
            OFFSET :offset'
        );

        $stmt->bindValue(':limit', $limit, PDO::PARAM_INT);
        $stmt->bindValue(':offset', $offset, PDO::PARAM_INT);

        $stmt->execute();

        return $stmt->fetchAll(PDO::FETCH_CLASS, SaleModel::class);
    }

    public function search(
        string $search,
        ?string $status,
        int $limit,
        int $offset
    ): array {
        $sql =
            'SELECT
                `sales`.*,
                COALESCE(SUM(`sale_items`.`quantity`), 0) AS `items_quantity`
            FROM `sales`
            LEFT JOIN `sale_items`
                ON `sale_items`.`sale_id` = `sales`.`id`
            WHERE `sales`.`customer_name` LIKE :search';

        if ($status !== null) {
            $sql .= '
                AND `sales`.`status` = :status';
        }

        $sql .= '
            GROUP BY `sales`.`id`
            ORDER BY `sales`.`created_at` DESC
            LIMIT :limit
            OFFSET :offset';

        $stmt = $this->pdo->prepare($sql);

        $stmt->bindValue(':search', '%' . $search . '%', PDO::PARAM_STR);

        if ($status !== null) {
            $stmt->bindValue(':status', $status, PDO::PARAM_STR);
        }

        $stmt->bindValue(':limit', $limit, PDO::PARAM_INT);
        $stmt->bindValue(':offset', $offset, PDO::PARAM_INT);

        $stmt->execute();

        return $stmt->fetchAll(PDO::FETCH_CLASS, SaleModel::class);
    }

    public function countAll(): int
    {
        $stmt = $this->pdo->prepare(
            'SELECT COUNT(*)
            FROM `sales`'
        );

        $stmt->execute();

        return (int) $stmt->fetchColumn();
    }

    public function countSearch(
        string $search,
        ?string $status
    ): int {
        $sql =
            'SELECT COUNT(*)
            FROM `sales`
            WHERE `sales`.`customer_name` LIKE :search';

        if ($status !== null) {
            $sql .= '
                AND `sales`.`status` = :status';
        }

        $stmt = $this->pdo->prepare($sql);

        $stmt->bindValue(':search', '%' . $search . '%', PDO::PARAM_STR);

        if ($status !== null) {
            $stmt->bindValue(':status', $status, PDO::PARAM_STR);
        }

        $stmt->execute();

        return (int) $stmt->fetchColumn();
    }

    public function getStatusCounts(string $search = ''): array
    {
        $stmt = $this->pdo->prepare(
            'SELECT
                COUNT(*) AS `all_count`,
                SUM(`status` = \'pending\') AS `pending_count`,
                SUM(`status` = \'completed\') AS `completed_count`,
                SUM(`status` = \'cancelled\') AS `cancelled_count`
            FROM `sales`
            WHERE `customer_name` LIKE :search'
        );

        $stmt->bindValue(':search', '%' . $search . '%', PDO::PARAM_STR);

        $stmt->execute();

        $counts = $stmt->fetch(PDO::FETCH_ASSOC);

        return [
            'all' => (int) ($counts['all_count'] ?? 0),
            'pending' => (int) ($counts['pending_count'] ?? 0),
            'completed' => (int) ($counts['completed_count'] ?? 0),
            'cancelled' => (int) ($counts['cancelled_count'] ?? 0)
        ];
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
        ?int $userId
    ): int {
        $stmt = $this->pdo->prepare(
            'INSERT INTO `sales`
            (`customer_name`, `discount_amount`, `total_amount`, `user_id`)
            VALUES
            (:customer_name, :discount_amount, :total_amount, :user_id)'
        );

        $stmt->bindValue(':customer_name', $customerName);
        $stmt->bindValue(':discount_amount', $discountAmount);
        $stmt->bindValue(':total_amount', $totalAmount);
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
        float $totalAmount
    ): void {
        $stmt = $this->pdo->prepare(
            'UPDATE `sales`
            SET
                `customer_name` = :customer_name,
                `discount_amount` = :discount_amount,
                `total_amount` = :total_amount
            WHERE `id` = :id'
        );

        $stmt->bindValue(':id', $saleId, PDO::PARAM_INT);
        $stmt->bindValue(':customer_name', $customerName);
        $stmt->bindValue(':discount_amount', $discountAmount);
        $stmt->bindValue(':total_amount', $totalAmount);

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
