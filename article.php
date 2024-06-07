<?php
require("./layout/header.php");

    $articlesManager = new ArticlesManager();
    $articles = $articlesManager->getAll();
    $loggedInUser = $usersManager->getLoggedInUser();
?>

<div>
    <?php foreach ($articles as $article): ?>
        <div class="trip-card">
            <h1><?= $article->getTitle() ?></h1>
            <?php if ($article->getImage()): ?>
                <img src="images/upload/<?= $article->getImage() ?>" alt="image <?= $article->getTitle() ?>" class="trip-image">
            <?php endif; ?>
            <p><?= $article->getDescription() ?></p>
            <h3>Destination: <?= $article->getDestination() ?></h3>
            <h3>Date de début: <?= $article->getStartDate() ?></h3>
            <h3>Date de fin: <?= $article->getEndDate() ?></h3>
            <?php
                $creator = $usersManager->getById($article->getUserId());
            ?>
            <h2>Créateur : <?= $creator->getFirstName() . ' ' . $creator->getLastName() ?></h2>
            <?php if ($loggedInUser && $loggedInUser->getId() === $creator->getId()): ?>
                <a href="edit-article.php?id=<?= $article->getId() ?>">Éditer</a>
                <form action="delete-article.php" method="post" onsubmit="return confirm('Êtes-vous sûr de vouloir supprimer cet article ?');">
                    <input type="hidden" name="id" value="<?= $article->getId() ?>">
                    <input type="submit" value="Supprimer">
                </form>
            <?php endif; ?>
        </div>
    <?php endforeach; ?>    
</div>