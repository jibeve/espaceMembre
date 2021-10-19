<?php
    session_start(); //initialise la session
    session_unset(); //Désactive la session
    session_destroy(); //détruit la session

    setcookie('log', '', time() - 3444, '/', null, false, true);//détruit le coockie

    header('location: ./');//redirection vers acceuil non connecté
?>

