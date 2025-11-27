<?php
namespace App\Model;

use PDO;

class AttributeItem extends Attribute {
    private int $attr_item_id;
    private string $displayValue;
    private string $value;

    public function __construct(array $data) {
        parent::__construct($data);
        $this->attr_item_id = $data['id_attribute_items'];
        $this->displayValue = $data['displayValue'];
        $this->value = $data['value'];
    }

    public function getId():int { return $this->attr_item_id;}
    public function getDisplayValue():string { return $this->displayValue;}
    public function getValue():string { return $this->value;}
}
