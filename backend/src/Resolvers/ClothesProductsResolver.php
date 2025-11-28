<?php

namespace App\Resolvers;

use App\Database\Connection;
use App\Model\ClothesProduct;

class ClothesProductsResolver
{
    public function clothesProducts(): array
    {
        $db = Connection::get();
        $clothesProducts = ClothesProduct::getClothesProducts($db);
        foreach ($clothesProducts as $p) {
            $p->loadRelations($db);
        }
        return $clothesProducts;
    }
}