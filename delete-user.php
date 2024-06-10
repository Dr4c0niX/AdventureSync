<?php
    require("./layout/header.php");

    if (!isset($_SESSION["is_connected"])) {
        echo "<script>alert('Vous devez vous connecter pour accéder à cette page.'); window.location.href='./login.php';</script>";
        exit;
    }

    if ($_SERVER["REQUEST_METHOD"] == "POST") {
        $usersManager = new UsersManager();
        $user = $usersManager->getByEmail($_SESSION["is_connected"]);

        if (password_verify($_POST["password"], $user->getPassword())) {
            $usersManager->delete($user);
            session_destroy();
            echo "<script>alert('Votre compte a été supprimé.'); window.location.href='./login.php';</script>";
            exit;
        } else {
            echo "<script>alert('Mot de passe incorrect.');</script>";
        }
    }
?>

<div>
    <h1>Supprimer votre compte</h1>
    <form method="post">
        <label for="password">Mot de passe</label>
        <input type="password" name="password" id="password" required>
        <input type="submit" value="Supprimer votre compte" onclick="return confirm('Êtes-vous sûr de vouloir supprimer votre compte ?');">
    </form>
</div>