<?php
require_once("./lib.php");

$error = '';
$success = '';
$form_data = [];

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $form_data = $_POST;
    if (empty(trim($_POST['name']))) {
        $error = 'Το πεδίο Name είναι υποχρεωτικό.';
    } else {
        $productsList = new Products("./products.xml");
        $productsList->add_product($_POST);
        header('Location: products.php?success=1');
        exit;
    }
}

if (isset($_GET['success']) && $_GET['success'] === '1') {
    $success = 'Το προϊόν προστέθηκε επιτυχώς!';
}

$productsList = new Products("./products.xml");
?>
<!DOCTYPE html>
<html lang="el">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Products</title>
    <link href="https://fonts.googleapis.com/css2?family=DM+Sans:wght@300;400;500;600&family=DM+Mono:wght@400;500&display=swap" rel="stylesheet">
    <style>
        *,
        *::before,
        *::after {
            box-sizing: border-box;
            margin: 0;
            padding: 0;
        }

        :root {
            --bg: #f7f7f5;
            --surface: #ffffff;
            --border: #e8e8e4;
            --text-primary: #1a1a1a;
            --text-secondary: #6b6b6b;
            --accent: #2563eb;
            --accent-hover: #1d4ed8;
            --danger: #dc2626;
            --success: #16a34a;
            --shadow: 0 1px 3px rgba(0, 0, 0, 0.06), 0 4px 16px rgba(0, 0, 0, 0.06);
            --shadow-lg: 0 8px 32px rgba(0, 0, 0, 0.12);
            --radius: 10px;
        }

        body {
            font-family: 'DM Sans', sans-serif;
            background: var(--bg);
            color: var(--text-primary);
            min-height: 100vh;
            padding: 48px 32px;
        }

        .container {
            max-width: 1200px;
            margin: 0 auto;
        }

        /* Header */
        .header {
            display: flex;
            align-items: center;
            justify-content: space-between;
            margin-bottom: 32px;
        }

        .header-left h1 {
            font-size: 24px;
            font-weight: 600;
            letter-spacing: -0.4px;
        }

        .header-left p {
            font-size: 14px;
            color: var(--text-secondary);
            margin-top: 4px;
        }

        /* Button */
        .btn {
            display: inline-flex;
            align-items: center;
            gap: 8px;
            padding: 10px 20px;
            border-radius: var(--radius);
            font-family: 'DM Sans', sans-serif;
            font-size: 14px;
            font-weight: 500;
            cursor: pointer;
            transition: all 0.15s ease;
            border: none;
        }

        .btn-primary {
            background: var(--accent);
            color: white;
        }

        .btn-primary:hover {
            background: var(--accent-hover);
            transform: translateY(-1px);
            box-shadow: 0 4px 12px rgba(37, 99, 235, 0.3);
        }

        .btn-ghost {
            background: transparent;
            color: var(--text-secondary);
            border: 1px solid var(--border);
        }

        .btn-ghost:hover {
            background: var(--bg);
            color: var(--text-primary);
        }

        /* Alerts */
        .alert {
            padding: 12px 16px;
            border-radius: var(--radius);
            font-size: 14px;
            margin-bottom: 24px;
            display: flex;
            align-items: center;
            gap: 8px;
        }

        .alert-success {
            background: #f0fdf4;
            color: var(--success);
            border: 1px solid #bbf7d0;
        }

        .alert-error {
            background: #fef2f2;
            color: var(--danger);
            border: 1px solid #fecaca;
        }

        /* Table Card */
        .card {
            background: var(--surface);
            border-radius: var(--radius);
            box-shadow: var(--shadow);
            overflow: hidden;
            border: 1px solid var(--border);
        }

        table {
            width: 100%;
            border-collapse: collapse;
        }

        thead {
            background: #fafaf9;
            border-bottom: 1px solid var(--border);
        }

        th {
            padding: 12px 16px;
            text-align: left;
            font-size: 11px;
            font-weight: 600;
            text-transform: uppercase;
            letter-spacing: 0.6px;
            color: var(--text-secondary);
        }

        td {
            padding: 14px 16px;
            font-size: 14px;
            border-bottom: 1px solid var(--border);
            vertical-align: middle;
        }

        tr:last-child td {
            border-bottom: none;
        }

        tbody tr {
            transition: background 0.1s ease;
        }

        tbody tr:hover {
            background: #fafaf9;
        }

        .badge {
            display: inline-block;
            padding: 2px 8px;
            border-radius: 20px;
            font-size: 11px;
            font-weight: 500;
        }

        .badge-green {
            background: #f0fdf4;
            color: var(--success);
        }

        .badge-gray {
            background: #f3f4f6;
            color: var(--text-secondary);
        }

        .price {
            font-family: 'DM Mono', monospace;
            font-size: 13px;
        }

        /* Modal Overlay */
        .modal-overlay {
            display: none;
            position: fixed;
            inset: 0;
            background: rgba(0, 0, 0, 0.4);
            backdrop-filter: blur(4px);
            z-index: 100;
            align-items: center;
            justify-content: center;
            padding: 24px;
        }

        .modal-overlay.active {
            display: flex;
            animation: fadeIn 0.2s ease;
        }

        @keyframes fadeIn {
            from {
                opacity: 0;
            }

            to {
                opacity: 1;
            }
        }

        @keyframes slideUp {
            from {
                opacity: 0;
                transform: translateY(16px);
            }

            to {
                opacity: 1;
                transform: translateY(0);
            }
        }

        .modal {
            background: var(--surface);
            border-radius: 14px;
            box-shadow: var(--shadow-lg);
            width: 100%;
            max-width: 560px;
            max-height: 90vh;
            overflow-y: auto;
            animation: slideUp 0.25s ease;
        }

        .modal-header {
            display: flex;
            align-items: center;
            justify-content: space-between;
            padding: 24px 28px 0;
        }

        .modal-header h2 {
            font-size: 18px;
            font-weight: 600;
            letter-spacing: -0.3px;
        }

        .modal-close {
            width: 32px;
            height: 32px;
            border-radius: 8px;
            border: 1px solid var(--border);
            background: transparent;
            cursor: pointer;
            display: flex;
            align-items: center;
            justify-content: center;
            color: var(--text-secondary);
            font-size: 18px;
            transition: all 0.15s;
        }

        .modal-close:hover {
            background: var(--bg);
            color: var(--text-primary);
        }

        .modal-body {
            padding: 24px 28px 28px;
        }

        /* Form */
        .form-grid {
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 16px;
        }

        .form-group {
            display: flex;
            flex-direction: column;
            gap: 6px;
        }

        .form-group.full {
            grid-column: 1 / -1;
        }

        label {
            font-size: 13px;
            font-weight: 500;
            color: var(--text-primary);
        }

        label .required {
            color: var(--accent);
            margin-left: 2px;
        }

        input[type="text"],
        input[type="number"],
        select {
            padding: 10px 12px;
            border: 1px solid var(--border);
            border-radius: 8px;
            font-family: 'DM Sans', sans-serif;
            font-size: 14px;
            color: var(--text-primary);
            background: var(--surface);
            transition: border-color 0.15s, box-shadow 0.15s;
            outline: none;
        }

        input:focus,
        select:focus {
            border-color: var(--accent);
            box-shadow: 0 0 0 3px rgba(37, 99, 235, 0.1);
        }

        .modal-error {
            background: #fef2f2;
            color: var(--danger);
            border: 1px solid #fecaca;
            padding: 10px 14px;
            border-radius: 8px;
            font-size: 13px;
            margin-bottom: 16px;
        }

        .modal-footer {
            display: flex;
            gap: 10px;
            justify-content: flex-end;
            margin-top: 24px;
            padding-top: 20px;
            border-top: 1px solid var(--border);
        }
    </style>
