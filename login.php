<?php
session_start();
include('con_bdd.php');

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $email = htmlspecialchars($_POST['email']);
    $password = $_POST['password'];

    $stmt = $mysqlClient->prepare("SELECT * FROM CUSTOMERS WHERE email = :email");
    $stmt->bindParam(':email', $email);
    $stmt->execute();
    $user = $stmt->fetch(PDO::FETCH_ASSOC);

    if ($user && password_verify($password, $user['password'])) {
        $_SESSION['user_id'] = $user['id'];
        $_SESSION['first_name'] = $user['first_name'];
        header('Location: accueil.php');
        exit;
    } else {
        $_SESSION['error'] = "Email ou mot de passe incorrect.";
        header('Location: login.php');
        exit;
    }
}
?>

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
                <!-- si connecté alors bouton de déconnexion -->
                <a href="logout.php" class="btn btn-primary btn-lg mt-1">Déconnexion</a>
            <?php else: ?>
                <!-- si pas connecté alors boutonq de login et signup -->
                <a href="login.php" class="btn btn-primary btn-lg mt-1">Se connecter</a>
                <a href="signup.php" class="btn btn-primary btn-lg mt-1">S'inscrire</a>
            <?php endif; ?>
        </div>
    </header>
    <div class="container">
        <h2>Connexion</h2>
        <form method="POST" action="login_process.php">
            <div class="mb-3">
                <label for="email" class="form-label">Email</label>
                <input type="email" class="form-control form-design" id="email" name="email" required>
            </div>
            <div class="mb-3">
                <label for="password" class="form-label">Mot de passe</label>
                <input type="password" class="form-control form-design" id="password" name="password" required>
            </div>
            <button type="submit" class="btn btn-primary">Se connecter</button>
        </form>
        <?php
        if (isset($_SESSION['error'])) {
            echo '<p class="text-danger">' . $_SESSION['error'] . '</p>';
            unset($_SESSION['error']);
        }
        ?>
    </div>
    <footer class="py-3 my-4">
        <p class="footer">© 2025 Café MOCHA</p>
    </footer>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js" integrity="sha384-YvpcrYf0tY3lHB60NNkmXc5s9fDVZLESaAA55NDzOxhy9GkcIdslK1eN7N6jIeHz" crossorigin="anonymous"></script>
</body>

</html>