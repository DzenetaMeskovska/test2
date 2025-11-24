<?php
namespace App\Model;

use PDO;

class Currency {
    private int $id;
    private string $label;
    private string $symbol;

    public function __construct(array $data) {
        $this->id = $data['id_currency'];
        $this->label = $data['label'];
        $this->symbol = $data['symbol'];
    }

    public function getId():int { return $this->id;}
    public function getLabel():string { return $this->label;}
    public function getSymbol():string { return $this->symbol;}

    public static function getById(PDO $db, int $id): ?self {
        $stmt = $db->prepare("SELECT * FROM currency WHERE id_currency = :id");
        $stmt->execute(['id' => $id]);
        $row = $stmt->fetch(PDO::FETCH_ASSOC);
        return $row ? new self($row) : null;
    }
}
