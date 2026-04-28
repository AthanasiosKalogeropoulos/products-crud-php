<?php
require_once("./lib.php");

$error = '';
$success = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (empty(trim($_POST['name']))) {
        $error = 'Το πεδίο NAME είναι υποχρεωτικό.';
    } else {
        $productsList = new Products("./products.xml");
        $productsList->add_product($_POST);
        $success = 'Το προϊόν προστέθηκε επιτυχώς!';
    }
}
?>

<!DOCTYPE html>
<html>

<body>

    <h1>Add New Product</h1>

    <?php if ($error): ?>
        <p style="color:red;"><?= $error ?></p>
    <?php endif; ?>

    <?php if ($success): ?>
        <p style="color:green;"><?= $success ?></p>
    <?php endif; ?>

    <form method="POST" action="">
        <label>Name *</label><br>
        <input type="text" name="name"><br><br>

        <label>Price</label><br>
        <input type="text" name="price"><br><br>

        <label>Quantity</label><br>
        <input type="text" name="quantity"><br><br>

        <label>Category</label><br>
        <input type="text" name="category"><br><br>

        <label>Manufacturer</label><br>
        <input type="text" name="manufacturer"><br><br>

        <label>Barcode</label><br>
        <input type="text" name="barcode"><br><br>

        <label>Weight</label><br>
        <input type="text" name="weight"><br><br>

        <label>In Stock (Y/N)</label><br>
        <input type="text" name="instock"><br><br>

        <label>Availability</label><br>
        <input type="text" name="availability"><br><br>

        <button type="submit">Add Product</button>
    </form>

    <br>
    <a href="./products.php">← Back to products</a>

</body>

</html>