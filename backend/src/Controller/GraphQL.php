<?php

namespace App\Controller;

use GraphQL\GraphQL as GraphQLBase;
use GraphQL\Type\Definition\ObjectType;
use GraphQL\Type\Definition\Type;
use GraphQL\Type\Definition\InputObjectType;
use GraphQL\Type\Schema;
use GraphQL\Type\SchemaConfig;
use GraphQL\Error\DebugFlag;
use RuntimeException;
use Throwable;

class GraphQL {
    static public function handle() {
        try {
            $productType = null;

            $currencyType = new ObjectType([
                'name' => 'Currency',
                'fields' => [
                    'label' => [
                        'type' => Type::string(),
                        'resolve' => fn($currency) => $currency->getLabel(),
                    ],
                    'symbol' => [
                        'type' => Type::string(),
                        'resolve' => fn($currency) => $currency->getSymbol(),
                    ],
                ],
            ]);

            $priceType = new ObjectType([
                'name' => 'Price',
                'fields' => [
                    'amount' => [
                        'type' => Type::float(),
                        'resolve' => fn($price) => $price->getAmount(),
                    ],
                    'currency' => [
                        'type' => $currencyType,
                        'resolve' => fn($price) => $price->getCurrency(),
                    ],
                ],
            ]);

            $galleryItemType = new ObjectType([
                'name' => 'GalleryItem',
                'fields' => [
                    'url' => [
                        'type' => Type::string(),
                        'resolve' => fn($gallery) => $gallery->getUrl(),
                    ],
                ],
            ]);

            $attributeItemType = new ObjectType([
                'name' => 'AttributeItem',
                'fields' => [
                    'displayValue' => [
                        'type' => Type::string(),
                        'resolve' => fn($attritem) => $attritem->displayValue,
                    ],
                    'value' => [
                        'type' => Type::string(),
                        'resolve' => fn($attritem) => $attritem->value,
                    ],
                ],
            ]);

            $attributeType = new ObjectType([
                'name' => 'Attribute',
                'fields' => [
                    'name' => [
                        'type' => Type::string(),
                        'resolve' => fn($attribute) => $attribute->name,
                    ],
                    'type' => [
                        'type' => Type::string(),
                        'resolve' => fn($attribute) => $attribute->type,
                    ],
                    'items' => [
                        'type' => Type::listOf($attributeItemType),
                        'resolve' => fn($attribute) => $attribute->items,
                    ],
                ],
            ]);

            $categoryType = new ObjectType([
                'name' => 'Category',
                'fields' => [
                    'id' => [
                        'type' => Type::id(),
                        'resolve' => fn($category) => $category->getId(),
                    ],
                    'name' => [
                        'type' => Type::string(),
                        'resolve' => fn($category) => $category->getName(),
                    ],
                ],
            ]);

            $productType = new ObjectType([
                'name' => 'Product',
                'fields' => function () use ($categoryType, $galleryItemType, $attributeType, $priceType){
                return [
                    'id' => [
                        'type' => Type::id(),
                        'resolve' => fn($product) => $product->getId(),
                    ],
                    'name' => [
                        'type' => Type::string(),
                        'resolve' => fn($product) => $product->getName(),
                    ],
                    'inStock' => [
                        'type' => Type::boolean(),
                        'resolve' => fn($product) => $product->isInStock(),
                    ],
                    'gallery' => [ 
                        'type' => Type::listOf($galleryItemType),
                        'resolve' => fn($product) => $product->getGallery(),
                    ],
                    'description' => [
                        'type' => Type::string(),
                        'resolve' => fn($product) => $product->getDescription(),
                    ],
                    'brand' => [
                        'type' => Type::string(),
                        'resolve' => fn($product) => $product->getBrand(),
                    ],
                    'attributes' => [
                        'type' => Type::listOf($attributeType),
                        'resolve' => fn($product) => $product->getAttributes(),
                    ],
                    'prices' => [
                        'type' => Type::listOf($priceType),
                        'resolve' => fn($product) => $product->getPrices(),
                    ],
                    'category' => [
                        'type' => $categoryType,
                        'resolve' => fn($product) => $product->getCategory(),
                    ],
                ];
                }
            ]);

            $orderItemType = new ObjectType([
                'name' => 'OrderItem',
                'fields' => [
                    'productId' => ['type' => Type::string()],
                    'price' => ['type' => Type::float()],
                    'quantity' => ['type' => Type::int()],
                    'attributes' => ['type' => Type::string()],
                ],
            ]);

            $orderType = new ObjectType([ //defines data u can fetch
                'name' => 'Order',
                'fields' => [
                    'id' => ['type' => Type::int()],
                    'total' => ['type' => Type::float()],
                    'currency_id' => ['type' => Type::int()],
                    'items' => ['type' => Type::listOf($orderItemType)],
                ],
            ]);

            $orderItemInputType = new InputObjectType([ //defines data u can send in mutations
            'name' => 'OrderItemInput',
            'fields' => [
                'productId' => ['type' => Type::string()],
                'price' => ['type' => Type::float()],
                'quantity' => ['type' => Type::int()],
                'attributes' => ['type' => Type::string()],
            ],
            ]);
                        

            /*$queryType = new ObjectType([
                'name' => 'Query',
                'fields' => [
                    'echo' => [
                        'type' => Type::string(),
                        'args' => [
                            'message' => ['type' => Type::string()],
                        ],
                        'resolve' => static fn ($rootValue, array $args): string => $rootValue['prefix'] . $args['message'],
                    ],
                ],
            ]);*/
        
            $mutationType = new ObjectType([
                'name' => 'Mutation',
                'fields' => [
                    /*'sum' => [
                        'type' => Type::int(),
                        'args' => [
                            'x' => ['type' => Type::int()],
                            'y' => ['type' => Type::int()],
                        ],
                        'resolve' => static fn ($calc, array $args): int => $args['x'] + $args['y'],
                    ],*/
                    'placeOrder' => [
                        'type' => $orderType,
                        'args' => [
                            'items' => ['type' => Type::listOf($orderItemInputType)],
                            'total' => ['type' => Type::float()],
                            'currency_id' => ['type' => Type::int()],
                        ],
                        'resolve' => function ($root, $args) {
                            try {
                            $db = \App\Database\Connection::get();
                            $order = \App\Model\Order::create($db, $args['items'], $args['total'], $args['currency_id']);
                            return $order;
                            } catch (\Exception $e) {
                                error_log('Order creation failed: ' . $e->getMessage());
                                throw new \Exception('Failed to create order.');
                            }
                        }
                    ],
                ],
            ]);

            $queryType = new \GraphQL\Type\Definition\ObjectType([
            'name' => 'Query',
            'fields' => [
                'products' => [
                    'type' => \GraphQL\Type\Definition\Type::listOf($productType),
                    'resolve' => function() {
                        $db = \App\Database\Connection::get();
                        $products = \App\Model\TechProduct::getAll($db);
                        foreach ($products as $p) $p->loadRelations($db);
                        //error_log("Fetched " . count($products) . " products");
                        return $products;
                    }
                ],
                'techProducts' => [
                    'type' => \GraphQL\Type\Definition\Type::listOf($productType),
                    'resolve' => function() {
                        $db = \App\Database\Connection::get();
                        $techProducts = \App\Model\TechProduct::getTechProducts($db);
                        foreach ($techProducts as $p) $p->loadRelations($db);
                        return $techProducts;
                    }
                ],
                'clothesProducts' => [
                    'type' => \GraphQL\Type\Definition\Type::listOf($productType),
                    'resolve' => function() {
                        $db = \App\Database\Connection::get();
                        $clothesProducts = \App\Model\ClothesProduct::getClothesProducts($db);
                        foreach ($clothesProducts as $p) $p->loadRelations($db);
                        return $clothesProducts;
                    }
                ],
                'product' => [
                    'type' => $productType,
                    'args' => [
                        'id' => Type::nonNull(Type::id())
                    ],
                    'resolve' => function ($root, $args) {
                        $db = \App\Database\Connection::get();
                        $product = \App\Model\TechProduct::getById($db, $args['id']);
                        $product->loadRelations($db);
                        return $product;
                    }
                ],
                'categories' => [
                    'type' => \GraphQL\Type\Definition\Type::listOf($categoryType),
                    'resolve' => function() {
                        $db = \App\Database\Connection::get();
                        $categories = \App\Model\Category::getAll($db);
                        // foreach ($categories as $c) $c->loadProducts($db);
                        return $categories;
                    }
                ]
            ]
            ]);
        
            // See docs on schema options:
            // https://webonyx.github.io/graphql-php/schema-definition/#configuration-options
            $schema = new Schema(
                (new SchemaConfig())
                ->setQuery($queryType)
                ->setMutation($mutationType)
            );
        
            $rawInput = file_get_contents('php://input');
            if ($rawInput === false) {
                throw new RuntimeException('Failed to get php://input');
            }
        
            $input = json_decode($rawInput, true);
            $query = $input['query'];
            $variableValues = $input['variables'] ?? null;
        
            $rootValue = ['prefix' => 'You said: '];
            $result = GraphQLBase::executeQuery($schema, $query, $rootValue, null, $variableValues);
            $output = $result->toArray(DebugFlag::INCLUDE_DEBUG_MESSAGE | DebugFlag::INCLUDE_TRACE);
        } catch (Throwable $e) {
            error_log("GraphQL Exception: " . $e->getMessage() . "\n" . $e->getTraceAsString());
            $output = [
                'error' => [
                    'message' => $e->getMessage(),
                ],
            ];
        }

        header('Content-Type: application/json; charset=UTF-8');
        return json_encode($output);
    }
}