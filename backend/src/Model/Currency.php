<?php
namespace App\Model;

use PDO;

class Currency {
    public int $id;
    public string $label;
    public string $symbol;

    public function __construct(array $data) {
        $this->id = $data['id_currency'];
        $this->label = $data['label'];
        $this->symbol = $data['symbol'];
    }

    public static function getById(PDO $db, int $id): ?self {
        $stmt = $db->prepare("SELECT * FROM currency WHERE id_currency = :id");
        $stmt->execute(['id' => $id]);
        $row = $stmt->fetch(PDO::FETCH_ASSOC);
        return $row ? new self($row) : null;
    }
}
