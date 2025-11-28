<?php

namespace App\Resolvers;

use App\Database\Connection;
use App\Model\TechProduct;

class ProductResolver
{
    public function product($root, $args)
    {
        $db = Connection::get();
        $product = TechProduct::getById($db, $args['id']);
        $product->loadRelations($db);
        return $product;
    }
}