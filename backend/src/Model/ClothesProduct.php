<?php
namespace App\Model;

use PDO;

class ClothesProduct extends Product {
    
    public static function getCategoryId(PDO $db): int {
        $stmt = $db->query("SELECT id_categories FROM categories WHERE name = 'clothes'");
        $catid = $stmt->fetch(PDO::FETCH_ASSOC);
        return $catid['id_categories'] ?? 0;
    }

    public static function getClothesProducts(PDO $db): array {
        return static::getByCategory($db);
    }
}