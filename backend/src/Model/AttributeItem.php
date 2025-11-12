<?php
namespace App\Model;

class AttributeItem {
    public int $id;
    public string $displayValue;
    public string $value;

    public function __construct(array $data) {
        $this->id = $data['id_attribute_items'];
        $this->displayValue = $data['displayValue'];
        $this->value = $data['value'];
    }
}
