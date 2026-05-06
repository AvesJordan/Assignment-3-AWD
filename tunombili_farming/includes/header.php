<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Tunombili Farm Management</title>
    <!-- Bootstrap 5 CSS CDN -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <!-- Custom CSS -->
    <link href="assets/css/style.css" rel="stylesheet">
</head>
<body>

<!-- Sidebar -->
<aside class="sidebar">
    <div class="logo">Tunombili Farm</div>
    <nav>
        <ul class="sidebar-nav">
            <li><a href="index.php" <?= basename($_SERVER['PHP_SELF']) == 'index.php' ? 'class="active"' : '' ?>>Dashboard</a></li>
            <li><a href="crops.php" <?= basename($_SERVER['PHP_SELF']) == 'crops.php' ? 'class="active"' : '' ?>>Crops Management</a></li>
            <li><a href="inventory.php" <?= basename($_SERVER['PHP_SELF']) == 'inventory.php' ? 'class="active"' : '' ?>>Inventory</a></li>
            <li><a href="finances.php" <?= basename($_SERVER['PHP_SELF']) == 'finances.php' ? 'class="active"' : '' ?>>Finances</a></li>
        </ul>
    </nav>
</aside>

<!-- Main Content Area -->
<main class="main-content">
