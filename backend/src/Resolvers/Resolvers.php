<?php

use GraphQL\Type\Definition\ObjectType;
use GraphQL\Type\Definition\Type;
use GraphQL\Type\Definition\InputObjectType;
use App\Database\Connection;
use App\Model\Category;
use App\Model\Order;
use App\Model\TechProduct;
use App\Model\ClothesProduct;

return [

    'products' => function() {
        $db = Connection::get();
        $products = TechProduct::getAll($db);
        foreach ($products as $p) $p->loadRelations($db);
        //error_log("Fetched " . count($products) . " products");
        return $products;
    },
    'techProducts' => function() {
        $db = Connection::get();
        $techProducts = TechProduct::getTechProducts($db);
        foreach ($techProducts as $p) $p->loadRelations($db);
        return $techProducts;
    },
    'clothesProducts' => function() {
        $db = Connection::get();
        $clothesProducts = ClothesProduct::getClothesProducts($db);
        foreach ($clothesProducts as $p) $p->loadRelations($db);
        return $clothesProducts;
    },
    'product' => function ($root, $args) {
        $db = Connection::get();
        $product = TechProduct::getById($db, $args['id']);
        $product->loadRelations($db);
        return $product;
    },
    'categories' => function() {
        $db = Connection::get();
        $categories = Category::getAll($db);
        return $categories;
    },

    'placeOrder' => function ($root, $args) {
        try {
            $db = Connection::get();
            $order = Order::create($db, $args['items'], $args['total'], $args['currency_id']);
            return $order;
        } catch (\Exception $e) {
            error_log('Order creation failed: ' . $e->getMessage());
            throw new \Exception('Failed to create order.');
        }
    },
];
