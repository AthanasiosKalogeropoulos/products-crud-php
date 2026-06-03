<?php

require_once 'config/db.php';
require_once 'lib.php';

$products = new Products($pdo);
$allProducts = $products->getAll();

$success = $_GET['success'] ?? null;
$error   = $_GET['error'] ?? null;
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Products Management</title>
    <link rel="stylesheet" href="style.css">
</head>

<body>

    <!-- Top Bar -->
    <div class="topbar">
        <span class="topbar-logo">Products</span>
    </div>

    <div class="container">
        <div class="page-header">
            <div>
                <div class="page-title">
                    Product Management
                    <?php if (!empty($allProducts)): ?>
                        <span class="count-badge"><?= count($allProducts) ?></span>
                    <?php endif; ?>
                </div>
                <div class="page-subtitle">Manage your product catalog</div>
            </div>
            <button class="btn btn-primary" onclick="openAddModal()">
                + Add Product
            </button>
        </div>

        <!-- Table -->
        <?php if (empty($allProducts)): ?>
            <div class="table-card">
                <div class="empty-state">
                    <div class="empty-state-icon">📦</div>
                    <p>No products yet. Add your first product!</p>
                </div>
            </div>
        <?php else: ?>
            <div class="table-card">
                <table>
                    <thead>
                        <tr>
                            <th>#</th>
                            <th>Name</th>
                            <th>Price</th>
                            <th>Weight</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($allProducts as $product): ?>
                            <tr>
                                <td style="color:#d1d5db; font-size:13px;"><?= $product['id'] ?></td>
                                <td class="product-name"><?= htmlspecialchars($product['name']) ?></td>
                                <td class="price-badge">€<?= number_format($product['price'], 2) ?></td>
                                <td style="white-space: nowrap;"><?= number_format($product['weight'], 2) ?> kg</td>
                                <td>
                                    <div class="actions">
                                        <a href="edit.php?id=<?= $product['id'] ?>"
                                            class="btn btn-secondary btn-small">Edit</a>
                                        <button class="btn btn-danger btn-small"
                                            onclick="confirmDelete(<?= $product['id'] ?>, '<?= htmlspecialchars($product['name']) ?>')">
                                            Delete
                                        </button>
                                    </div>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            </div>
        <?php endif; ?>
    </div>

    <!-- Toast Notifications -->
    <?php if ($success === '1'): ?>
        <div class="toast toast-success" id="toast">✓ Product added successfully.</div>
    <?php elseif ($success === 'updated'): ?>
        <div class="toast toast-success" id="toast">✓ Product updated successfully.</div>
    <?php elseif ($success === 'deleted'): ?>
        <div class="toast toast-success" id="toast">✓ Product deleted.</div>
    <?php elseif ($error): ?>
        <div class="toast toast-error" id="toast">⚠ <?= htmlspecialchars(urldecode($error)) ?></div>
    <?php endif; ?>

    <!-- Add Modal -->
    <div class="modal-overlay" id="addModal">
        <div class="modal">
            <div class="modal-header">
                <h2>Add Product</h2>
                <button class="modal-close" onclick="closeAddModal()">✕</button>
            </div>
            <form method="POST" action="add.php">
                <div class="form-group">
                    <label for="name">Product Name *</label>
                    <input type="text" id="name" name="name"
                        placeholder="e.g. Apple MacBook Air" required>
                </div>
                <div class="form-group">
                    <label for="price">Price (€)</label>
                    <input type="number" id="price" name="price"
                        step="0.01" min="0" placeholder="0.00">
                </div>
                <div class="form-group">
                    <label for="weight">Weight (kg)</label>
                    <input type="number" id="weight" name="weight"
                        step="0.001" min="0" placeholder="0.000">
                </div>
                <div class="modal-actions">
                    <button type="button" class="btn btn-secondary"
                        onclick="closeAddModal()">Cancel</button>
                    <button type="submit" class="btn btn-primary">Add Product</button>
                </div>
            </form>
        </div>
    </div>

    <!-- Delete Confirm -->
    <div class="confirm-overlay" id="confirmOverlay">
        <div class="confirm-box">
            <div class="confirm-icon">🗑</div>
            <h3>Delete Product?</h3>
            <p id="confirmMsg">Are you sure you want to delete this product? This action cannot be undone.</p>
            <form method="POST" action="delete.php">
                <input type="hidden" name="id" id="deleteId">
                <div class="confirm-actions">
                    <button type="button" class="btn btn-secondary"
                        onclick="closeConfirm()">Cancel</button>
                    <button type="submit" class="btn btn-danger">Delete</button>
                </div>
            </form>
        </div>
    </div>

    <script>
        function openAddModal() {
            document.getElementById('addModal').classList.add('active');
        }

        function closeAddModal() {
            document.getElementById('addModal').classList.remove('active');
        }
        document.getElementById('addModal').addEventListener('click', function(e) {
            if (e.target === this) closeAddModal();
        });

        function confirmDelete(id, name) {
            document.getElementById('deleteId').value = id;
            document.getElementById('confirmMsg').textContent =
                `Are you sure you want to delete "${name}"? This action cannot be undone.`;
            document.getElementById('confirmOverlay').classList.add('active');
        }

        function closeConfirm() {
            document.getElementById('confirmOverlay').classList.remove('active');
        }
        document.getElementById('confirmOverlay').addEventListener('click', function(e) {
            if (e.target === this) closeConfirm();
        });

        // Auto-dismiss toast
        const toast = document.getElementById('toast');
        if (toast) {
            setTimeout(() => {
                toast.style.transition = 'all 0.4s ease';
                toast.style.transform = 'translateX(120%)';
                toast.style.opacity = '0';
                setTimeout(() => toast.remove(), 400);
            }, 3000);
        }
    </script>

</body>

</html>