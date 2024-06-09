<?php
    require("./layout/header.php");

    if (!isset($_SESSION["is_connected"])) {
        echo "<script>alert('Vous devez vous connecter pour accéder à cette page.'); window.location.href='./login.php';</script>";
        exit;
    }

    $usersManager = new UsersManager();
    $user = $usersManager->getByEmail($_SESSION["is_connected"]);
    $articlesManager = new ArticlesManager();
    $tripsManager = new TripsManager();

    if ($_POST) {
        if (!empty($_POST["oldPassword"]) && !empty($_POST["newPassword"]) && !empty($_POST["confirmPassword"])) {
            // Vérifiez l'ancien mot de passe
            if (!password_verify($_POST["oldPassword"], $user->getPassword())) {
                echo "<script>alert('Ancien mot de passe incorrect.'); window.location.href='edit-profile.php';</script>";
                exit;
            } elseif ($_POST["newPassword"] !== $_POST["confirmPassword"]) {
                echo "<script>alert('Les nouveaux mots de passe ne correspondent pas.');window.location.href='edit-profile.php';</script>";
                exit;
            } else {
                // Mettez à jour le mot de passe
                $user->setPassword(password_hash($_POST["newPassword"], PASSWORD_DEFAULT));
            }
        }
        
        $submittedBirthDate = date("Y-m-d", strtotime($_POST["birthDate"]));
        $userBirthDate = date("Y-m-d", strtotime($user->getBirthDate()));
    
        if ($submittedBirthDate != $userBirthDate) {
            echo "<script>alert('Vous ne pouvez pas modifier votre date de naissance.'); window.location.href='edit-profile.php';</script>";
            exit;
        } else {
            // Mettez à jour le profil de l'utilisateur
            $user->setEmail($_POST["email"]);
            $user->setUsername($_POST["username"]);
            $user->setFirstName($_POST["firstName"]);
            $user->setLastName($_POST["lastName"]);
            $usersManager->update($user);
            echo "<script>alert('Profil mis à jour avec succès.'); window.location.href='index.php';</script>";
        }
    }
?>

<div>
    <h1>Modifier votre profil</h1>
    <form method="post">
        <label for="email">Email</label>
        <input type="email" name="email" id="email" placeholder="Votre adresse e-mail" required value="<?php echo $user->getEmail(); ?>"><br>
        <label for="username">Pseudo</label>
        <input type="text" name="username" id="username" placeholder="Votre pseudo" required value="<?php echo $user->getUsername(); ?>"><br>
        <label for="firstName">Prénom</label>
        <input type="text" name="firstName" id="firstName" placeholder="Votre prénom" required value="<?php echo $user->getFirstName(); ?>"><br>
        <label for="lastName">Nom</label>
        <input type="text" name="lastName" id="lastName" placeholder="Votre nom" required value="<?php echo $user->getLastName(); ?>"><br>
        <label for="birthDate">Date de naissance</label>
        <input type="date" name="birthDate" id="birthDate" class="edit-profile-birth-date" readonly value="<?php echo $user->getBirthDate(); ?>"><br> <!-- readonly pour empêcher la modification de la case-->
        <label for="oldPassword">Ancien mot de passe</label>
        <input type="password" name="oldPassword" id="oldPassword"><br>
        <label for="newPassword">Nouveau mot de passe</label>
        <input type="password" name="newPassword" id="newPassword" minlength=6 maxlength=30><br>
        <label for="confirmPassword">Confirmer le nouveau mot de passe</label>
        <input type="password" name="confirmPassword" id="confirmPassword" minlength=6 maxlength=30><br>
        <input type="submit" value="Modifier votre profil">
        <a href="./delete-user.php">Supprimer votre compte</a>
    </form>
</div>
<div>
    <?php
        $articles = $articlesManager->getAllArticlesByUserId($user->getId());
        $trips = $tripsManager->getAllTripsByUserId($user->getId());  
    ?>

    <!-- Affichez les articles -->
    <h2>Vos articles</h2>
    <?php foreach ($articles as $article): ?>
    <div>
        <h3><?php echo $article->getTitle(); ?></h3>
        <p><?php echo $article->getDescription(); ?></p>
        <a href="edit-article.php?id=<?= $article->getId() ?>">Éditer</a>
        <form action="delete-article.php" method="post" onsubmit="return confirm('Êtes-vous sûr de vouloir supprimer cet article ?');">
            <input type="hidden" name="id" value="<?= $article->getId() ?>">
            <input type="submit" value="Supprimer">
        </form>
    </div>
<?php endforeach; ?>

<!-- Affichez les voyages -->
<h2>Vos voyages</h2>
<?php foreach ($trips as $trip): ?>
    <div>
        <h3><?php echo $trip->getTitle(); ?></h3>
        <p><?php echo $trip->getDescription(); ?></p>
        <a href="edit-trip.php?id=<?= $trip->getId() ?>">Éditer</a>
        <form action="delete-trip.php" method="post" onsubmit="return confirm('Êtes-vous sûr de vouloir supprimer ce voyage ?');">
            <input type="hidden" name="id" value="<?= $trip->getId() ?>">
            <input type="submit" value="Supprimer">
        </form>
    </div>
<?php endforeach; ?>