<?php

namespace App\Repository;

use PDO;
use App\Model\ProductModel;
use App\Model\CategoryModel;

class ProductRepository
{
    public function __construct(private PDO $pdo) {}

    public function getAll(int $limit, int $offset): array
    {
        $stmt = $this->pdo->prepare(
            'SELECT
                `products`.`id`,
                `products`.`name`,
                `products`.`category_id`,
                `products`.`tag`,
                `products`.`price`,
                `products`.`stock`,
                `products`.`description`,
                `products`.`photo`,
                `categories`.`name` AS `category_name`
            FROM `products`
            LEFT JOIN `categories`
                ON `categories`.`id` = `products`.`category_id`
            ORDER BY `products`.`name` ASC
            LIMIT :limit
            OFFSET :offset'
        );

        $stmt->bindValue(':limit', $limit, PDO::PARAM_INT);
        $stmt->bindValue(':offset', $offset, PDO::PARAM_INT);

        $stmt->execute();

        return $stmt->fetchAll(PDO::FETCH_CLASS, ProductModel::class);
    }

    public function getByCategory(
        int $categoryId,
        int $limit,
        int $offset
    ): array {
        $stmt = $this->pdo->prepare(
            'SELECT
                `products`.`id`,
                `products`.`name`,
                `products`.`category_id`,
                `products`.`tag`,
                `products`.`price`,
                `products`.`stock`,
                `products`.`description`,
                `products`.`photo`,
                `categories`.`name` AS `category_name`
            FROM `products`
            LEFT JOIN `categories`
                ON `categories`.`id` = `products`.`category_id`
            WHERE `products`.`category_id` = :category_id
            ORDER BY `products`.`name` ASC
            LIMIT :limit
            OFFSET :offset'
        );

        $stmt->bindValue(':category_id', $categoryId, PDO::PARAM_INT);
        $stmt->bindValue(':limit', $limit, PDO::PARAM_INT);
        $stmt->bindValue(':offset', $offset, PDO::PARAM_INT);

        $stmt->execute();

        return $stmt->fetchAll(PDO::FETCH_CLASS, ProductModel::class);
    }

    public function search(
        string $search,
        ?int $categoryId,
        int $limit,
        int $offset
    ): array {
        $sql =
            'SELECT
                `products`.`id`,
                `products`.`name`,
                `products`.`category_id`,
                `products`.`tag`,
                `products`.`price`,
                `products`.`stock`,
                `products`.`description`,
                `products`.`photo`,
                `categories`.`name` AS `category_name`
            FROM `products`
            LEFT JOIN `categories`
                ON `categories`.`id` = `products`.`category_id`
            WHERE (
                `products`.`name` LIKE :search_name
                OR `products`.`tag` LIKE :search_tag
            )';

        if ($categoryId !== null) {
            $sql .= '
            AND `products`.`category_id` = :category_id';
        }

        $sql .= '
            ORDER BY `products`.`name` ASC
            LIMIT :limit
            OFFSET :offset';

        $stmt = $this->pdo->prepare($sql);

        $stmt->bindValue(':search_name', '%' . $search . '%', PDO::PARAM_STR);
        $stmt->bindValue(':search_tag', '%' . $search . '%', PDO::PARAM_STR);

        if ($categoryId !== null) {
            $stmt->bindValue(':category_id', $categoryId, PDO::PARAM_INT);
        }

        $stmt->bindValue(':limit', $limit, PDO::PARAM_INT);
        $stmt->bindValue(':offset', $offset, PDO::PARAM_INT);

        $stmt->execute();

        return $stmt->fetchAll(PDO::FETCH_CLASS, ProductModel::class);
    }

    public function getForSale(): array
    {
        $stmt = $this->pdo->prepare(
            'SELECT
                `id`,
                `name`,
                `price`,
                `stock`
            FROM `products`
            ORDER BY `name` ASC'
        );

        $stmt->execute();

        return $stmt->fetchAll(PDO::FETCH_CLASS, ProductModel::class);
    }

    public function countAll(): int
    {
        $stmt = $this->pdo->prepare(
            'SELECT COUNT(*)
            FROM `products`'
        );

        $stmt->execute();

        return (int) $stmt->fetchColumn();
    }

    public function countByCategory(int $categoryId): int
    {
        $stmt = $this->pdo->prepare(
            'SELECT COUNT(*)
            FROM `products`
            WHERE `products`.`category_id` = :category_id'
        );

        $stmt->bindValue(':category_id', $categoryId, PDO::PARAM_INT);
        $stmt->execute();

        return (int) $stmt->fetchColumn();
    }

    public function countSearch(
        string $search,
        ?int $categoryId
    ): int {
        $sql =
            'SELECT COUNT(*)
            FROM `products`
            WHERE (
                `products`.`name` LIKE :search_name
                OR `products`.`tag` LIKE :search_tag
            )';

        if ($categoryId !== null) {
            $sql .= '
            AND `products`.`category_id` = :category_id';
        }

        $stmt = $this->pdo->prepare($sql);

        $stmt->bindValue(':search_name', '%' . $search . '%', PDO::PARAM_STR);
        $stmt->bindValue(':search_tag', '%' . $search . '%', PDO::PARAM_STR);

        if ($categoryId !== null) {
            $stmt->bindValue(':category_id', $categoryId, PDO::PARAM_INT);
        }

        $stmt->execute();

        return (int) $stmt->fetchColumn();
    }

