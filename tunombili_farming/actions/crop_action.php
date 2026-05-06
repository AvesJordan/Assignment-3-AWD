<?php
require_once '../config/db.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $action = $_POST['action'] ?? '';

    if ($action === 'create') {
        $stmt = $pdo->prepare("INSERT INTO crops (name, variety, plant_date, expected_harvest_date, status) VALUES (?, ?, ?, ?, ?)");
        $stmt->execute([
            $_POST['name'],
            $_POST['variety'],
            $_POST['plant_date'],
            $_POST['expected_harvest_date'],
            $_POST['status']
        ]);
    } elseif ($action === 'update') {
        $stmt = $pdo->prepare("UPDATE crops SET name=?, variety=?, plant_date=?, expected_harvest_date=?, status=? WHERE id=?");
        $stmt->execute([
            $_POST['name'],
            $_POST['variety'],
            $_POST['plant_date'],
            $_POST['expected_harvest_date'],
            $_POST['status'],
            $_POST['id']
        ]);
    } elseif ($action === 'delete') {
        $stmt = $pdo->prepare("DELETE FROM crops WHERE id=?");
        $stmt->execute([$_POST['id']]);
    }

    header('Location: ../crops.php');
    exit;
}
