<?php
require("./layout/header.php");

$articlesManager = new ArticlesManager();
$usersManager = new UsersManager();
$article = null;

if ($_GET && isset($_GET["id"])) {
    $article = $articlesManager->getById($_GET["id"]); //AJOUTER POUR QUE SEUL CREATEUR SE CO
}

$loggedInUser = $usersManager->getLoggedInUser();

if (!$loggedInUser) {
    echo "<script>alert('Pourquoi voulez-vous modifier un article qui n\'est pas le vôtre ?'); window.location.href='article.php';</script>";
    exit;
}

if ($article && $article->getUserId() != $loggedInUser->getId()) {
    echo "<script>alert('Pourquoi voulez-vous modifier un article qui n\'est pas le vôtre ?'); window.location.href='article.php';</script>";
    exit;
}

if ($_POST) {
    $article->setTitle($_POST["title"]);
    $article->setDescription($_POST["description"]);
    $article->setDestination($_POST["destination"]);
    $article->setStartDate($_POST["startDate"]);
    $article->setEndDate($_POST["endDate"]);
    $articlesManager->update($article);
    echo "<script>alert('Article mis à jour avec succès.'); window.location.href='article.php';</script>";
}
?>

<form method="post">
    <label for="title">Titre :</label>
    <input type="text" id="title" name="title" value="<?= $article->getTitle() ?>" required>
    <label for="description">Description :</label>
    <textarea id="description" name="description" required><?= $article->getDescription() ?></textarea>
    <label for="destination">Destination :</label>
    <input type="text" id="destination" name="destination" value="<?= $article->getDestination() ?>" required>
    <label for="startDate">Date de début :</label>
    <input type="date" id="startDate" name="startDate" value="<?= $article->getStartDate() ?>" required>
    <label for="endDate">Date de fin :</label>
    <input type="date" id="endDate" name="endDate" value="<?= $article->getEndDate() ?>" required>
    <input type="submit" value="Mettre à jour">
</form>