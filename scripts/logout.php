<?php
session_start();

// Détruire toutes les variables de session
$_SESSION = array();

// Détruire la session
session_destroy();

// Rediriger vers la page de connexion (ou toute autre page de votre choix)
header('Location: https://cdc.minesmada.org/index.php');
exit;
