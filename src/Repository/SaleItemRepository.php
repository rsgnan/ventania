<?php

namespace App\Repository;

use PDO;

class SaleItemRepository
{
    public function __construct(private PDO $pdo) {}

    public function getBySaleId(int $saleId): array
    {
        $stmt = $this->pdo->prepare(
            'SELECT * 
            FROM `sale_items`
            WHERE `sale_id` = :sale_id
                AND `deleted_at` IS NULL'
        );

        $stmt->bindValue(':sale_id', $saleId, PDO::PARAM_INT);
        $stmt->execute();

        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function getAllBySaleId(int $saleId): array
    {
        $stmt = $this->pdo->prepare(
            'SELECT * 
            FROM `sale_items`
            WHERE `sale_id` = :sale_id
            ORDER BY `id` ASC'
        );

        $stmt->bindValue(':sale_id', $saleId, PDO::PARAM_INT);
        $stmt->execute();

        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function create(
        int $saleId,
        int $productId,
        string $productName,
        float $originalPrice,
        float $unitPrice,
        int $quantity,
        float $subtotal
    ): void {
        $stmt = $this->pdo->prepare(
            'INSERT INTO `sale_items`
                (`sale_id`, `product_id`, `product_name`, `original_price`,
                `unit_price`, `quantity`, `subtotal`)
            VALUES
                (:sale_id, :product_id, :product_name, :original_price,
                :unit_price, :quantity, :subtotal)'
        );

        $stmt->bindValue(':sale_id', $saleId, PDO::PARAM_INT);
        $stmt->bindValue(':product_id', $productId, PDO::PARAM_INT);
        $stmt->bindValue(':product_name', $productName, PDO::PARAM_STR);
        $stmt->bindValue(':original_price', $originalPrice);
        $stmt->bindValue(':unit_price', $unitPrice);
        $stmt->bindValue(':quantity', $quantity, PDO::PARAM_INT);
        $stmt->bindValue(':subtotal', $subtotal);

        $stmt->execute();
    }

    public function update(
        int $saleItemId,
        string $productName,
        float $originalPrice,
        float $unitPrice,
        int $quantity,
        float $subtotal
    ): void {
        $stmt = $this->pdo->prepare(
            'UPDATE `sale_items`
            SET
                `product_name` = :product_name,
                `original_price` = :original_price,
                `unit_price` = :unit_price,
                `quantity` = :quantity,
                `subtotal` = :subtotal
            WHERE `id` = :id
                AND `deleted_at` IS NULL'
        );

        $stmt->bindValue(':id', $saleItemId, PDO::PARAM_INT);
        $stmt->bindValue(':product_name', $productName, PDO::PARAM_STR);
        $stmt->bindValue(':original_price', $originalPrice);
        $stmt->bindValue(':unit_price', $unitPrice);
        $stmt->bindValue(':quantity', $quantity, PDO::PARAM_INT);
        $stmt->bindValue(':subtotal', $subtotal);

        $stmt->execute();
    }

    public function softDelete(int $saleItemId): void
    {
        $stmt = $this->pdo->prepare(
            'UPDATE `sale_items`
            SET `deleted_at` = NOW()
            WHERE `id` = :id
                AND `deleted_at` IS NULL'
        );

        $stmt->bindValue(':id', $saleItemId, PDO::PARAM_INT);
        $stmt->execute();
    }
}
