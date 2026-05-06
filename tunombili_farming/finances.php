<?php
require_once 'config/db.php';
include 'includes/header.php';

$finances = $pdo->query("SELECT * FROM finances ORDER BY transaction_date DESC")->fetchAll();

$totalIncome = $pdo->query("SELECT SUM(amount) FROM finances WHERE type='Income'")->fetchColumn() ?: 0;
$totalExpense = $pdo->query("SELECT SUM(amount) FROM finances WHERE type='Expense'")->fetchColumn() ?: 0;
$net = $totalIncome - $totalExpense;
?>

<div class="d-flex justify-content-between align-items-center mb-4">
    <h2>Financial Log</h2>
    <button class="btn btn-primary-custom" data-bs-toggle="modal" data-bs-target="#financeModal">Add Record</button>
</div>

<div class="row mb-4">
    <div class="col-md-4">
        <div class="card-custom text-center">
            <h4>Total Income</h4>
            <h3 class="text-success">$<?= number_format($totalIncome, 2) ?></h3>
        </div>
    </div>
    <div class="col-md-4">
        <div class="card-custom text-center">
            <h4>Total Expenses</h4>
            <h3 class="text-danger">$<?= number_format($totalExpense, 2) ?></h3>
        </div>
    </div>
    <div class="col-md-4">
        <div class="card-custom text-center">
            <h4>Net Balance</h4>
            <h3 class="<?= $net >= 0 ? 'text-success' : 'text-danger' ?>">$<?= number_format($net, 2) ?></h3>
        </div>
    </div>
</div>

<div class="card-custom">
    <div class="table-responsive">
        <table class="table table-hover align-middle">
            <thead>
                <tr>
                    <th>Date</th>
                    <th>Type</th>
                    <th>Category</th>
                    <th>Description</th>
                    <th>Amount</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach($finances as $record): ?>
                <tr>
                    <td><?= htmlspecialchars($record['transaction_date']) ?></td>
                    <td>
                        <span class="badge <?= $record['type'] === 'Income' ? 'bg-success' : 'bg-danger' ?>">
                            <?= htmlspecialchars($record['type']) ?>
                        </span>
                    </td>
                    <td><?= htmlspecialchars($record['category']) ?></td>
                    <td><?= htmlspecialchars($record['description']) ?></td>
                    <td class="fw-bold <?= $record['type'] === 'Income' ? 'text-success' : 'text-danger' ?>">
                        $<?= number_format($record['amount'], 2) ?>
                    </td>
                    <td>
                        <button class="btn btn-sm btn-outline-secondary edit-btn" 
                            data-id="<?= $record['id'] ?>"
                            data-type="<?= htmlspecialchars($record['type']) ?>"
                            data-amount="<?= $record['amount'] ?>"
                            data-category="<?= htmlspecialchars($record['category']) ?>"
                            data-date="<?= $record['transaction_date'] ?>"
                            data-desc="<?= htmlspecialchars($record['description']) ?>"
                            data-bs-toggle="modal" data-bs-target="#editFinanceModal">Edit</button>
                        <form action="actions/finance_action.php" method="POST" class="d-inline" onsubmit="return confirm('Delete this record?');">
                            <input type="hidden" name="action" value="delete">
                            <input type="hidden" name="id" value="<?= $record['id'] ?>">
                            <button type="submit" class="btn btn-sm btn-outline-danger">Delete</button>
                        </form>
                    </td>
                </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    </div>
</div>

<!-- Add Modal -->
<div class="modal fade" id="financeModal" tabindex="-1" aria-hidden="true">
  <div class="modal-dialog">
    <form action="actions/finance_action.php" method="POST" class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title">Add Financial Record</h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
      </div>
      <div class="modal-body">
        <input type="hidden" name="action" value="create">
        <div class="mb-3">
            <label class="form-label">Type</label>
            <select name="type" class="form-select">
                <option value="Income">Income</option>
                <option value="Expense">Expense</option>
            </select>
        </div>
        <div class="mb-3">
            <label class="form-label">Amount</label>
            <input type="number" step="0.01" name="amount" class="form-control" required>
        </div>
        <div class="mb-3">
            <label class="form-label">Category</label>
            <input type="text" name="category" class="form-control" placeholder="e.g. Fertilizer, Crop Sale" required>
        </div>
        <div class="mb-3">
            <label class="form-label">Date</label>
            <input type="date" name="transaction_date" class="form-control" required>
        </div>
        <div class="mb-3">
            <label class="form-label">Description</label>
            <textarea name="description" class="form-control" rows="2"></textarea>
        </div>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
        <button type="submit" class="btn btn-primary-custom">Save Record</button>
      </div>
    </form>
  </div>
</div>

<!-- Edit Modal -->
<div class="modal fade" id="editFinanceModal" tabindex="-1" aria-hidden="true">
  <div class="modal-dialog">
    <form action="actions/finance_action.php" method="POST" class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title">Edit Financial Record</h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
      </div>
      <div class="modal-body">
        <input type="hidden" name="action" value="update">
        <input type="hidden" name="id" id="edit-fin-id">
        <div class="mb-3">
            <label class="form-label">Type</label>
            <select name="type" id="edit-fin-type" class="form-select">
                <option value="Income">Income</option>
                <option value="Expense">Expense</option>
            </select>
        </div>
        <div class="mb-3">
            <label class="form-label">Amount</label>
            <input type="number" step="0.01" name="amount" id="edit-fin-amount" class="form-control" required>
        </div>
        <div class="mb-3">
            <label class="form-label">Category</label>
            <input type="text" name="category" id="edit-fin-category" class="form-control" required>
        </div>
        <div class="mb-3">
            <label class="form-label">Date</label>
            <input type="date" name="transaction_date" id="edit-fin-date" class="form-control" required>
        </div>
        <div class="mb-3">
            <label class="form-label">Description</label>
            <textarea name="description" id="edit-fin-desc" class="form-control" rows="2"></textarea>
        </div>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
        <button type="submit" class="btn btn-primary-custom">Update Record</button>
      </div>
    </form>
  </div>
</div>

<script>
document.addEventListener('DOMContentLoaded', () => {
    document.querySelectorAll('#financeModal .edit-btn, .edit-btn[data-bs-target="#editFinanceModal"]').forEach(btn => {
        btn.addEventListener('click', (e) => {
            const target = e.currentTarget;
            document.getElementById('edit-fin-id').value = target.getAttribute('data-id');
            document.getElementById('edit-fin-type').value = target.getAttribute('data-type');
            document.getElementById('edit-fin-amount').value = target.getAttribute('data-amount');
            document.getElementById('edit-fin-category').value = target.getAttribute('data-category');
            document.getElementById('edit-fin-date').value = target.getAttribute('data-date');
            document.getElementById('edit-fin-desc').value = target.getAttribute('data-desc');
        });
    });
});
</script>

<?php include 'includes/footer.php'; ?>
