<?php
require_once '../config/db.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $action = $_POST['action'] ?? '';

    if ($action === 'create') {
        $stmt = $pdo->prepare("INSERT INTO finances (type, amount, category, transaction_date, description) VALUES (?, ?, ?, ?, ?)");
        $stmt->execute([
            $_POST['type'],
            $_POST['amount'],
            $_POST['category'],
            $_POST['transaction_date'],
            $_POST['description']
        ]);
    } elseif ($action === 'update') {
        $stmt = $pdo->prepare("UPDATE finances SET type=?, amount=?, category=?, transaction_date=?, description=? WHERE id=?");
        $stmt->execute([
            $_POST['type'],
            $_POST['amount'],
            $_POST['category'],
            $_POST['transaction_date'],
            $_POST['description'],
            $_POST['id']
        ]);
    } elseif ($action === 'delete') {
        $stmt = $pdo->prepare("DELETE FROM finances WHERE id=?");
        $stmt->execute([$_POST['id']]);
    }

    header('Location: ../finances.php');
    exit;
}
