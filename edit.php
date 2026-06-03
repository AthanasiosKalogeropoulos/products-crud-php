<?php

require_once 'config/db.php';
require_once 'lib.php';

$products = new Products($pdo);

// POST: Save changes
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $id     = (int) ($_POST['id'] ?? 0);
    $name   = trim($_POST['name'] ?? '');
    $price  = (float) ($_POST['price'] ?? 0);
    $weight = (float) ($_POST['weight'] ?? 0);

    $errors = [];
    if (empty($name))  $errors[] = 'Name is required.';
    if ($price < 0)    $errors[] = 'Price must be positive.';
    if ($weight < 0)   $errors[] = 'Weight must be positive.';

    if (empty($errors)) {
        $products->update($id, $name, $price, $weight);
        header('Location: products.php?success=updated');
        exit;
    }
}

// GET: Load form
$id = (int) ($_GET['id'] ?? $_POST['id'] ?? 0);

if (!$id) {
    header('Location: products.php');
    exit;
}

$product = $products->getById($id);

if (!$product) {
    header('Location: products.php?error=not_found');
    exit;
}
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Edit Product</title>
    <link rel="stylesheet" href="style.css">
</head>

<body>
    <div class="container">
        <h1>Edit Product</h1>

        <?php if (!empty($errors)): ?>
            <div class="alert">
                <?= htmlspecialchars(implode(' ', $errors)) ?>
            </div>
        <?php endif; ?>

        <div class="card">
            <form method="POST" action="edit.php">
                <input type="hidden" name="id" value="<?= $product['id'] ?>">

                <div class="form-group">
                    <label for="name">Product Name *</label>
                    <input
                        type="text"
                        id="name"
                        name="name"
                        value="<?= htmlspecialchars($product['name']) ?>"
                        required>
                </div>

                <div class="form-group">
                    <label for="price">Price (€)</label>
                    <input
                        type="number"
                        id="price"
                        name="price"
                        step="0.01"
                        min="0"
                        value="<?= $product['price'] ?>">
                </div>

                <div class="form-group">
                    <label for="weight">Weight (kg)</label>
                    <input
                        type="number"
                        id="weight"
                        name="weight"
                        step="0.001"
                        min="0"
                        value="<?= $product['weight'] ?>">
                </div>

                <div class="form-actions">
                    <button type="submit" class="btn btn-primary">Save Changes</button>
                    <a href="products.php" class="btn btn-secondary">Cancel</a>
                </div>
            </form>
        </div>
    </div>
</body>

</html>