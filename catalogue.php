<?php
session_start();
include('con_bdd.php');
?>

<!DOCTYPE html>
<html lang="fr">

<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <meta http-equiv="X-UA-Compatible" content="IE=edge">
  <link rel="stylesheet" href="styles.css"> <!-- pour relier à la page CSS-->
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
        <!-- Si l'utilisateur est connecté, afficher le bouton de déconnexion -->
        <a href="logout.php" class="btn btn-primary btn-lg mt-1">Déconnexion</a>
      <?php else: ?>
        <!-- Si l'utilisateur n'est pas connecté, afficher les boutons de login et signup -->
        <a href="login.php" class="btn btn-primary btn-lg mt-1">Se connecter</a>
        <a href="signup.php" class="btn btn-primary btn-lg mt-1">S'inscrire</a>
      <?php endif; ?>
    </div>
  </header>
  <div class="header-container">
    <div class="header-elements"></div>
    <div class="header-elements">
      <form method="GET" action="catalogue.php" class="d-flex">
        <input
          type="search"
          name="search"
          class="form-control search"
          placeholder="Rechercher un produit..."
          aria-label="Search"
          value="<?= isset($_GET['search']) ? htmlspecialchars($_GET['search']) : ''; ?>">
        <button type="submit" class="btn btn-primary btn-lg mt-1">Rechercher</button>
      </form>
    </div>
    <div class="header-elements">
      <a href="panier.php" class="link">
        <img src="images/shopping_basket.png" alt="Panier" width="50" height="50">
        <span class="cart-badge note">
          <?php
          $totalItems = isset($_SESSION['panier']) ? count($_SESSION['panier']) : 0;
          echo $totalItems;
          ?>
        </span>
      </a>
    </div>
  </div>
  <main class="catalogue">
    <?php if (empty($products)): ?>
      <p class="search_error">Aucun produit trouvé pour votre recherche.</p>
    <?php else: ?>
      <div class="row row-cols-1 row-cols-md-4 g-4"> <!-- 4 cartes par ligne sur les écrans moyens -->
        <?php foreach ($products as $product): ?>
          <div class="col"> <!-- chaque carte occupe une colonne -->
            <div class="carte-produit-complete mb-1">
              <form action="ajouter_panier.php?id=<?= $product['product_id'] ?>" method="POST">
                <a href="produit.php?id=<?= $product['product_id']; ?>" class="no-underline text-dark">
                  <div class="product-card position-relative">
                    <img src="<?= htmlspecialchars($product['image_url']); ?>" alt="<?= htmlspecialchars($product['name']); ?>" class="product-image">
                    <?php if (!$product['available']): ?>
                      <p class="text-danger fw-bold hors-stock">Hors stock</p>
                    <?php endif; ?>
                    <h2 class="<?= !$product['available'] ? 'text-decoration-line-through' : ''; ?> mb-2"><?= htmlspecialchars($product['name']); ?></h2>
                    <p class="price mb-3"><?= $product['price']; ?>€</p>
                  </div>
                </a>
                <?php if ($product['available']): ?>
                  <div class="action-options d-flex align-items-center">
                    <div class="dropdowns-container me-3 d-flex flex-column">
                      <div class="size-select mb-2">
                        <select name="size" class="form-select long-dropdown" required>
                          <option value="">-- Taille --</option>
                          <option value="S">S</option>
                          <option value="M">M</option>
                          <option value="L">L</option>
                        </select>
                      </div>
                      <div class="sugar-select">
                        <select name="sugar" class="form-select long-dropdown" required>
                          <option value="">-- Quantité de sucre --</option>
                          <option value="faible">Faible</option>
                          <option value="moyen">Moyen</option>
                          <option value="élevé">Élevé</option>
                        </select>
                      </div>
                    </div>
                    <input type="number" name="quantity" value="1" min="1" class="form-control quantity-input me-3" style="width: 80px;" required>
                    <button type="submit" class="btn btn-success">+</button>
                  </div>
                <?php endif; ?>
              </form>
            </div>
          </div>
        <?php endforeach; ?>
      </div>
    <?php endif; ?>
  </main>
  <footer class="py-3 my-4">
    <p class="footer">© 2025 Café MOCHA </p>
  </footer>
  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js" integrity="sha384-YvpcrYf0tY3lHB60NNkmXc5s9fDVZLESaAA55NDzOxhy9GkcIdslK1eN7N6jIeHz" crossorigin="anonymous"></script>
  <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.11.8/dist/umd/popper.min.js" integrity="sha384-I7E8VVD/ismYTF4hNIPjVp/Zjvgyol6VFvRkX/vR+Vc4jQkC+hVqc2pM8ODewa9r" crossorigin="anonymous"></script>
  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.min.js" integrity="sha384-0pUGZvbkm6XF6gxjEnlmuGrJXVbNuzT9qBBavbLwCsOGabYfZo0T0to5eqruptLy" crossorigin="anonymous"></script>
</body>

</html>