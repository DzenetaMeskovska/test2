<?php
namespace App\Model;

use PDO;

class Attribute {
    public int $id;
    public string $name;
    public string $type;
    public array $items = [];

    public function __construct(array $data) {
        $this->id = $data['id_attributes'];
        $this->name = $data['name'];
        $this->type = $data['type'];
    }

    public static function getByProductId(PDO $db, string $productId): array {
        $stmt = $db->prepare("
            SELECT a.* FROM attributes a
            JOIN product_attributes pa ON pa.attribute_id = a.id_attributes
            WHERE pa.product_id = :pid
        ");
        $stmt->execute(['pid' => $productId]);
        $rows = $stmt->fetchAll(PDO::FETCH_ASSOC);
        $attributes = array_map(fn($row) => new self($row), $rows);
        foreach ($attributes as $attr) {
            $attr->loadItems($db);
        }
        return $attributes;
    }

    public function loadItems(PDO $db): void {
        $stmt = $db->prepare("SELECT * FROM attribute_items WHERE attribute_id = :id");
        $stmt->execute(['id' => $this->id]);
        $rows = $stmt->fetchAll(PDO::FETCH_ASSOC);
        $this->items = array_map(fn($row) => new AttributeItem($row), $rows);
    }
}
