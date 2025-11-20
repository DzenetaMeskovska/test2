<?php
namespace App\Model;

use PDO;

class TechProduct extends Product {
    
    public static function getCategoryId(PDO $db): int {
        $stmt = $db->query("SELECT id_categories FROM categories WHERE name = 'tech'");
        $catid = $stmt->fetch(PDO::FETCH_ASSOC);
        return $catid['id_categories'] ?? 0;
    }

    public static function getTechProducts(PDO $db): array {
        /* $categoryId = getCategoryId();
        $stmt = $db->prepare("SELECT * FROM products WHERE category_id = :id");
        $stmt->execute(['id' => $categoryId]);
        $rows = $stmt->fetchAll(PDO::FETCH_ASSOC);
        return array_map(fn($row) => new self($row), $rows); */
        return static::getByCategory($db);
    }
}