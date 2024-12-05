<?php
// logout.php

session_start();
session_destroy(); // Distrugge la sessione
//faccio scadere i cookie di sessione
setcookie(session_name(), '', time() - 3600, '/');
header('Location: index.php'); // Redirect alla pagina di login
exit();
