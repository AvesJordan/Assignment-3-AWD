<?php
require_once 'config/db.php';

// Fetch Summary Data
$cropsCount = $pdo->query("SELECT COUNT(*) FROM crops WHERE status IN ('Planted', 'Growing')")->fetchColumn();
$lowStockCount = $pdo->query("SELECT COUNT(*) FROM inventory WHERE quantity < 20")->fetchColumn();

// Calculate total income and expense
$income = $pdo->query("SELECT SUM(amount) FROM finances WHERE type='Income'")->fetchColumn() ?: 0;
$expense = $pdo->query("SELECT SUM(amount) FROM finances WHERE type='Expense'")->fetchColumn() ?: 0;

include 'includes/header.php';
?>

<div class="d-flex justify-content-between align-items-center mb-4">
    <h2>Dashboard</h2>
</div>

<div class="row">
    <div class="col-md-3">
        <div class="card-custom text-center">
            <h4>Active Crops</h4>
            <h2 class="text-success"><?= $cropsCount ?></h2>
        </div>
    </div>
    <div class="col-md-3">
        <div class="card-custom text-center">
            <h4>Low Stock Alerts</h4>
            <h2 class="text-danger"><?= $lowStockCount ?></h2>
        </div>
    </div>
    <div class="col-md-3">
        <div class="card-custom text-center">
            <h4>Total Income</h4>
            <h2 class="text-success">$<?= number_format($income, 2) ?></h2>
        </div>
    </div>
    <div class="col-md-3">
        <div class="card-custom text-center">
            <h4>Total Expense</h4>
            <h2 class="text-danger">$<?= number_format($expense, 2) ?></h2>
        </div>
    </div>
</div>

<div class="row mt-4">
    <div class="col-md-6">
        <div class="card-custom">
            <h4>Recent Finances</h4>
            <ul class="list-group list-group-flush">
                <?php
                $recentFinances = $pdo->query("SELECT * FROM finances ORDER BY transaction_date DESC LIMIT 5")->fetchAll();
                foreach($recentFinances as $f) {
                    $color = $f['type'] == 'Income' ? 'text-success' : 'text-danger';
                    echo "<li class='list-group-item d-flex justify-content-between align-items-center'>
                            {$f['category']}
                            <span class='$color fw-bold'>\${$f['amount']}</span>
                          </li>";
                }
                ?>
            </ul>
        </div>
    </div>
    <div class="col-md-6">
        <div class="card-custom">
            <h4>Crops Overview</h4>
            <ul class="list-group list-group-flush">
                <?php
                $recentCrops = $pdo->query("SELECT * FROM crops ORDER BY expected_harvest_date ASC LIMIT 5")->fetchAll();
                foreach($recentCrops as $c) {
                    echo "<li class='list-group-item d-flex justify-content-between align-items-center'>
                            {$c['name']} ({$c['variety']})
                            <span class='badge bg-success rounded-pill'>{$c['status']}</span>
                          </li>";
                }
                ?>
            </ul>
        </div>
    </div>
</div>

<?php include 'includes/footer.php'; ?>
