<?php
session_start();
include('con_bdd.php');

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $email = htmlspecialchars($_POST['email']);
    $password = $_POST['password'];

    $stmt = $mysqlClient->prepare("SELECT * FROM CUSTOMERS WHERE email = :email");
    $stmt->bindParam(':email', $email);
    $stmt->execute();
    $user = $stmt->fetch();

    if ($user && password_verify($password, $user['password'])) {
        $_SESSION['user_id'] = $user['customer_id'];
        $_SESSION['email'] = $user['email'];
        $_SESSION['first_name'] = $user['first_name'];
        $_SESSION['last_name'] = $user['last_name'];

        header('Location: catalogue.php');
        exit;
    } else {
        $_SESSION['error'] = "Email ou mot de passe incorrect";
        header('Location: login.php');
        exit;
    }
}
