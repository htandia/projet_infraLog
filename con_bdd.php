<?php
try {
    $mysqlClient = new PDO('mysql:host=192.168.56.102;dbname=db_cafe;charset=utf8', 'user', 'network'); // Connexion
} catch (Exception $e) {
    die('Erreur : ' . $e->getMessage());
}

$searchTerm = isset($_GET['search']) ? htmlspecialchars($_GET['search']) : '';

if (!empty($searchTerm)) {
    $request = 'SELECT * FROM products WHERE name LIKE :search';
    $productsStatement = $mysqlClient->prepare($request);
    $productsStatement->bindValue(':search', '%' . $searchTerm . '%', PDO::PARAM_STR);
} else {
    $request = 'SELECT * FROM PRODUCTS';
    $productsStatement = $mysqlClient->prepare($request);
}

$productsStatement->execute();
$products = $productsStatement->fetchAll();
