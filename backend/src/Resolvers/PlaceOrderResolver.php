<?php

namespace App\Resolvers;

use App\Database\Connection;
use App\Model\Order;

class PlaceOrderResolver
{
    public function placeOrder($root, $args)
    {
        try {
            $db = Connection::get();
            $order = Order::create($db, $args['items'], $args['total'], $args['currency_id']);
            return $order;
        } catch (\Exception $e) {
            error_log('Order creation failed: ' . $e->getMessage());
            throw new \Exception('Failed to create order.');
        }
    }
}