

<?php

session_start();


require('./src/connection.php');
//Inscription

if(!empty($_POST['pseudo']) && !empty($_POST['email']) && !empty($_POST['password']) && !empty($_POST['password_confirm'])){

    //variable
    $pseudo = $_POST['pseudo'];
    $email = $_POST['email'];
    $password = $_POST['password'];
    $pass_confirm = $_POST['password_confirm'];

    //Test if password = password_confirm
    if($password != $password_confirm){ 
        //redirect 
        header('location: ./?error=1&pass=1');
    };

    //Test if email already exist in db
    $req = $db->prepare("SELECT count(*) as numberEmail FROM users where email = ?");
    $req->execute(array($email));

    while($email_verif = $req->fetch()){
        if($email_verif['numberEmail'] !=0){
            header('location: ./?error=1&email=1');
        }
    }


    //password encryption
    $password = "abq3".sha1($password."1245")."86754";

    //Password Hash
    $secret = sha1($email).time();
    $secret = sha1($hash).time().time();

    //Send request to db

    $req = $db->prepare("INSERT INTO users(pseudo, email, password, secretKey) VALUES (?, ?, ?, ?)");
    $req->execute(array($pseudo, $email, $password, $secret));

    header('location: ./?success=1');    
}

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" type="text/css" href="design/default.css">
    <title>Inscription Espace Membre</title>
</head>
<body>
    <header>
        <h1>Inscription</h1>
    </header>

    <div class="container">

        <?php 
        if(!isset($_SESSION['connect'])){ ?>

        <p id="info" >Bienvenue</p>
        <p>Déjà inscrit ! <a href="connection.php">Connectez-vous</a></p>

        <?php 
        if(isset($_GET['error'])){

            if(isset($_GET['pass'])){
                echo '<p id="error">Les mots de passes ne correspondent pas </p>';
            }
            else if(isset($_GET['email'])){
                echo '<p id="error">L\'email est déjà utilisé</p>';
            }
        }
        else if (isset($_GET['success'])){
            echo '<p id="success"> Inscription prise en compte</p>';
        }
        ?>
    

        <form method="post" action="index.php">
            <p>
                <table>
                    <tr>
                        <td>Pseudo</td>
                        <td><input type="text" name="pseudo" placeholder="Ex : Murielle" required></td>
                    </tr>
                    <tr>
                        <td>Email</td>
                        <td><input type="email" name="email" placeholder="Ex : example@google.com" required></td>
                    </tr>
                    <tr>
                        <td>Mot de passe</td>
                        <td><input type="password" name="password" placeholder="Ex : ******" required></td>
                    </tr>
                    <tr>
                        <td>Confirmation de votre mot de passe</td>
                        <td><input type="password" name="password_confirm" placeholder="Ex : ******" required></td>
                    </tr>
                </table>
                <div id="button">
                    <button  type="submit">S'inscrire</button>
                </div>
            </p>
        </form>
    </div>
    <?php } else {?>

        <p id="info">Bonjour <?= $_SESSION['pseudo'] ?><br>
        <a href="disconnection.php">Se déconnecter</a>
    </p>
        <?php } ?> 
</body>
</html>