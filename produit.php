<?php
include('con_bdd.php');

$product_id = isset($_GET['id']) ? intval($_GET['id']) : 0;

if ($product_id <= 0) {
    echo "ID de produit invalide.";
    exit;
}

try {
    $sql = "SELECT * FROM products WHERE product_id = :id";
    $stmt = $mysqlClient->prepare($sql);
    $stmt->bindValue(':id', $product_id, PDO::PARAM_INT);
    $stmt->execute();
    $product = $stmt->fetch(PDO::FETCH_ASSOC);
    if (!$product) {
        echo "Produit introuvable.";
        exit;
    }
} catch (Exception $e) {
    die('Erreur lors de la récupération des données : ' . $e->getMessage());
}
?>

<!DOCTYPE html>
<html lang="fr">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?php echo htmlspecialchars($product['name']); ?></title>
</head>

<body>
    <h1><?php echo htmlspecialchars($product['name']); ?></h1>
    <img src="<?php echo htmlspecialchars($product['image_url']); ?>" alt="<?php echo htmlspecialchars($product['name']); ?>">
    <p>Description : <?php echo htmlspecialchars($product['description']); ?></p>
    <p>Catégorie : <?php echo htmlspecialchars($product['category']); ?></p>
    <p>Prix : <?php echo htmlspecialchars($product['price']); ?>€</p>
</body>

</html>