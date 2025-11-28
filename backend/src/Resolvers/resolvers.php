<?php

use App\Resolvers\ProductResolver;
use App\Resolvers\ProductsResolver;
use App\Resolvers\TechProductsResolver;
use App\Resolvers\ClothesProductsResolver;
use App\Resolvers\CategoriesResolver;
use App\Resolvers\PlaceOrderResolver;

return [
    'products' => [new ProductsResolver(), 'products'],
    'product' => [new ProductResolver(), 'product'],
    'techProducts' => [new TechProductsResolver(), 'techProducts'],
    'clothesProducts' => [new ClothesProductsResolver(), 'clothesProducts'],
    'categories' => [new CategoriesResolver(), 'categories'],

    'placeOrder' => [new PlaceOrderResolver(), 'placeOrder'],
];
