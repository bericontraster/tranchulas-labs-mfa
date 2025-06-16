<?php
// admin.php


session_start();

// Verifica se il cookie session_token esiste
if (!isset($_COOKIE['session_token'])) {
    header('Location: index.php'); // Reindirizza alla pagina di login
    exit();
}

$config = require 'login-config.php';

// VERIFICA TOKEN
$session_token = $_COOKIE['session_token'];
$expected_token = hash("sha256",($config['username'].$config['otp']));

if ($session_token !== $expected_token) {
   
    header('Location: logout.php'); // Reindirizza alla pagina di login
    exit();
}


?>

<!DOCTYPE html>
<html lang="it">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Amministrazione</title>
    <link rel="stylesheet" href="style.css">
</head>
<body>
    <h2>Welcome to the administrative area</h2>
    <p>You have successfully authenticated</p>
    <pre title="Free Palestine">
⬛⬛⬛⬛⬛⬛⬛⬛⬛⬛⬛⬛⬛
⬛⬛⬛⬛⬛⬛⬛⬛⬛⬛⬛⬛⬛
⬛⬛⬛⬛⬛⬛⬛⬛⬛⬛⬛⬛⬛
⬛⬛⬛⬛⬛⬛⬛⬛⬛⬛⬛⬛⬛
⬛⬛⬛⬛⬛⬛⬛⬛⬛⬛⬛⬛⬛
⬛⬛⬛⬛⬛⬛⬛⬛⬛⬛⬛⬛⬛
⬛⬛⬛⬛⬛⬛⬛⬛⬛⬛⬛⬛⬛
⬛⬛⬛⬛⬛⬛⬛⬛⬛⬛⬛⬛⬛
⬛⬛⬛⬛⬛⬛⬛⬛⬛⬛⬛⬛⬛
</pre>    
    <a href="logout.php">Logout</a>
        <div>&nbsp;&nbsp;Tranchulas Labs</div>
</body>
</html>
