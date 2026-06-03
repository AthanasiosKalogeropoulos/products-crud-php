<?php

require_once 'config/db.php';
require_once 'lib.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $id = (int) ($_POST['id'] ?? 0);

    if ($id > 0) {
        $products = new Products($pdo);
        $products->delete($id);
        header('Location: products.php?success=deleted');
        exit;
    }
}

header('Location: products.php');
exit;
