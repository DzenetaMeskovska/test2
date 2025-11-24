<?php
namespace App\Model;

use PDO;

class TechProduct extends Product {
    
    public static function getCategoryIdByName(PDO $db): int {
        $stmt = $db->query("SELECT id_categories FROM categories WHERE name = 'tech'");
        $catid = $stmt->fetch(PDO::FETCH_ASSOC);
        return $catid['id_categories'] ?? 0;
    }

    public static function getTechProducts(PDO $db): array {
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