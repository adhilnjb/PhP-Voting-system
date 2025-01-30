<?php
try {
    $host = "localhost";
    $dbname = "voting";
    $user = "admin";
    $password = "admin1";

    $dsn = "mysql:host=$host;dbname=$dbname;charset=utf8mb4";
    $pdo = new PDO($dsn, $user, $password);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

} catch(PDOException $e) {
    echo "Database Connection Error: " . $e->getMessage();
    exit(); 
}

return $pdo; 
?>