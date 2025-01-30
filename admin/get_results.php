<?php
include '../includes/db.php';

$stmt = $pdo->query("SELECT name, votes FROM candidates ORDER BY votes DESC");
$results = $stmt->fetchAll(PDO::FETCH_ASSOC);

header('Content-Type: application/json');
echo json_encode($results);
?>
