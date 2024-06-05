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
<!--    <nav class="navbar">
            <ul class="nav-links">
                <li><a href="#home">Accueil</a></li>
                <li><a href="#services">Services</a></li>
                <li><a href="#about">À propos</a></li>
            </ul>
            <div class="logo">
                <img src="./images/logo/adventuresync-high-resolution-logo-transparent.png" alt="AdventureSync Logo">
            </div>
            <ul class="nav-links">
                <li><a href="#blog">Blog</a></li>
                <li><a href="#contact">Contact</a></li>
                <li><a href="#signup" class="signup">Inscription</a></li>
            </ul>
        </nav>-->        
        <nav class="navbar navbar-expand-lg bg-body-tertiary">
            <div class="container-fluid">
                <a class="navbar-brand" href="./index.php">Mon super site !</a>
                <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav" aria-controls="navbarNav" aria-expanded="false" aria-label="Toggle navigation">
                    <span class="navbar-toggler-icon"></span>
                </button>
                <div class="collapse navbar-collapse" id="navbarNav">
                    <ul class="navbar-nav">
                        <li class="nav-item">
                            <a class="nav-link" href="./index.php">Accueil</a>
                        </li>
                        <li>
                            <a class="nav-link" href="./trip.php">Voyages</a>
                        </li>
                        <?php if ($_SESSION && $_SESSION["is_connected"]) : ?>
                            <li class="nav-item">
                                <a class="nav-link" href="./create-trip.php">Ajouter un voyage</a>
                            </li>
                            <li>
                                <a class="nav-link" href="./create-article.php">Ajouter un article</a>
                            </li>
                        <?php endif ?>
                        <?php if ($_SESSION && $_SESSION["is_connected"]) : ?>
                            <li class="nav-item">
                                <a class="nav-link" href="./logout.php">Se déconnecter</a>
                            </li>
                            <li>
                                <a class="nav-link" href="./edit-profile.php?email=<?= $_SESSION["is_connected"] ?>">Modifier votre profil</a> <!-- lien de la page à mettre avec l'id de user -->
                            </li>
                        <?php else : ?>
                            <li class="nav-item">
                                <a class="nav-link" href="./login.php">Se connecter</a>
                            </li>
                            <li class="nav-item">
                                <a class="nav-link" href="./register.php">Créer un compte</a>
                            </li>
                        <?php endif ?>
                    </ul>
                </div>
            </div>
        </nav>
    </header>
    <main class="container">