<?php
// index.php

session_start();


if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    // Carica le credenziali dal file
    $config = require 'login-config.php';

    // Verifica se le credenziali sono corrette
    if ($_POST['username'] == $config['username'] && $_POST['password'] == $config['password'] && $_POST['otp'] == $config['otp']) {
    
    	 session_regenerate_id(true);
  
        // Genera un token di sessione unico
        $session_token = hash("sha256",($config['username'].$config['otp']));
        

        // Imposta il cookie con il session token (con scadenza di 1 ora)
        setcookie('session_token', $session_token, time() + 3600, '/', '', true, true); // Sicuro e accessibile solo tramite HTTP (flags Secure e HttpOnly)


        // Redirige l'utente alla pagina di amministrazione
        header('Location: admin.php');
        exit();
    } else {
        $error = 'Wrong credentials!';
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login</title>
    <link rel="stylesheet" href="style.css">
</head>
<body>
    <p class="red-bold">THIS APPLICATION IS VERY INSECURE and has been created only for testing purpose</p>
    <p>

    <h2>Login</h2>
    
    <?php if (isset($error)) : ?>
        <div style="color: red;"><?php echo $error; ?></div>
    <?php endif; ?>
    
    <form action="index.php" method="POST">
        <label for="username">Username:</label>
        <input type="text" id="username" name="username" required><br><br>
        
        <label for="password">Password:</label>
        <input type="password" id="password" name="password" required><br><br>
        
         <label for="otp">OTP:</label>
        <input type="password" id="otp" name="otp" maxlength="6" required><br><br>
        
        <input type="submit" value="Login">
    </form>
    <div>&nbsp;&nbsp;Poorely coded by Zinzloun ¯\_(ツ)_/¯</div>
</body>
</html>
