<?php
// index.php

session_start();

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    // Carica le credenziali dal file
    $config = require 'login-config.php';

    // Verifica se le credenziali sono corrette
    if ($_POST['username'] == $config['username'] && $_POST['password'] == $config['password']) {
        // Genera un token di sessione unico
        $session_token = session_id() . "--very-insecure-fixed-VALUE--DO-NOT-USE-IT-NEVER-IN-REAL-APPLICATION";

        // Imposta il cookie con il session token (con scadenza di 1 ora)
        setcookie('session_token', $session_token, time() + 3600, '/', '', true, true); // Sicuro e accessibile solo tramite HTTP (flags Secure e HttpOnly)

        // Imposta anche un tempo di scadenza per la sessione nel server (opzionale)
        $_SESSION['session_token_expiry'] = time() + 3600;  // Scadenza di 1 ora

        // Redirige l'utente alla pagina di amministrazione
        header('Location: admin.php');
        exit();
    } else {
        $error = 'Credenziali errate!';
    }
}
?>

<!DOCTYPE html>
<html lang="it">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login</title>
    <link rel="stylesheet" href="style.css">
</head>
<body>
    <h2>Login</h2>
    
    <?php if (isset($error)) : ?>
        <div style="color: red;"><?php echo $error; ?></div>
    <?php endif; ?>
    
    <form action="index.php" method="POST">
        <label for="username">Nome utente:</label>
        <input type="text" id="username" name="username" required><br><br>
        
        <label for="password">Password:</label>
        <input type="password" id="password" name="password" required><br><br>
        
        <input type="submit" value="Accedi">
    </form>
</body>
</html>
