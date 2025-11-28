<?php

namespace App\Resolvers;

use App\Database\Connection;
use App\Model\TechProduct;

class ProductsResolver
{
    public function products(): array
    {
        $db = Connection::get();
        $products = TechProduct::getAll($db);

        foreach ($products as $p) {
            $p->loadRelations($db);
        }

        return $products;
    }
}
