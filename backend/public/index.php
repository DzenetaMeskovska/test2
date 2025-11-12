<?php
ini_set('display_errors', 1);
error_reporting(E_ALL);

require __DIR__ . '/../vendor/autoload.php';

//use FastRoute\RouteCollector;
//use FastRoute\Dispatcher;
use App\Controller\GraphQL;
//header('Content-Type: application/json');

echo GraphQL::handle();

/*$dispatcher = FastRoute\simpleDispatcher(function(RouteCollector $r) {
    $r->post('/graphql', [GraphQL::class, 'handle']);
});

$routeInfo = $dispatcher->dispatch(
    $_SERVER['REQUEST_METHOD'],
    parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH)
);

switch ($routeInfo[0]) {
    case Dispatcher::NOT_FOUND:
        http_response_code(404);
        echo json_encode(['error' => 'Not Found']);
        break;

    case Dispatcher::METHOD_NOT_ALLOWED:
        http_response_code(405);
        echo json_encode(['error' => 'Method Not Allowed']);
        break;

    case Dispatcher::FOUND:
        $handler = $routeInfo[1];
        $vars = $routeInfo[2];
        echo call_user_func($handler, $vars);
        break;
}*/

/*include 'config.php';

$sql = "SELECT 
            p.id_products,
            p.name AS product_name,
            p.description,
            p.inStock,
            p.brand,
            c.name AS category_name,
            pr.amount,
            cu.label AS currency_label,
            cu.symbol AS currency_symbol
        FROM products p
        LEFT JOIN categories c ON p.category_id = c.id_categories
        LEFT JOIN prices pr ON p.id_products = pr.product_id
        LEFT JOIN currency cu ON pr.currency_id = cu.id_currency";

$result = $conn->query($sql);
?>

<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <title>Product Catalog</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            background: #f5f5f5;
            margin: 0;
            padding: 20px;
        }
        .product {
            background: white;
            border-radius: 10px;
            box-shadow: 0 0 10px rgba(0,0,0,0.1);
            padding: 15px;
            margin-bottom: 20px;
        }
        .product h2 {
            margin-top: 0;
        }
        .price {
            font-weight: bold;
            color: green;
        }
        .category {
            color: #666;
            font-size: 0.9em;
        }
        .gallery img {
            max-width: 150px; 
            margin: 5px;
        }
        .attributes { 
            margin-top: 10px; 
        }
        .attribute { 
            display: inline-block; 
            background: #eee; 
            padding: 3px 7px; 
            border-radius: 5px; 
            margin-right: 5px; 
            font-size: 0.85em; 
        }
    </style>
</head>
<body>

<h1>Product Catalog</h1>

<?php
if ($result->num_rows > 0) {
    while ($row = $result->fetch_assoc()) {
        echo "<div class='product'>";
        echo "<h2>" . htmlspecialchars($row['product_name']) . "</h2>";
        echo "<p class='category'>Category: " . htmlspecialchars($row['category_name']) . "</p>";
        echo "<p>Brand: " . htmlspecialchars($row['brand']) . "</p>";
        echo "<p>" . $row['description'] . "</p>";
        echo "<p class='price'>Price: " . $row['currency_symbol'] . number_format($row['amount'], 2) . " " . $row['currency_label'] . "</p>";
        echo "<p>Status: " . ($row['inStock'] ? "✅ In Stock" : "❌ Out of Stock") . "</p>";
        $gallery_sql = "SELECT image_url FROM gallery WHERE product_id = '" . $conn->real_escape_string($row['id_products']) . "'";
        $gallery_result = $conn->query($gallery_sql);
        if ($gallery_result->num_rows > 0) {
            echo "<div class='gallery'>";
            while ($img = $gallery_result->fetch_assoc()) {
                echo "<img src='" . htmlspecialchars($img['image_url']) . "' alt='Product image'>";
            }
            echo "</div>";
        }
        
        $attr_sql = "
        SELECT a.name AS attribute_name, ai.displayValue
        FROM product_attributes pa
        JOIN attributes a ON pa.attribute_id = a.id_attributes
        JOIN attribute_items ai ON ai.attribute_id = a.id_attributes
        WHERE pa.product_id = '" . $conn->real_escape_string($row['id_products']) . "'
        ORDER BY a.name, ai.displayValue";

        $attr_result = $conn->query($attr_sql);

        if ($attr_result->num_rows > 0) {
            $attributes = [];
            while ($attr = $attr_result->fetch_assoc()) {
                $attributes[$attr['attribute_name']][] = $attr['displayValue'];
            }

            echo "<div class='attributes'><strong>Attributes:</strong><br>";
            foreach ($attributes as $name => $values) {
                echo "<strong>" . htmlspecialchars($name) . ":</strong> " . htmlspecialchars(implode(", ", $values)) . "<br>";
            }
            echo "</div>";
        }
        echo "</div>";
    }
} else {
    echo "<p>No products found.</p>";
}

$conn->close();
?>

</body>
</html>*/
