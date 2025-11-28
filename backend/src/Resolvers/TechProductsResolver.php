<?php

namespace App\Resolvers;

use App\Database\Connection;
use App\Model\TechProduct;

class TechProductsResolver
{
    public function techProducts(): array
    {
        $db = Connection::get();
        $techProducts = TechProduct::getTechProducts($db);
        foreach ($techProducts as $p) {
            $p->loadRelations($db);
        }
        return $techProducts;
    }
}