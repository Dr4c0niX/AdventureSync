<?php require("./layout/header.php");

if ($_POST) {
    $birthDate = new DateTime($_POST["birthDate"]); //vérification de l'âge minimum de 16 ans
    $today = new DateTime();
    $interval = $birthDate->diff($today);
    $age = $interval->y;

    if ($age < 16) {
        echo "<script>alert('Vous devez avoir au moins 16 ans pour vous inscrire.'); window.location.href='register.php';</script>";
    } elseif ($usersManager->emailExists($_POST["email"])) {
        echo "<script>alert('Cette adresse e-mail est déjà utilisée.'); window.location.href='login.php';</script>";
    } else {
        $_POST["password"] = password_hash($_POST["password"], PASSWORD_DEFAULT); // Met à jour le mot de passe saisit avec une version encryptée
        $usersManager->create(new User($_POST));
        $_SESSION["is_connected"] = $_POST["email"];
        echo "<script>window.location.href='index.php'</script>"; //redirection sur la page d'accueil
    }
}

?>
<h1>Créer un compte utilisateur</h1> <!-- Formulaire pour créer un compte-->
<form method="post">
    <label for="email">Email</label>
    <input type="email" name="email" id="email" placeholder="Votre adresse e-mail" required>
    <label for="username">Nom d'utilisateur</label>
    <input type="text" name="username" id="username" placeholder="Votre nom d'utilisateur"  required>
    <label for="firstname">Prénom</label>
    <input type="text" name="firstName" id="firstname" placeholder="Votre prénom" required>
    <label for="lastname">Nom</label>
    <input type="text" name="lastName" id="lastname" placeholder="Votre nom" required>
    <label for="password">Mot de passe</label>
    <input type="password" name="password" id="password" placeholder="Votre mot de passe" required minlength=6 maxlength=30>
    <label for="birthDate">Date de naissance</label>
    <input type="date" name="birthDate" id="birthDate" required>
    <input type="submit" value="Créer un compte">
</form>
<a href="./login.php">Se connecter</a>