<?php
include('con_bdd.php');

if (!isset($_SESSION)) {
    session_start();
}

if (!isset($_SESSION['panier'])) {
    $_SESSION['panier'] = [];
}

if (isset($_GET['id']) && is_numeric($_GET['id'])) {
    $id = intval($_GET['id']);
    $request = 'SELECT * FROM products WHERE product_id = ?';
    $query = $mysqlClient->prepare($request);
    $query->execute([$id]);
    $produit = $query->fetch(PDO::FETCH_ASSOC);

    if (!$produit) {
        die("Ce produit n'existe pas.");
    }

    $size = $_POST['size'] ?? 'medium';
    $sugar = $_POST['sugar'] ?? 'medium';
    $quantity = intval($_POST['quantity'] ?? 1);
    $unit_price = floatval($produit['price']);
    $price = $unit_price * $quantity;
    $orderItem = [
        'product_id' => $id,
        'name' => $produit['name'],
        'size' => $size,
        'sugar' => $sugar,
        'quantity' => $quantity,
        'unit_price' => $unit_price,
        'price' => $price
    ];

    $_SESSION['panier'][] = $orderItem;

    header("Location: catalogue.php");
    exit();
}
