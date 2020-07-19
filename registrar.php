<?php

require 'database.php';

$message = '';

if (!empty($_POST['email']) && !empty($_POST['password'])) {
    $sql = "INSERT INTO users (email, password) VALUES (:email, :password)";
    $stmt = $conn->prepare($sql);
    $stmt->bindParam(':email', $_POST['email']);
    $password = password_hash($_POST['password'], PASSWORD_BCRYPT);
    $stmt->bindParam(':password', $password);


    if ($stmt->execute()) {
        $message = 'Usuario creado correctamente';
    } else {
        $message = 'Sorry there must have been an issue creating your account';
    }
}
?>

<!DOCTYPE html>
<html>

<head>
    <meta charset="utf-8">
    <title>SignUp</title>
    <link href="https://fonts.googleapis.com/css?family=Roboto" rel="stylesheet">
    <link rel="stylesheet" href="assets/css/style.css">
</head>

<body>
    <?php require 'partials/header.php' ?>


    <?php if (!empty($message)) : ?>
        <p> <?= $message ?></p>
    <?php endif; ?>

    <h1>Registrar</h1>
    <span>o <a href="login.php">Login</a></span>

    <form action="registrar.php" method="POST">
        <input name="email" type="text" placeholder="Enter your email">
        <input name="password" type="password" placeholder="Enter your Password">
        <input type="submit" value="Registrarse">
    </form>

</body>

</html>