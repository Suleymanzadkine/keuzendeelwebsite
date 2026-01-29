<?php
$id = $argv[1] ?? 1;
$path = __DIR__ . '/../database/database.sqlite';
$pdo = new PDO('sqlite:' . $path);
$stmt = $pdo->prepare('SELECT id,user_id,keuzedeel_id,status,created_at FROM inschrijvingen WHERE id = ?');
$stmt->execute([$id]);
$row = $stmt->fetch(PDO::FETCH_ASSOC);
if ($row) {
    echo json_encode($row, JSON_PRETTY_PRINT) . PHP_EOL;
} else {
    echo "NOT_FOUND" . PHP_EOL;
}
