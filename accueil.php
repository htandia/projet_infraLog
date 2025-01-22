<?php
include('con_bdd.php');
?>

<!DOCTYPE html>
<html lang="fr">

<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <title>accueil</title>
  <link rel="stylesheet" href="styles.css"> <!-- pour relier à la page CSS-->
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH" crossorigin="anonymous">
  <link href="https://fonts.googleapis.com/css2?family=Fira+Sans&display=swap" rel="stylesheet">
</head>

<body>
  <header class="custom-header d-flex flex-wrap align-items-center justify-content-between py-é mb-4">
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
  <div class="logo text-center">
    <img src="images/logo_mocha.png" alt="Logo" height="160px">
  </div>
  <div>
    <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 1440 320">
      <path fill="#534646" fill-opacity="1" d="M0,96L80,85.3C160,75,320,53,480,74.7C640,96,800,160,960,165.3C1120,171,1280,117,1360,90.7L1440,64L1440,0L1360,0C1280,0,1120,0,960,0C800,0,640,0,480,0C320,0,160,0,80,0L0,0Z"></path>
    </svg>
  </div>
  <main class="text-center">
    <div class="quote-section">
      <blockquote class="blockquote">
        <p class="mb-0">« Le café n'est pas qu'une boisson, c'est une expérience. »</p>
      </blockquote>
      <a href="catalogue.php" class="btn btn-primary btn-lg mt-3">Découvrir notre catalogue</a>
    </div>
  </main>

  <footer class="py-3 my-4 footer">
    <p class="footer">© 2025 Café MOCHA</p>
  </footer>
  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js" integrity="sha384-YvpcrYf0tY3lHB60NNkmXc5s9fDVZLESaAA55NDzOxhy9GkcIdslK1eN7N6jIeHz" crossorigin="anonymous"></script>
  <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.11.8/dist/umd/popper.min.js" integrity="sha384-I7E8VVD/ismYTF4hNIPjVp/Zjvgyol6VFvRkX/vR+Vc4jQkC+hVqc2pM8ODewa9r" crossorigin="anonymous"></script>
  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.min.js" integrity="sha384-0pUGZvbkm6XF6gxjEnlmuGrJXVbNuzT9qBBavbLwCsOGabYfZo0T0to5eqruptLy" crossorigin="anonymous"></script>
</body>

</html>

<style>
  .logo {
    text-align: center;
    padding-top: 45px;
    padding-bottom: 50px;
    background-color: #534646;
  }

  .quote-section {
    padding: 0px 0;
    padding-bottom: 300px;
  }

  .quote-section blockquote {
    font-size: 2rem;
    font-style: bold;
    color: #534646;
  }

  .footer {
    margin-top: 50px;
    text-align: center;
  }

  footer {
    padding-top: 50px;
  }
</style>