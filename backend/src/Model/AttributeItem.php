<?php
namespace App\Model;

class AttributeItem {
    private int $id;
    private string $displayValue;
    private string $value;

    public function __construct(array $data) {
        $this->id = $data['id_attribute_items'];
        $this->displayValue = $data['displayValue'];
        $this->value = $data['value'];
    }

    public function getId():int { return $this->id;}
    public function getDisplayValue():string { return $this->displayValue;}
    public function getValue():string { return $this->value;}
}
