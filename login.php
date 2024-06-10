<?php require("./layout/header.php");
?>
<h1>Connexion utilisateur</h1>
<form method="post">
    <label for="email">Email</label>
    <input type="email" name="email" id="email" placeholder="Votre adresse e-mail"  required>
    <label for="password">Mot de passe</label>
    <input type="password" name="password" id="password" placeholder="Votre mot de passe" required minlength=6 maxlength=30>
    <input type="submit" value="Se connecter">
</form> 
<div class="button-container">
    <a href="./register.php">Créer un compte utilisateur</a>
</div>
<?php

if ($_POST) {
    // Récupère l'objet utilisateur complet en fonction de l'e-mail saisit
    $user = $usersManager->getByEmail($_POST["email"]);
  
    if ($user && password_verify($_POST["password"], $user->getPassword())) {
        // Le connecte en mettant à jour la session
        $_SESSION["is_connected"] = $_POST["email"];
        // Redirection sur la page d'accueil
        echo "<script>window.location.href='index.php'</script>";
    } else {
        echo '<p>Email ou mot de passe incorrect</p>'; 
    }
}
?>