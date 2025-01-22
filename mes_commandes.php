<?php
session_start();
include('con_bdd.php');

if (!isset($_SESSION['user_id'])) {
    $_SESSION['error'] = "Vous devez être connecté pour voir vos commandes.";
    header('Location: login.php');
    exit();
}

$userId = $_SESSION['user_id'];

$stmt = $mysqlClient->prepare("
    SELECT 
        o.order_id,
        o.pickup_datetime,
        o.status,
        o.total_amount
    FROM ORDERS o
    WHERE o.customer_id = :customer_id
    ORDER BY o.order_datetime DESC
");
$stmt->bindParam(':customer_id', $userId);
$stmt->execute();
$orders = $stmt->fetchAll(PDO::FETCH_ASSOC);
?>

<!DOCTYPE html>
<html lang="fr">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Mes Commandes</title>
    <link rel="stylesheet" href="styles.css">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH" crossorigin="anonymous">
    <link href="https://fonts.googleapis.com/css2?family=Fira+Sans&display=swap" rel="stylesheet">
</head>

<body>
    <header class="custom-header d-flex flex-wrap align-items-center justify-content-between py-é mb-4">
        <div class="col-md-3 text-start">
            <a href="/" class="d-inline-flex link-body-emphasis text-decoration-none">
                <img src="images/logo_mcs.png" alt="Logo MOCHA" width="200" height="50">
            </a>
        </div>
        <ul class="nav col-12 col-md-auto mb-2 justify-content-center mb-md-0">
            <li><a href="accueil.php" class="nav-link px-4 text-uppercase text-white">Accueil</a></li>
            <li><a href="catalogue.php" class="nav-link px-4 text-uppercase text-white">Catalogue</a></li>
            <li><a href="mes_commandes.php" class="nav-link px-4 text-uppercase text-white">Mes commandes</a></li>
        </ul>
        <div class="col-md-3 text-end pe-é">
            <?php if (isset($_SESSION['user_id'])): ?>
                <a href="logout.php" class="btn btn-primary btn-lg mt-1">Déconnexion</a>
            <?php else: ?>
                <a href="login.php" class="btn btn-primary btn-lg mt-1">Se connecter</a>
                <a href="signup.php" class="btn btn-primary btn-lg mt-1">S'inscrire</a>
            <?php endif; ?>
        </div>
    </header>

    <main class="container">
        <h2>Mes Commandes</h2>

        <?php if (empty($orders)): ?>
            <p>Vous n'avez pas encore passé de commandes.</p>
        <?php else: ?>
            <?php foreach ($orders as $order): ?>
                <div class="card mb-4">
                    <div class="card-header">
                        <h5 class="card-title">Commande n°<?= htmlspecialchars($order['order_id']) ?></h5>
                        <p>
                            <strong>Heure de récupération :</strong> <?= htmlspecialchars($order['pickup_datetime']) ?><br>
                            <strong>Statut :</strong> <?= htmlspecialchars($order['status']) ?><br>
                            <strong>Prix total :</strong> <?= number_format($order['total_amount'], 2) ?>€
                        </p>
                    </div>
                    <div class="card-body">
                        <h6>Produits :</h6>
                        <ul class="list-group">
                            <?php
                            $productStmt = $mysqlClient->prepare("
                                SELECT 
                                    oi.quantity,
                                    oi.sugar,
                                    oi.size,
                                    oi.customization,
                                    oi.unit_price,
                                    p.name
                                FROM ORDER_ITEMS oi
                                JOIN PRODUCTS p ON oi.product_id = p.product_id
                                WHERE oi.order_id = :order_id
                            ");
                            $productStmt->bindParam(':order_id', $order['order_id']);
                            $productStmt->execute();
                            $products = $productStmt->fetchAll(PDO::FETCH_ASSOC);

                            foreach ($products as $product):
                            ?>
                                <li class="list-group-item order">
                                    <strong><?= htmlspecialchars($product['name']) ?></strong><br>
                                    Quantité : <?= htmlspecialchars($product['quantity']) ?><br>
                                    Taille : <?= htmlspecialchars($product['size']) ?><br>
                                    Sucre : <?= htmlspecialchars($product['sugar']) ?><br>
                                    Prix unitaire : <?= number_format($product['unit_price'], 2) ?>€
                                </li>
                            <?php endforeach; ?>
                        </ul>
                    </div>
                </div>
            <?php endforeach; ?>
        <?php endif; ?>
    </main>

    <footer class="py-3 my-4">
        <p class="footer">© 2025 Café MOCHA</p>
    </footer>
</body>

</html>