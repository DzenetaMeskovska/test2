<?php
/*$servername = "localhost";
$username = "root";
$password = "option123";
$database = "scandiweb_store";

$conn = new mysqli($servername, $username, $password, $database);

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}*/
namespace App\Database;

use PDO;

class Connection {
    private static ?PDO $pdo = null;

    public static function get(): PDO {
        if (self::$pdo === null) {
            self::$pdo = new PDO(
                "mysql:host=localhost;dbname=scandiweb_store;charset=utf8",
                "root",
                "option123",
                [PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION]
            );
        }
        return self::$pdo;
    }
}

