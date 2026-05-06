<?php
require_once 'config/db.php';
include 'includes/header.php';

$crops = $pdo->query("SELECT * FROM crops ORDER BY expected_harvest_date ASC")->fetchAll();
?>

<div class="d-flex justify-content-between align-items-center mb-4">
    <h2>Crops Management</h2>
    <button class="btn btn-primary-custom" data-bs-toggle="modal" data-bs-target="#cropModal">Add New Crop</button>
</div>

<div class="card-custom">
    <div class="table-responsive">
        <table class="table table-hover align-middle">
            <thead>
                <tr>
                    <th>Name</th>
                    <th>Variety</th>
                    <th>Plant Date</th>
                    <th>Expected Harvest</th>
                    <th>Status</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach($crops as $crop): ?>
                <tr>
                    <td><?= htmlspecialchars($crop['name']) ?></td>
                    <td><?= htmlspecialchars($crop['variety']) ?></td>
                    <td><?= htmlspecialchars($crop['plant_date']) ?></td>
                    <td><?= htmlspecialchars($crop['expected_harvest_date']) ?></td>
                    <td><span class="badge bg-success rounded-pill"><?= htmlspecialchars($crop['status']) ?></span></td>
                    <td>
                        <!-- Pass data via data attributes -->
                        <button class="btn btn-sm btn-outline-secondary edit-btn" 
                            data-id="<?= $crop['id'] ?>"
                            data-name="<?= htmlspecialchars($crop['name']) ?>"
                            data-variety="<?= htmlspecialchars($crop['variety']) ?>"
                            data-plant="<?= $crop['plant_date'] ?>"
                            data-harvest="<?= $crop['expected_harvest_date'] ?>"
                            data-status="<?= $crop['status'] ?>"
                            data-bs-toggle="modal" data-bs-target="#editCropModal">Edit</button>
                        <form action="actions/crop_action.php" method="POST" class="d-inline" onsubmit="return confirm('Delete this crop?');">
                            <input type="hidden" name="action" value="delete">
                            <input type="hidden" name="id" value="<?= $crop['id'] ?>">
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
<div class="modal fade" id="cropModal" tabindex="-1" aria-hidden="true">
  <div class="modal-dialog">
    <form action="actions/crop_action.php" method="POST" class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title">Add Crop</h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
      </div>
      <div class="modal-body">
        <input type="hidden" name="action" value="create">
        <div class="mb-3">
            <label class="form-label">Name</label>
            <input type="text" name="name" class="form-control" required>
        </div>
        <div class="mb-3">
            <label class="form-label">Variety</label>
            <input type="text" name="variety" class="form-control" required>
        </div>
        <div class="mb-3">
            <label class="form-label">Plant Date</label>
            <input type="date" name="plant_date" class="form-control" required>
        </div>
        <div class="mb-3">
            <label class="form-label">Expected Harvest</label>
            <input type="date" name="expected_harvest_date" class="form-control" required>
        </div>
        <div class="mb-3">
            <label class="form-label">Status</label>
            <select name="status" class="form-select">
                <option value="Planted">Planted</option>
                <option value="Growing">Growing</option>
                <option value="Harvested">Harvested</option>
            </select>
        </div>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
        <button type="submit" class="btn btn-primary-custom">Save Crop</button>
      </div>
    </form>
  </div>
</div>

<!-- Edit Modal -->
<div class="modal fade" id="editCropModal" tabindex="-1" aria-hidden="true">
  <div class="modal-dialog">
    <form action="actions/crop_action.php" method="POST" class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title">Edit Crop</h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
      </div>
      <div class="modal-body">
        <input type="hidden" name="action" value="update">
        <input type="hidden" name="id" id="edit-id">
        <div class="mb-3">
            <label class="form-label">Name</label>
            <input type="text" name="name" id="edit-name" class="form-control" required>
        </div>
        <div class="mb-3">
            <label class="form-label">Variety</label>
            <input type="text" name="variety" id="edit-variety" class="form-control" required>
        </div>
        <div class="mb-3">
            <label class="form-label">Plant Date</label>
            <input type="date" name="plant_date" id="edit-plant" class="form-control" required>
        </div>
        <div class="mb-3">
            <label class="form-label">Expected Harvest</label>
            <input type="date" name="expected_harvest_date" id="edit-harvest" class="form-control" required>
        </div>
        <div class="mb-3">
            <label class="form-label">Status</label>
            <select name="status" id="edit-status" class="form-select">
                <option value="Planted">Planted</option>
                <option value="Growing">Growing</option>
                <option value="Harvested">Harvested</option>
            </select>
        </div>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
        <button type="submit" class="btn btn-primary-custom">Update Crop</button>
      </div>
    </form>
  </div>
</div>

<script>
// Logic to populate edit modal
document.addEventListener('DOMContentLoaded', () => {
    document.querySelectorAll('.edit-btn').forEach(btn => {
        btn.addEventListener('click', (e) => {
            const target = e.currentTarget;
            document.getElementById('edit-id').value = target.getAttribute('data-id');
            document.getElementById('edit-name').value = target.getAttribute('data-name');
            document.getElementById('edit-variety').value = target.getAttribute('data-variety');
            document.getElementById('edit-plant').value = target.getAttribute('data-plant');
            document.getElementById('edit-harvest').value = target.getAttribute('data-harvest');
            document.getElementById('edit-status').value = target.getAttribute('data-status');
        });
    });
});
</script>

<?php include 'includes/footer.php'; ?>
