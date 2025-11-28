<?php
namespace App\Model;

use PDO;

class Order {
    private int $id;
    private float $total;
    private int $currency_id;
    private array $items = [];

    public function __construct(array $data) {
        $this->id = $data['id_order'];
        $this->total = $data['total'];
        $this->currency_id = $data['currency_id'];
    }

    public function getId() { return $this->id; }
    public function getTotal() { return $this->total; }
    public function getCurrencyId() { return $this->currency_id; }
    public function getItems() { return $this->items; }

    public static function create(PDO $db, array $items, float $total, int $currency_id): self {
        $stmt = $db->prepare("INSERT INTO orders (total, currency_id) VALUES (:total, :currency_id)");
        $stmt->execute([':total' => $total, ':currency_id' => $currency_id]);
        $orderId = (int)$db->lastInsertId();

        $stmtItem = $db->prepare("
            INSERT INTO order_items (order_id, product_id, price, quantity, selected_attributes)
            VALUES (:order_id, :product_id, :price, :quantity, :selected_attributes)
        ");
        foreach ($items as $item) {
            $stmtItem->execute([
                ':order_id' => $orderId,
                ':product_id' => $item['productId'],
                ':price' => $item['price'],
                ':quantity' => $item['quantity'],
                ':selected_attributes' => json_encode($item['attributes']),
            ]);
        }

        $order = new self([
            'id_order' => $orderId,
            'total' => $total,
            'currency_id' => $currency_id
        ]);
        $order->items = $items;
        return $order;
    }
}

