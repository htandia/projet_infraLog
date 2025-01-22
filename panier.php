<?php
session_start();
include('con_bdd.php');

if (isset($_GET['del']) && is_numeric($_GET['del'])) {
    $indexToDelete = intval($_GET['del']);
    if (isset($_SESSION['panier'][$indexToDelete])) {
        unset($_SESSION['panier'][$indexToDelete]);
    }
    exit();
}

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    if (empty($_SESSION['panier'])) {
        $_SESSION['error'] = "Votre panier est vide. Veuillez ajouter des articles avant de valider la commande.";
        header('Location: panier.php');
        exit();
    }

    if (isset($_SESSION['user_id'])) {
        $userId = $_SESSION['user_id'];
        $pickupDatetime = htmlspecialchars($_POST['pickup_datetime']);

        $totalAmount = 0;
        foreach ($_SESSION['panier'] as $item) {
            $totalAmount += $item['price'];
        }

        try {
            $mysqlClient->beginTransaction();

            $orderStmt = $mysqlClient->prepare("
                INSERT INTO ORDERS (status, total_amount, pickup_datetime, customer_id)
                VALUES ('pending', :total_amount, :pickup_datetime, :customer_id)
            ");
            $orderStmt->bindParam(':total_amount', $totalAmount);
            $orderStmt->bindParam(':pickup_datetime', $pickupDatetime);
            $orderStmt->bindParam(':customer_id', $userId);
            $orderStmt->execute();

            $orderId = $mysqlClient->lastInsertId();

            $orderItemStmt = $mysqlClient->prepare("
                INSERT INTO ORDER_ITEMS (quantity, sugar, size, customization, unit_price, price, order_id, product_id)
                VALUES (:quantity, :sugar, :size, :customization, :unit_price, :price, :order_id, :product_id)
            ");

            foreach ($_SESSION['panier'] as $item) {
                $orderItemStmt->bindParam(':quantity', $item['quantity']);
                $orderItemStmt->bindParam(':sugar', $item['sugar']);
                $orderItemStmt->bindParam(':size', $item['size']);
                $orderItemStmt->bindParam(':customization', $item['customization']);
                $orderItemStmt->bindParam(':unit_price', $item['unit_price']);
                $orderItemStmt->bindParam(':price', $item['price']);
                $orderItemStmt->bindParam(':order_id', $orderId);
                $orderItemStmt->bindParam(':product_id', $item['product_id']);
                $orderItemStmt->execute();
            }

            $mysqlClient->commit();

            unset($_SESSION['panier']);
            $_SESSION['success'] = "Commande validée avec succès.";
            header('Location: mes_commandes.php');
            exit();
        } catch (Exception $e) {
            $mysqlClient->rollBack();
            $_SESSION['error'] = "Erreur lors de la validation de la commande : " . $e->getMessage();
            header('Location: panier.php');
            exit();
        }
    } else {
        $_SESSION['error'] = "Vous devez être connecté pour valider votre commande.";
        header('Location: login.php');
        exit();
    }
}
?>
<?php if (isset($_SESSION['error'])): ?>
    <div class="alert alert-danger">
        <?= htmlspecialchars($_SESSION['error']) ?>
    </div>
    <?php unset($_SESSION['error']); ?>
<?php endif; ?>

<?php if (isset($_SESSION['success'])): ?>
    <div class="alert alert-success">
        <?= htmlspecialchars($_SESSION['success']) ?>
    </div>
    <?php unset($_SESSION['success']); ?>
<?php endif; ?>


<!DOCTYPE html>
<html lang="fr">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Panier</title>
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
    <section class="panier-section">
        <h1>Votre Panier</h1>
        <table class="panier-table">
            <thead>
                <tr>
                    <th>Nom</th>
                    <th>Taille</th>
                    <th>Sucre</th>
                    <th>Quantité</th>
                    <th>Prix unitaire</th>
                    <th>Prix total</th>
                    <th></th>
                </tr>
            </thead>
            <tbody>
                <?php
                $total = 0;
                if (!isset($_SESSION['panier']) || empty($_SESSION['panier'])) {
                    echo "<tr><td colspan='7'>Votre panier est vide</td></tr>";
                } else {
                    foreach ($_SESSION['panier'] as $index => $item) {
                        $total += $item['price'];
                ?>
                        <tr>
                            <td><?= htmlspecialchars($item['name']) ?></td>
                            <td><?= htmlspecialchars($item['size']) ?></td>
                            <td><?= htmlspecialchars($item['sugar']) ?></td>
                            <td><?= htmlspecialchars($item['quantity']) ?></td>
                            <td><?= number_format($item['unit_price'], 2) ?>€</td>
                            <td><?= number_format($item['price'], 2) ?>€</td>
                            <td>
                                <a href="panier.php?del=<?= $index ?>" class="delete-btn">
                                    <img src="images/delete.png" alt="Supprimer" style="width: 24px; height: 24px;">
                                </a>
                            </td>
                        </tr>
                <?php
                    }
                }
                ?>
                <tr>
                    <th colspan="7" class="total">Total : <?= number_format($total, 2) ?>€</th>
                </tr>
            </tbody>
        </table>
    </section>
    <section class="panier-section">
        <form method="POST" action="panier.php">
            <div class="mb-3">
                <label for="pickup_datetime" class="form-label">Heure et date de récupération</label>
                <input type="datetime-local" class="form-control form-design" id="pickup_datetime" name="pickup_datetime" required>
            </div>
            <button type="submit" class="btn btn-primary">Valider</button>
        </form>
    </section>
    </body>
    <footer class="py-1 my-4">
        <p class="footer">© 2025 Café MOCHA</p>
    </footer>

</html>