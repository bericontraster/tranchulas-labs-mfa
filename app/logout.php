<?php
// logout.php

session_start();
session_destroy(); // Distrugge la sessione
header('Location: index.php'); // Redirect alla pagina di login
exit();