    public function getById(int $id): ?ProductModel
    {
        $stmt = $this->pdo->prepare(
            'SELECT
                `id`,
                `name`,
                `category_id`,
                `tag`,
                `price`,
                `stock`,
                `description`,
                `photo` 
            FROM `products` 
            WHERE `id` = :id'
        );

        $stmt->bindValue(':id', $id, PDO::PARAM_INT);
        $stmt->execute();

        $stmt->setFetchMode(PDO::FETCH_CLASS, ProductModel::class);

        $entry = $stmt->fetch();

        return $entry !== false ? $entry : null;
    }

    public function getAllCategories(string $search = ''): array
    {
        $sql =
            'SELECT
                `categories`.`id`,
                `categories`.`name`,
                COUNT(`products`.`id`) AS `product_count`
            FROM `categories` 
            LEFT JOIN `products`
                ON `products`.`category_id` = `categories`.`id`';

        if ($search !== '') {
            $sql .= '
                AND (
                    `products`.`name` LIKE :search_name
                    OR `products`.`tag` LIKE :search_tag
                    )';
        }

        $sql .= '
            GROUP BY
                `categories`.`id`,
                `categories`.`name`
            ORDER BY `categories`.`name` ASC';

        $stmt = $this->pdo->prepare($sql);

        if ($search !== '') {
            $stmt->bindValue(':search_name', '%' . $search . '%', PDO::PARAM_STR);
            $stmt->bindValue(':search_tag', '%' . $search . '%', PDO::PARAM_STR);
        }

        $stmt->execute();

        return $stmt->fetchAll(PDO::FETCH_CLASS, CategoryModel::class);
    }

    public function categoryExists(int $categoryId): bool
    {
        $stmt = $this->pdo->prepare(
            'SELECT 1 
            FROM `categories` 
            WHERE `id` = :id
            LIMIT 1'
        );

        $stmt->bindValue(':id', $categoryId, PDO::PARAM_INT);
        $stmt->execute();

        return $stmt->fetchColumn() !== false;
    }

    public function create(
        string $name,
        int $categoryId,
        string $tag,
        float $price,
        int $stock,
        string $description,
        string $photo
    ): int {
        $stmt = $this->pdo->prepare(
            'INSERT INTO `products` 
            (`name`, `category_id`, `tag`, `price`, `stock`, `description`, `photo`)
            VALUES
            (:name, :category_id, :tag, :price, :stock, :description, :photo)'
        );

        $stmt->bindValue(':name', $name, PDO::PARAM_STR);
        $stmt->bindValue(':category_id', $categoryId, PDO::PARAM_INT);
        $stmt->bindValue(':tag', $tag, PDO::PARAM_STR);
        $stmt->bindValue(':price', $price);
        $stmt->bindValue(':stock', $stock, PDO::PARAM_INT);
        $stmt->bindValue(':description', $description, PDO::PARAM_STR);
        $stmt->bindValue(':photo', $photo, PDO::PARAM_STR);

        $stmt->execute();

        return (int) $this->pdo->lastInsertId();
    }

    public function update(
        int $productId,
        string $name,
        int $categoryId,
        string $tag,
        float $price,
        int $stock,
        string $description,
        string $photo
    ): void {
        $stmt = $this->pdo->prepare(
            'UPDATE `products`
            SET 
                `name` = :name,
                `category_id` = :category_id,
                `tag` = :tag,
                `price` = :price,
                `stock` = :stock,
                `description` = :description,
                `photo` = :photo
            WHERE `id` = :id'
        );

        $stmt->bindValue(':id', $productId, PDO::PARAM_INT);
        $stmt->bindValue(':name', $name, PDO::PARAM_STR);
        $stmt->bindValue(':category_id', $categoryId, PDO::PARAM_INT);
        $stmt->bindValue(':tag', $tag, PDO::PARAM_STR);
        $stmt->bindValue(':price', $price);
        $stmt->bindValue(':stock', $stock, PDO::PARAM_INT);
        $stmt->bindValue(':description', $description, PDO::PARAM_STR);
        $stmt->bindValue(':photo', $photo, PDO::PARAM_STR);

        $stmt->execute();
    }

    public function delete(int $id): void
    {
        $stmt = $this->pdo->prepare(
            'DELETE 
            FROM `products` 
            WHERE `id` = :id'
        );

        $stmt->bindValue(':id', $id, PDO::PARAM_INT);

        $stmt->execute();
    }

    public function decreaseStock(int $productId, int $quantity): bool
    {
        $stmt = $this->pdo->prepare(
            'UPDATE `products`
            SET `stock` = `stock` - :quantity
            WHERE `id` = :id
            AND `stock` >= :quantity'
        );

        $stmt->bindValue(':id', $productId, PDO::PARAM_INT);
        $stmt->bindValue(':quantity', $quantity, PDO::PARAM_INT);

        $stmt->execute();

        return $stmt->rowCount() > 0;
    }

    public function increaseStock(int $productId, int $quantity): void
    {
        $stmt = $this->pdo->prepare(
            'UPDATE `products`
            SET `stock` = `stock` + :quantity
            WHERE `id` = :id'
        );

        $stmt->bindValue(':id', $productId, PDO::PARAM_INT);
        $stmt->bindValue(':quantity', $quantity, PDO::PARAM_INT);

        $stmt->execute();
    }
}
