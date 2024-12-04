<?php
// admin.php

session_start();

// Verifica se il cookie session_token esiste
if (!isset($_COOKIE['session_token'])) {
    header('Location: index.php'); // Reindirizza alla pagina di login
    exit();
}

// Ottieni il session token dal cookie
$session_token = $_COOKIE['session_token'];
$expected_token = session_id() . "--very-insecure-fixed-VALUE--DO-NOT-USE-IT-NEVER-IN-REAL-APPLICATION";

if ($session_token !== $expected_token) {
    // Se il token non corrisponde al valore atteso, termina la sessione e reindirizza
    session_destroy();
    setcookie('session_token', '', time() - 3600, '/', '', true, true); // Elimina il cookie
    header('Location: index.php'); // Reindirizza alla pagina di login
    exit();
}

// Verifica che il token sia valido e non sia scaduto (opzionale, ma consigliato)
if (time() > $_SESSION['session_token_expiry']) {
    // Se il token è scaduto, distruggi la sessione
    session_destroy();
    setcookie('session_token', '', time() - 3600, '/', '', true, true); // Elimina il cookie
    header('Location: index.php'); // Reindirizza alla pagina di login
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
    <h2>Benvenuto nell'area amministrativa</h2>
    <p>Sei autenticato con successo!</p>
    
    <a href="logout.php">Esci</a>
</body>
</html>
