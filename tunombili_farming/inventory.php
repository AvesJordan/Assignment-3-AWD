<?php
require_once 'config/db.php';
include 'includes/header.php';

$inventory = $pdo->query("SELECT * FROM inventory ORDER BY item_name ASC")->fetchAll();
?>

<div class="d-flex justify-content-between align-items-center mb-4">
    <h2>Inventory Tracking</h2>
    <button class="btn btn-primary-custom" data-bs-toggle="modal" data-bs-target="#inventoryModal">Add New Item</button>
</div>

<div class="card-custom">
    <div class="table-responsive">
        <table class="table table-hover align-middle">
            <thead>
                <tr>
                    <th>Item Name</th>
                    <th>Type</th>
                    <th>Quantity</th>
                    <th>Unit</th>
                    <th>Supplier</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach($inventory as $item): ?>
                <?php $isLowStock = $item['quantity'] < 20; ?>
                <tr class="<?= $isLowStock ? 'table-danger' : '' ?>">
                    <td>
                        <?= htmlspecialchars($item['item_name']) ?>
                        <?php if($isLowStock): ?>
                            <span class="badge bg-danger rounded-pill ms-2">Low Stock</span>
                        <?php endif; ?>
                    </td>
                    <td><?= htmlspecialchars($item['type']) ?></td>
                    <td><?= htmlspecialchars($item['quantity']) ?></td>
                    <td><?= htmlspecialchars($item['unit']) ?></td>
                    <td><?= htmlspecialchars($item['supplier']) ?></td>
                    <td>
                        <button class="btn btn-sm btn-outline-secondary edit-btn" 
                            data-id="<?= $item['id'] ?>"
                            data-name="<?= htmlspecialchars($item['item_name']) ?>"
                            data-type="<?= htmlspecialchars($item['type']) ?>"
                            data-qty="<?= $item['quantity'] ?>"
                            data-unit="<?= htmlspecialchars($item['unit']) ?>"
                            data-supplier="<?= htmlspecialchars($item['supplier']) ?>"
                            data-bs-toggle="modal" data-bs-target="#editInventoryModal">Edit</button>
                        <form action="actions/inventory_action.php" method="POST" class="d-inline" onsubmit="return confirm('Delete this item?');">
                            <input type="hidden" name="action" value="delete">
                            <input type="hidden" name="id" value="<?= $item['id'] ?>">
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
<div class="modal fade" id="inventoryModal" tabindex="-1" aria-hidden="true">
  <div class="modal-dialog">
    <form action="actions/inventory_action.php" method="POST" class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title">Add Inventory Item</h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
      </div>
      <div class="modal-body">
        <input type="hidden" name="action" value="create">
        <div class="mb-3">
            <label class="form-label">Item Name</label>
            <input type="text" name="item_name" class="form-control" required>
        </div>
        <div class="mb-3">
            <label class="form-label">Type</label>
            <select name="type" class="form-select">
                <option value="Seeds">Seeds</option>
                <option value="Fertilizer">Fertilizer</option>
                <option value="Tools">Tools</option>
            </select>
        </div>
        <div class="mb-3">
            <label class="form-label">Quantity</label>
            <input type="number" step="0.01" name="quantity" class="form-control" required>
        </div>
        <div class="mb-3">
            <label class="form-label">Unit</label>
            <input type="text" name="unit" class="form-control" placeholder="e.g., Kgs, Bags, Pieces" required>
        </div>
        <div class="mb-3">
            <label class="form-label">Supplier</label>
            <input type="text" name="supplier" class="form-control">
        </div>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
        <button type="submit" class="btn btn-primary-custom">Save Item</button>
      </div>
    </form>
  </div>
</div>

<!-- Edit Modal -->
<div class="modal fade" id="editInventoryModal" tabindex="-1" aria-hidden="true">
  <div class="modal-dialog">
    <form action="actions/inventory_action.php" method="POST" class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title">Edit Inventory Item</h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
      </div>
      <div class="modal-body">
        <input type="hidden" name="action" value="update">
        <input type="hidden" name="id" id="edit-inv-id">
        <div class="mb-3">
            <label class="form-label">Item Name</label>
            <input type="text" name="item_name" id="edit-inv-name" class="form-control" required>
        </div>
        <div class="mb-3">
            <label class="form-label">Type</label>
            <select name="type" id="edit-inv-type" class="form-select">
                <option value="Seeds">Seeds</option>
                <option value="Fertilizer">Fertilizer</option>
                <option value="Tools">Tools</option>
            </select>
        </div>
        <div class="mb-3">
            <label class="form-label">Quantity</label>
            <input type="number" step="0.01" name="quantity" id="edit-inv-qty" class="form-control" required>
        </div>
        <div class="mb-3">
            <label class="form-label">Unit</label>
            <input type="text" name="unit" id="edit-inv-unit" class="form-control" required>
        </div>
        <div class="mb-3">
            <label class="form-label">Supplier</label>
            <input type="text" name="supplier" id="edit-inv-supplier" class="form-control">
        </div>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
        <button type="submit" class="btn btn-primary-custom">Update Item</button>
      </div>
    </form>
  </div>
</div>

<script>
document.addEventListener('DOMContentLoaded', () => {
    document.querySelectorAll('#inventoryModal .edit-btn, .edit-btn[data-bs-target="#editInventoryModal"]').forEach(btn => {
        btn.addEventListener('click', (e) => {
            const target = e.currentTarget;
            document.getElementById('edit-inv-id').value = target.getAttribute('data-id');
            document.getElementById('edit-inv-name').value = target.getAttribute('data-name');
            document.getElementById('edit-inv-type').value = target.getAttribute('data-type');
            document.getElementById('edit-inv-qty').value = target.getAttribute('data-qty');
            document.getElementById('edit-inv-unit').value = target.getAttribute('data-unit');
            document.getElementById('edit-inv-supplier').value = target.getAttribute('data-supplier');
        });
    });
});
</script>

<?php include 'includes/footer.php'; ?>
