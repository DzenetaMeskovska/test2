<?php
namespace App\Model;

use PDO;

class GalleryItem {
    private int $id;
    private string $url;
    private string $productId;

    public function __construct(array $data) {
        $this->id = $data['id_gallery'];
        $this->url = $data['image_url'];
        $this->productId = $data['product_id'];
    }

    public function getId():int { return $this->id;}
    public function getUrl():string { return $this->url;}
    public function getProductId():string { return $this->productId;}

    public static function getByProductId(PDO $db, string $productId): array {
        $stmt = $db->prepare("SELECT * FROM gallery WHERE product_id = :id");
        $stmt->execute(['id' => $productId]);
        $rows = $stmt->fetchAll(PDO::FETCH_ASSOC);
        return array_map(fn($row) => new self($row), $rows);
    }
}
