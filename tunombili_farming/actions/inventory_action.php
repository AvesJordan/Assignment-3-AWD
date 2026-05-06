<?php
require_once '../config/db.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $action = $_POST['action'] ?? '';

    if ($action === 'create') {
        $stmt = $pdo->prepare("INSERT INTO inventory (item_name, type, quantity, unit, supplier) VALUES (?, ?, ?, ?, ?)");
        $stmt->execute([
            $_POST['item_name'],
            $_POST['type'],
            $_POST['quantity'],
            $_POST['unit'],
            $_POST['supplier']
        ]);
    } elseif ($action === 'update') {
        $stmt = $pdo->prepare("UPDATE inventory SET item_name=?, type=?, quantity=?, unit=?, supplier=? WHERE id=?");
        $stmt->execute([
            $_POST['item_name'],
            $_POST['type'],
            $_POST['quantity'],
            $_POST['unit'],
            $_POST['supplier'],
            $_POST['id']
        ]);
    } elseif ($action === 'delete') {
        $stmt = $pdo->prepare("DELETE FROM inventory WHERE id=?");
        $stmt->execute([$_POST['id']]);
    }

    header('Location: ../inventory.php');
    exit;
}
