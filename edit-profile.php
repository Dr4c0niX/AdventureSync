<?php
    require("./layout/header.php");

    if (!isset($_SESSION["is_connected"])) {
        echo "<script>alert('Vous devez vous connecter pour accéder à cette page.'); window.location.href='./login.php';</script>";
        exit;
    }

    $usersManager = new UsersManager();
    $user = $usersManager->getByEmail($_SESSION["is_connected"]);

    if ($_POST) {
        // Vérifiez l'ancien mot de passe
        if (!password_verify($_POST["oldPassword"], $user->getPassword())) {
            echo "<script>alert('Ancien mot de passe incorrect.');</script>";
        } elseif ($_POST["newPassword"] !== $_POST["confirmPassword"]) {
            echo "<script>alert('Les nouveaux mots de passe ne correspondent pas.');</script>";
        } else {
            // Mettez à jour le profil de l'utilisateur
            $user->setEmail($_POST["email"]);
            $user->setUsername($_POST["username"]);
            $user->setFirstName($_POST["firstName"]);
            $user->setLastName($_POST["lastName"]);
            $user->setBirthDate($_POST["birthDate"]);
            $user->setPassword(password_hash($_POST["newPassword"], PASSWORD_DEFAULT));
            $usersManager->update($user);
            echo "<script>alert('Profil mis à jour avec succès.'); window.location.href='index.php';</script>";
        }
    }
?>

<div>
    <h1>Modifier votre profil</h1>
    <form method="post">
        <label for="email">Email</label>
        <input type="email" name="email" id="email" placeholder="Votre adresse e-mail" class="form-control" required value="<?php echo $user->getEmail(); ?>">
        <label for="username">Pseudo</label>
        <input type="text" name="username" id="username" placeholder="Votre pseudo" class="form-control" required value="<?php echo $user->getUsername(); ?>">
        <label for="firstName">Prénom</label>
        <input type="text" name="firstName" id="firstName" placeholder="Votre prénom" class="form-control" required value="<?php echo $user->getFirstName(); ?>">
        <label for="lastName">Nom</label>
        <input type="text" name="lastName" id="lastName" placeholder="Votre nom" class="form-control" required value="<?php echo $user->getLastName(); ?>">
        <label for="birthDate">Date de naissance</label>
        <input type="date" name="birthDate" id="birthDate" class="form-control" required value="<?php echo $user->getBirthDate(); ?>">
        <label for="oldPassword">Ancien mot de passe</label>
        <input type="password" name="oldPassword" id="oldPassword" class="form-control" required>
        <label for="newPassword">Nouveau mot de passe</label>
        <input type="password" name="newPassword" id="newPassword" class="form-control" required minlength=6 maxlength=30>
        <label for="confirmPassword">Confirmer le nouveau mot de passe</label>
        <input type="password" name="confirmPassword" id="confirmPassword" class="form-control" required minlength=6 maxlength=30>
        <input type="submit" value="Modifier votre profil" class="mt-2 btn btn-primary">
        <a href="./deleteUser.php">Supprimer votre compte</a>
    </form>
</div>