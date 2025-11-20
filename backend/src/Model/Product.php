<?php
namespace App\Model;

use PDO;

abstract class Product {
    public string $id;
    public string $name;
    public bool $inStock;
    public string $description;
    public int $categoryId;
    public string $brand;
    public array $gallery = [];
    public array $attributes = [];
    public array $prices = [];
    public ?Category $category = null;

    public function __construct(array $data) {
        $this->id = $data['id_products'];
        $this->name = $data['name'];
        $this->inStock = (bool)$data['inStock'];
        $this->description = $data['description'];
        $this->categoryId = $data['category_id'];
        $this->brand = $data['brand'];
    }

    public static function getAll(PDO $db): array {
        $stmt = $db->query("SELECT * FROM products");
        $rows = $stmt->fetchAll(PDO::FETCH_ASSOC);
        // return array_map(fn($row) => new self($row), $rows);
        return array_map(fn($row) => new static($row), $rows);
    }

    public static function getById(PDO $db, string $id): ?self {
        $stmt = $db->prepare("SELECT * FROM products WHERE id_products = :id");
        $stmt->execute(['id' => $id]);
        $row = $stmt->fetch(PDO::FETCH_ASSOC);
        // return $row ? new self($row) : null;
        return $row ? new static($row) : null;
        // self always calls from this class, no overrides
        // static can be called from inherited classes
    }

    abstract public static function getCategoryId(PDO $db): int;

    public static function getByCategory(PDO $db): array {
        $catId = static::getCategoryId($db);
        $stmt = $db->prepare("SELECT * FROM products WHERE category_id = :id");
        $stmt->execute(['id' => $catId]);
        $rows = $stmt->fetchAll(PDO::FETCH_ASSOC);
        return array_map(fn($row) => new static($row), $rows);
    }

    public function loadRelations(PDO $db): void {
        $this->gallery = GalleryItem::getByProductId($db, $this->id);
        $this->prices = Price::getByProductId($db, $this->id);
        $this->category = Category::getById($db, $this->categoryId);
        $attributes = Attribute::getByProductId($db, $this->id);

        $this->attributes = [];

        foreach ($attributes as $a) {
            $stmtItems = $db->prepare("
                SELECT ai.displayValue, ai.value
                FROM product_items pi
                JOIN attribute_items ai ON pi.attribute_item_id = ai.id_attribute_items
                WHERE pi.product_id = :id AND ai.attribute_id = :attrId
            ");
            $stmtItems->execute([
                'id' => $this->id,
                'attrId' => $a->id
            ]);
            $items = $stmtItems->fetchAll(PDO::FETCH_ASSOC);

            $this->attributes[] = (object)[
                'name' => $a->name,
                'type' => $a->type,
                'items' => array_map(fn($it) => (object)[
                    'displayValue' => $it['displayValue'],
                    'value' => $it['value']
                ], $items)
            ];
        }
        /* error_log("Loading product: " . $this->id);
        error_log("Attributes for product " . $this->id . ": " . json_encode($attributes)); */

    }
}
