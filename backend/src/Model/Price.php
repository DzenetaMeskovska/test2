<?php
namespace App\Model;

use PDO;

class Price {
    public int $id;
    public float $amount;
    public int $currencyId;
    public string $productId;
    public ?Currency $currency = null;

    public function __construct(array $data) {
        $this->id = $data['id_prices'];
        $this->amount = (float)$data['amount'];
        $this->currencyId = $data['currency_id'];
        $this->productId = $data['product_id'];
    }

    public static function getByProductId(PDO $db, string $productId): array {
        $stmt = $db->prepare("SELECT * FROM prices WHERE product_id = :pid");
        $stmt->execute(['pid' => $productId]);
        $rows = $stmt->fetchAll(PDO::FETCH_ASSOC);
        $prices = array_map(fn($row) => new self($row), $rows);
        foreach ($prices as $price) {
            $price->currency = Currency::getById($db, $price->currencyId);
        }
        return $prices;
    }
}
