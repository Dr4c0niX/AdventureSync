<?php
require("./layout/header.php");

if (!isset($_SESSION["is_connected"])) {
    echo "<script>alert('Vous devez vous connecter pour accéder à cette page.'); window.location.href='./login.php';</script>";
    exit;
}

$articlesManager = new ArticlesManager();

if ($_POST && $_SESSION && $_SESSION["is_connected"]) {
    $articlesManager->create(new Article($_POST));
    echo "<script>alert('Bravo ! Vous venez de créer votre article');window.location.href='index.php'</script>";
}

?>

<div>
    <h1>Créer un article</h1>
    <form method="post">
        <label for="title">Titre</label>
        <input type="text" name="title" id="title" placeholder="Titre de l'article" class="form-control" required>
        <label for="description">Description</label>
        <textarea name="description" id="description" placeholder="Description de l'article" class="form-control" required></textarea>
        <label for="destination">Destination</label>
        <input type="text" name="destination" id="destination" placeholder="Destination de l'article" class="form-control" required>
        <label for="startDate">Date de début</label>
        <input type="date" name="startDate" id="startDate" class="form-control" required>
        <label for="endDate">Date de fin</label>
        <input type="date" name="endDate" id="endDate" class="form-control" required>
        <label for="image">Photos de votre voyage</label>
        <input type="file" name="image" id="image" class="form-control">
        <input type="submit" value="Créer un article" class="mt-2 btn btn-primary">
    </form>
</div>