</head>

<body>
    <div class="container">

        <div class="header">
            <div class="header-left">
                <h1>Products</h1>
                <p>Manage your product catalog</p>
            </div>
            <button class="btn btn-primary" onclick="openModal()">
                <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5">
                    <line x1="12" y1="5" x2="12" y2="19" />
                    <line x1="5" y1="12" x2="19" y2="12" />
                </svg>
                Add Product
            </button>
        </div>

        <?php if ($success): ?>
            <div class="alert alert-success">
                <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                    <polyline points="20 6 9 17 4 12" />
                </svg>
                <?= $success ?>
            </div>
        <?php endif; ?>

        <div class="card">
            <table>
                <thead>
                    <tr>
                        <th>Name</th>
                        <th>Price</th>
                        <th>Qty</th>
                        <th>Category</th>
                        <th>Manufacturer</th>
                        <th>Barcode</th>
                        <th>Weight</th>
                        <th>In Stock</th>
                        <th>Availability</th>
                    </tr>
                </thead>
                <tbody>
                    <?php $productsList->print_html_table_with_all_products(); ?>
                </tbody>
            </table>
        </div>

    </div>

    <!-- Modal -->
    <div class="modal-overlay <?= $error ? 'active' : '' ?>" id="modalOverlay" onclick="handleOverlayClick(event)">
        <div class="modal" id="modal">
            <div class="modal-header">
                <h2>Add New Product</h2>
                <button class="modal-close" onclick="closeModal()">×</button>
            </div>
            <div class="modal-body">

                <?php if ($error): ?>
                    <div class="modal-error">
                        <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" style="display:inline;margin-right:6px;">
                            <circle cx="12" cy="12" r="10" />
                            <line x1="12" y1="8" x2="12" y2="12" />
                            <line x1="12" y1="16" x2="12.01" y2="16" />
                        </svg>
                        <?= $error ?>
                    </div>
                <?php endif; ?>

                <form method="POST" action="">
                    <div class="form-grid">

                        <div class="form-group full">
                            <label>Name <span class="required">*</span></label>
                            <input type="text" name="name" value="<?= htmlspecialchars($form_data['name'] ?? '') ?>" placeholder="Product name">
                        </div>

                        <div class="form-group">
                            <label>Price</label>
                            <input type="text" name="price" value="<?= htmlspecialchars($form_data['price'] ?? '') ?>" placeholder="e.g. 49.99">
                        </div>

                        <div class="form-group">
                            <label>Quantity</label>
                            <input type="text" name="quantity" value="<?= htmlspecialchars($form_data['quantity'] ?? '') ?>" placeholder="e.g. 10">
                        </div>

                        <div class="form-group">
                            <label>Category</label>
                            <input type="text" name="category" value="<?= htmlspecialchars($form_data['category'] ?? '') ?>" placeholder="e.g. Electronics">
                        </div>

                        <div class="form-group">
                            <label>Manufacturer</label>
                            <input type="text" name="manufacturer" value="<?= htmlspecialchars($form_data['manufacturer'] ?? '') ?>" placeholder="e.g. Apple">
                        </div>

                        <div class="form-group">
                            <label>Barcode</label>
                            <input type="text" name="barcode" value="<?= htmlspecialchars($form_data['barcode'] ?? '') ?>" placeholder="e.g. 1234567890">
                        </div>

                        <div class="form-group">
                            <label>Weight</label>
                            <input type="text" name="weight" value="<?= htmlspecialchars($form_data['weight'] ?? '') ?>" placeholder="e.g. 1.2kg">
                        </div>

                        <div class="form-group">
                            <label>In Stock</label>
                            <select name="instock">
                                <option value="Y" <?= ($form_data['instock'] ?? '') === 'Y' ? 'selected' : '' ?>>Yes</option>
                                <option value="N" <?= ($form_data['instock'] ?? '') === 'N' ? 'selected' : '' ?>>No</option>
                            </select>
                        </div>

                        <div class="form-group full">
                            <label>Availability</label>
                            <input type="text" name="availability" value="<?= htmlspecialchars($form_data['availability'] ?? '') ?>" placeholder="e.g. Άμεσα Διαθέσιμο">
                        </div>

                    </div>

                    <div class="modal-footer">
                        <button type="button" class="btn btn-ghost" onclick="closeModal()">Cancel</button>
                        <button type="submit" class="btn btn-primary">Save Product</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <script>
        const alert = document.querySelector('.alert-success');
        if (alert) {
            setTimeout(() => {
                alert.style.transition = 'opacity 0.5s ease';
                alert.style.opacity = '0';
                setTimeout(() => alert.remove(), 500);
            }, 3000);
        }

        function openModal() {
            document.getElementById('modalOverlay').classList.add('active');
            document.body.style.overflow = 'hidden';
        }

        function closeModal() {
            document.getElementById('modalOverlay').classList.remove('active');
            document.body.style.overflow = '';
        }

        function handleOverlayClick(e) {
            if (e.target === document.getElementById('modalOverlay')) closeModal();
        }

        document.addEventListener('keydown', e => {
            if (e.key === 'Escape') closeModal();
        });

        // 1. Red border on NAME if empty
        document.querySelector('form').addEventListener('submit', function(e) {
            const nameInput = document.querySelector('input[name="name"]');
            if (!nameInput.value.trim()) {
                nameInput.style.borderColor = 'var(--danger)';
                nameInput.style.boxShadow = '0 0 0 3px rgba(220,38,38,0.1)';
                nameInput.focus();
            } else {
                nameInput.style.borderColor = '';
                nameInput.style.boxShadow = '';
            }
        });

        // Reset red border when user starts typing
        document.querySelector('input[name="name"]').addEventListener('input', function() {
            this.style.borderColor = '';
            this.style.boxShadow = '';
        });

        // 2. Price - only numbers and dot
        document.querySelector('input[name="price"]').addEventListener('keypress', function(e) {
            const allowed = /[0-9.]/;
            if (!allowed.test(e.key)) e.preventDefault();
            // Prevent multiple dots
            if (e.key === '.' && this.value.includes('.')) e.preventDefault();
        });

        // 3. Weight - auto append "kg"
        const weightInput = document.querySelector('input[name="weight"]');
        weightInput.addEventListener('blur', function() {
            const val = this.value.trim();
            if (val && !val.endsWith('kg')) {
                this.value = val + 'kg';
            }
        });

        weightInput.addEventListener('focus', function() {
            if (this.value.endsWith('kg')) {
                this.value = this.value.slice(0, -2);
            }
        }); 
        
        // Remove ?success=1 from URL after showing message
        if (window.location.search.includes('success=1')) {
            history.replaceState(null, '', window.location.pathname);
        }
    </script>

</body>

</html>