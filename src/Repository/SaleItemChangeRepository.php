<?php

namespace App\Repository;

use PDO;

class SaleItemChangeRepository
{
    public function __construct(private PDO $pdo) {}

    public function create(
        int $saleId,
        ?int $saleItemId,
        int $productId,
        string $productName,
        string $action,
        ?int $oldQuantity,
        ?int $newQuantity,
        ?float $oldUnitPrice,
        ?float $newUnitPrice,
        ?int $userId
    ): void {
        $stmt = $this->pdo->prepare(
            'INSERT INTO `sale_item_changes`
                (`sale_id`, `sale_item_id`, `product_id`, `product_name`,
                `action`, `old_quantity`, `new_quantity`,
                `old_unit_price`, `new_unit_price`, `user_id`)
            VALUES
                (:sale_id, :sale_item_id, :product_id, :product_name,
                :action, :old_quantity, :new_quantity,
                :old_unit_price, :new_unit_price, :user_id)'
        );

        $stmt->bindValue(':sale_id', $saleId, PDO::PARAM_INT);
        $stmt->bindValue(
            ':sale_item_id',
            $saleItemId,
            $saleItemId === null ? PDO::PARAM_NULL : PDO::PARAM_INT
        );
        $stmt->bindValue(':product_id', $productId, PDO::PARAM_INT);
        $stmt->bindValue(':product_name', $productName, PDO::PARAM_STR);
        $stmt->bindValue(':action', $action, PDO::PARAM_STR);
        $stmt->bindValue(
            ':old_quantity',
            $oldQuantity,
            $oldQuantity === null ? PDO::PARAM_NULL : PDO::PARAM_INT
        );
        $stmt->bindValue(
            ':new_quantity',
            $newQuantity,
            $newQuantity === null ? PDO::PARAM_NULL : PDO::PARAM_INT
        );
        $stmt->bindValue(
            ':old_unit_price',
            $oldUnitPrice,
            $oldUnitPrice === null ? PDO::PARAM_NULL : PDO::PARAM_STR
        );
        $stmt->bindValue(
            ':new_unit_price',
            $newUnitPrice,
            $newUnitPrice === null ? PDO::PARAM_NULL : PDO::PARAM_STR
        );
        $stmt->bindValue(
            ':user_id',
            $userId,
            $userId === null ? PDO::PARAM_NULL : PDO::PARAM_INT
        );

        $stmt->execute();
    }
}
