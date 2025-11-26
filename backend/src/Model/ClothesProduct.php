<?php
namespace App\Model;

use PDO;

class ClothesProduct extends Product {
    
    public static function getCategoryIdByName(PDO $db): int {
        $stmt = $db->query("SELECT id_categories FROM categories WHERE name = 'clothes'");
        $catid = $stmt->fetch(PDO::FETCH_ASSOC);
        return $catid['id_categories'] ?? 1;
    }

    public static function getClothesProducts(PDO $db): array {
        return static::getByCategory($db);
    }

    public function getProductAttributes(PDO $db): array {
        $attributes = Attribute::getByProductId($db, $this->id);

        $this->attributes = [];

        foreach ($attributes as $a) {
            $this->attributes[] = (object)[
                'name' => $a->getName(),
                'type' => $a->getType(),
                'items' => array_map(fn($it) => (object)[
                    'displayValue' => $it->getDisplayValue(),
                    'value' => $it->getValue()
                ], $a->getItems())
            ];
        }

        return $this->attributes;
    }
}