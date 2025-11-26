<?php
namespace App\Model;

use PDO;

class Category {
    private int $id;
    private string $name;

    public function __construct(array $data) {
        $this->id = $data['id_categories'];
        $this->name = $data['name'];
    }

    public function getId():int { return $this->id;}
    public function getName():string { return $this->name;}

    public static function getAll(PDO $db): array {
        $stmt = $db->query("SELECT * FROM categories");
        $rows = $stmt->fetchAll(PDO::FETCH_ASSOC);
        return array_map(fn($row) => new self($row), $rows);
    }

    public static function getById(PDO $db, int $id): ?self { 
        $stmt = $db->prepare("SELECT * FROM categories WHERE id_categories = :id");
        $stmt->execute(['id' => $id]);
        $row = $stmt->fetch(PDO::FETCH_ASSOC);
        return $row ? new self($row) : null;
    }
}
