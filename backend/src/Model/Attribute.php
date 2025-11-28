<?php
namespace App\Model;

use PDO;

class Attribute {
    private int $id;
    private string $name;
    private string $type;
    private array $items = [];

    public function __construct(array $data) {
        $this->id = $data['id_attributes'];
        $this->name = $data['name'];
        $this->type = $data['type'];
    }

    public function getId():int { return $this->id;}
    public function getName():string { return $this->name;}
    public function getType():string { return $this->type;}
    public function getItems():array { return $this->items;}

    public static function getByProductId(PDO $db, string $productId): array {
        $stmt = $db->prepare("
            SELECT a.* FROM attributes a
            JOIN product_attributes pa ON pa.attribute_id = a.id_attributes
            WHERE pa.product_id = :pid
        ");
        $stmt->execute(['pid' => $productId]);
        $rows = $stmt->fetchAll(PDO::FETCH_ASSOC);
        $attributes = array_map(fn($row) => new self($row), $rows);

        foreach ($attributes as $attr){
            $stmt2 = $db->prepare("
                SELECT ai.*
                FROM product_items pi
                JOIN attribute_items ai ON pi.attribute_item_id = ai.id_attribute_items
                WHERE pi.product_id = :id AND ai.attribute_id = :attrId");
            $stmt2->execute(['id' => $productId, 'attrId' => $attr->id]);
            $rowsItems = $stmt2->fetchAll(PDO::FETCH_ASSOC);
            $items = [];
            foreach ($rowsItems as $r){
                $items[] = new AttributeItem(array_merge([
                    'id_attributes' => $attr->id,
                    'name' => $attr->name,
                    'type' => $attr->type
                ], $r));
            }
            $attr->items = $items;
        }
        return $attributes;
    }
}
