<?php
namespace App\Model;

use PDO;

class Currency extends Price{
    private int $id_curr;
    private string $label;
    private string $symbol;

    public function __construct(array $data) {
        parent::__construct($data);
        $this->id_curr = $data['id_currency'];
        $this->label = $data['label'];
        $this->symbol = $data['symbol'];
    }

    public function getId():int { return $this->id_curr;}
    public function getLabel():string { return $this->label;}
    public function getSymbol():string { return $this->symbol;}
}
