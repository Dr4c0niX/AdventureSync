<?php
    session_start(); // Initialise la session pour donner accès à `$_SESSION`
?>

<!DOCTYPE html>
<html lang="fr-FR">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>AdventureSync</title>
    <link rel="shortcut icon" href="./images/favicon/adventuresync-favicon-color.png" type="image/x-icon">
    <link rel="stylesheet" href="./style.css">
</head>

<body>
    <?php
    spl_autoload_register(function (string $class) {
        if (str_contains($class, "Manager")) {
            require("./managers/$class.php");
        } else {
            require("./models/$class.php");
        }
    });

    $usersManager = new UsersManager();
    ?>
    <header>    
        <nav class="nav-bar">
            <div>
                <ul class="nav-links">
                    <li class="logo">
                        <a href="index.php"><img src="./images/logo/logo.png" alt="logo du site"></a>
                    </li>
                    <li>
                        <a href="./trip.php">Voyages</a>
                    </li>
                    <li>
                        <a href="./article.php">Articles</a>
                    </li>
                    <?php if ($_SESSION && $_SESSION["is_connected"]) : ?>
                        <li>
                            <a href="./create-trip.php">Ajouter un voyage</a>
                        </li>
                        <li>
                            <a href="./create-article.php">Ajouter un article</a>
                        </li>
                    <?php endif ?>
                    <?php if ($_SESSION && $_SESSION["is_connected"]) : ?>
                        <li>
                            <a href="./logout.php">Se déconnecter</a>
                        </li>
                        <li>
                            <a href="./edit-profile.php?email=<?= $_SESSION["is_connected"] ?>">Modifier mon profil</a> <!-- lien de la page à mettre avec l'id de user -->
                        </li>
                        <?php if ($usersManager->getLoggedInUser()->isAdmin()) : ?>
                            <li>
                                <a href="./admin.php">Admin</a>
                            </li>
                        <?php endif ?>
                    <?php else : ?>
                        <li>
                            <a href="./login.php">Se connecter  </a>
                        </li>
                        <li>
                            <a href="./register.php">Créer un compte</a>
                        </li>
                    <?php endif ?>
                </ul>
            </div>
        </nav>
    </header>
    <main>

    <script src="script.js"></script>