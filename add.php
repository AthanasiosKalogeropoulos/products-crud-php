<?php

require_once 'config/db.php';
require_once 'lib.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {

    $name   = trim($_POST['name'] ?? '');
    $price  = (float) ($_POST['price'] ?? 0);
    $weight = (float) ($_POST['weight'] ?? 0);

    $errors = [];

    if (empty($name)) {
        $errors[] = 'Name is required.';
    }
    if ($price < 0) {
        $errors[] = 'Price must be a positive number.';
    }
    if ($weight < 0) {
        $errors[] = 'Weight must be a positive number.';
    }

    if (empty($errors)) {
        $products = new Products($pdo);
        $products->add_product($name, $price, $weight);
        header('Location: products.php?success=1');
        exit;
    } else {
        $errorMsg = urlencode(implode(' ', $errors));
        header("Location: products.php?error=$errorMsg");
        exit;
    }
}

header('Location: products.php');
exit;
