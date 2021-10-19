<?php
    session_start(); //Initialise la session
    session_unset(); //Désactive la session
    session_destroy(); //Détruit la session

    setcookie('log', '', time() - 3444, '/', null, false, true);//Détruit le coockie

    header('location: ./');//Redirection vers acceuil non connecté
?>

