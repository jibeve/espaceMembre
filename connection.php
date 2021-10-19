<?php

session_start();

if(isset($_SESSION['connect'])){
    header('location: ./');
}

require('src/connection.php');


if(!empty($_POST['email']) && !empty($_POST['password'])){

    //variables
    $email = $_POST['email'];
    $password = $_POST['password'];
    $error = 1;
    //password encryption
    $password = "abq3".sha1($password."1245")."86754";

    $req = $db->prepare('SELECT * FROM users WHERE email = ?');
    $req->execute(array($email));

    while($user = $req->fetch()){
        if($password == $user['password']){
            $error = 0;
            $_SESSION['connect'] = 1;
            $_SESSION['pseudo'] = $user['pseudo'];

            //Remind me checkbox
            if(isset($_POST['connect'])){ 
                //Create coockie with name "log" and the secret key of the user
                // 365 days * 24 hours * 3600 seconds => number of seconds in one year.
                setcookie('log', $user['secret'], time() + 365*24*3600, "/", null, false, true);
            }
            header('location: ./connection.php?success=1');
        }
    } 
    if($error == 1){
        header('location: ./connection.php?error=1');
    }
    

}

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" type="text/css" href="design/default.css">
    <title>Connexion</title>
</head>
<body>  

    <header>
        <h1>Connexion</h1>
    </header>

    <div class="container">
    <p id="info">Bienvenue</p>
    <p>Si vous n'êtes pas inscrit, <a href="index.php">Inscrivez-vous</a></p>

    <?php
        if(isset($_GET['error'])){
            echo '<p id="error">Vous avez entré un mauvais email ou mot de passe</p>';
        }
        else if(isset($_GET['success'])){
            echo '<p id="success">Vous êtes connectés</p>';
        }
    ?>

    

    <form method="post" action="connection.php">
        <p>
            <table>
                <tr>
                    <td>Email</td>
                    <td><input type="email" name="email" placeholder="Ex : example@google.com" required></td>
                </tr>
                <tr>
                    <td>Mot de passe</td>
                    <td><input type="password" name="password" placeholder="Ex : ******" required></td>
                </tr>
            </table>
            <p><label>
                <input type="checkbox" name="connect" checked>Connexion automatique
                </label>
            </p>
            <div id="button">
                <button type="submit">S'inscrire</button>
            </div>
            
        </p>
    </form>
    </div>
    
</body>
</html>