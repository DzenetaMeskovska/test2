<?php
namespace App\Model;

use PDO;

abstract class Product {
    protected string $id;
    protected string $name;
    protected bool $inStock;
    protected string $description;
    protected int $categoryId;
    protected string $brand;
    protected array $gallery = [];
    protected array $attributes = [];
    protected array $prices = [];
    protected ?Category $category = null;

    public function __construct(array $data) {
        $this->id = $data['id_products'];
        $this->name = $data['name'];
        $this->inStock = (bool)$data['inStock'];
        $this->description = $data['description'];
        $this->categoryId = $data['category_id'];
        $this->brand = $data['brand'];
    }

    public function getId():string { return $this->id;}
    public function getName():string { return $this->name;}
    public function isInStock():bool { return $this->inStock;}
    public function getDescription():string { return $this->description;}
    public function getCategoryId():int { return $this->categoryId;}
    public function getBrand():string { return $this->brand;}
    public function getGallery():array { return $this->gallery;}
    public function getAttributes():array { return $this->attributes;}
    public function getPrices():array { return $this->prices;}
    public function getCategory():Category { return $this->category;}

    public static function getAll(PDO $db): array {
        $stmt = $db->query("SELECT * FROM products");
        $rows = $stmt->fetchAll(PDO::FETCH_ASSOC);
        return array_map(fn($row) => new static($row), $rows);
    }

    public static function getById(PDO $db, string $id): ?self {
        $stmt = $db->prepare("SELECT * FROM products WHERE id_products = :id");
        $stmt->execute(['id' => $id]);
        $row = $stmt->fetch(PDO::FETCH_ASSOC);
        return $row ? new static($row) : null;
    }

    abstract public static function getCategoryIdByName(PDO $db): int;

    public static function getByCategory(PDO $db): array {
        $catId = static::getCategoryIdByName($db);
        $stmt = $db->prepare("SELECT * FROM products WHERE category_id = :id");
        $stmt->execute(['id' => $catId]);
        $rows = $stmt->fetchAll(PDO::FETCH_ASSOC);
        return array_map(fn($row) => new static($row), $rows);
    }

    abstract public function getProductAttributes(PDO $db): array;

    public function loadRelations(PDO $db): void {
        $this->gallery = GalleryItem::getByProductId($db, $this->id);
        $this->prices = Price::getByProductId($db, $this->id);
        $this->category = Category::getById($db, $this->categoryId);
        $this->attributes = $this->getProductAttributes($db);
    }
}
