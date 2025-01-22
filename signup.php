<?php
session_start();
include('con_bdd.php');

$error_message = isset($_SESSION['error']) ? $_SESSION['error'] : '';
unset($_SESSION['error']);

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $email = htmlspecialchars($_POST['email']);
    $password = $_POST['password'];
    $first_name = htmlspecialchars($_POST['first_name']);
    $last_name = htmlspecialchars($_POST['last_name']);
    $phone = htmlspecialchars($_POST['phone']);
    $stmt = $mysqlClient->prepare("SELECT COUNT(*) FROM CUSTOMERS WHERE email = :email");
    $stmt->bindParam(':email', $email);
    $stmt->execute();
    $email_exists = $stmt->fetchColumn() > 0;
    if ($email_exists) {
        $_SESSION['error'] = "Cet email est déjà utilisé.";
        header('Location: signup.php');
        exit;
    }
    $hashed_password = password_hash($password, PASSWORD_BCRYPT);
    $stmt = $mysqlClient->prepare("INSERT INTO CUSTOMERS (email, password, first_name, last_name, phone) VALUES (:email, :password, :first_name, :last_name, :phone)");
    $stmt->bindParam(':email', $email);
    $stmt->bindParam(':password', $hashed_password);
    $stmt->bindParam(':first_name', $first_name);
    $stmt->bindParam(':last_name', $last_name);
    $stmt->bindParam(':phone', $phone);
    if ($stmt->execute()) {
        $user_id = $mysqlClient->lastInsertId();
        $_SESSION['user_id'] = $user_id;
        $_SESSION['first_name'] = $first_name;
        header('Location: accueil.php');
        exit;
    } else {
        $_SESSION['error'] = "Erreur lors de l'inscription. Essayez à nouveau.";
        header('Location: signup.php');
        exit;
    }
}
?>

<!DOCTYPE html>
<html lang="fr">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
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
                <!-- bouton de déconnexion si connecté -->
                <a href="logout.php" class="btn btn-outline-light me-2 px-4 py-2">Déconnexion</a>
            <?php else: ?>
                <!-- bouton de login & sign up si pas connecté -->
                <a href="login.php" class="btn btn-primary btn-lg mt-1">Se connecter</a>
                <a href="signup.php" class="btn btn-primary btn-lg mt-1">S'inscrire</a>
            <?php endif; ?>
        </div>
    </header>
    <div class="container">
        <h2>Inscription</h2>
        <?php if (isset($_SESSION['error'])): ?>
            <div class="alert alert-danger"><?= htmlspecialchars($_SESSION['error']); ?></div>
            <?php unset($_SESSION['error']); ?>
        <?php endif; ?>
        <form action="signup.php" method="POST">
            <div class="mb-3">
                <label for="first_name" class="form-label">Prénom</label>
                <input type="text" class="form-control form-design" id="first_name" name="first_name" required>
            </div>
            <div class="mb-3">
                <label for="last_name" class="form-label">Nom</label>
                <input type="text" class="form-control form-design" id="last_name" name="last_name" required>
            </div>
            <div class="mb-3">
                <label for="email" class="form-label">Email</label>
                <input type="email" class="form-control form-design" id="email" name="email" required>
            </div>
            <div class="mb-3">
                <label for="password" class="form-label">Mot de passe</label>
                <input type="password" class="form-control form-design" id="password" name="password" required>
            </div>
            <div class="mb-3">
                <label for="phone" class="form-label">Numéro de téléphone</label>
                <input type="text" class="form-control form-design" id="phone" name="phone" required>
            </div>
            <button type="submit" class="btn btn-primary">S'inscrire</button>
        </form>
        <p class="mt-3">Vous avez déjà un compte ? <a href="login.php" class="lien">Connectez-vous ici !</a></p>
    </div>
    <footer class="py-3 my-4">
        <p class="footer">© 2025 Café MOCHA </p>
    </footer>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js" integrity="sha384-YvpcrYf0tY3lHB60NNkmXc5s9fDVZLESaAA55NDzOxhy9GkcIdslK1eN7N6jIeHz" crossorigin="anonymous"></script>
</body>

</html>

<style>
    footer {
        padding-top: 500px !important;
    }
</style>