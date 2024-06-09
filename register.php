<?php require("./layout/header.php");

if ($_POST) {
    $birthDate = new DateTime($_POST["birthDate"]);
    $today = new DateTime();
    $interval = $birthDate->diff($today);
    $age = $interval->y;

    if ($age < 16) {
        echo "<script>alert('Vous devez avoir au moins 16 ans pour vous inscrire.'); window.location.href='register.php';</script>";
    } elseif ($usersManager->emailExists($_POST["email"])) {
        echo "<script>alert('Cette adresse e-mail est déjà utilisée.'); window.location.href='login.php';</script>";
    } else {
        // Met à jour le mot de passe saisit avec une version encryptée
        $_POST["password"] = password_hash($_POST["password"], PASSWORD_DEFAULT);
        // Créé l'utilisateur en BDD
        $usersManager->create(new User($_POST));
        // Connecte l'utilisateur en mettant à jour la session
        $_SESSION["is_connected"] = $_POST["email"];
        // Redirection sur la page d'accueil
        echo "<script>window.location.href='index.php'</script>";
    }
}

?>
<h1 class="mt-2">Créer un compte utilisateur</h1>
<form method="post">
    <label for="email">Email</label>
    <input type="email" name="email" id="email" placeholder="Votre adresse e-mail" class="form-control" required>
    <label for="username">Nom d'utilisateur</label>
    <input type="text" name="username" id="username" placeholder="Votre nom d'utilisateur" class="form-control" required>
    <label for="firstname">Prénom</label>
    <input type="text" name="firstName" id="firstname" placeholder="Votre prénom" class="form-control" required>
    <label for="lastname">Nom</label>
    <input type="text" name="lastName" id="lastname" placeholder="Votre nom" class="form-control" required>
    <label for="password">Mot de passe</label>
    <input type="password" name="password" id="password" placeholder="Votre mot de passe" class="form-control" required minlength=6 maxlength=30>
    <label for="birthDate">Date de naissance</label>
    <input type="date" name="birthDate" id="birthDate" class="form-control" required>
    <input type="submit" value="Créer un compte" class="mt-2 btn btn-primary">
</form>
<a href="./login.php">Se connecter</a>
<?php
require("./layout/footer.php");
?>