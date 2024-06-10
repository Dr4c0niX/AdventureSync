<?php
require("./layout/header.php");

    $articlesManager = new ArticlesManager();
    $articles = $articlesManager->getAll();
    $loggedInUser = $usersManager->getLoggedInUser();
?>

<div>
    <form action="article.php" method="get">
        <label for="country">Trier par pays</label>
        <select name="country" id="country" required>
            <?php
            $countries = json_decode(file_get_contents('countries.json'), true);
            foreach ($countries as $country) {
                echo "<option value=\"$country\">$country</option>";
            }
            ?>
        </select>
        <input type="submit" value="Trier">
        <a href="article.php" class="form-button">Réinitialiser</a>
    </form>
    <?php
        $country = $_GET['country'] ?? null;
        $articles = $articlesManager->getAll();
        if ($country) {
            $articles = array_filter($articles, function($article) use ($country) {
                return $article->getCountry() === $country;
            });
        }
    ?>
    <?php foreach ($articles as $article): ?>
        <div class="trip-card">
            <h1><?= $article->getTitle() ?></h1>
            <?php if ($article->getImage()): ?>
                <img src="images/upload/<?= $article->getImage() ?>" alt="image <?= $article->getTitle() ?>" class="trip-image">
            <?php endif; ?>
            <p><?= $article->getDescription() ?></p>
            <h3>Adresse: <?= $article->getAddress() ?></h3>
            <h3>Pays: <?= $article->getCountry() ?></h3>
            <h3>Date de début/fin : <?= $article->getStartDate() ?> - <?= $article->getEndDate() ?></h3>
            <?php
                $creator = $usersManager->getById($article->getUserId());
            ?>
            <h3>Créateur : <?= $creator->getFirstName() . ' ' . $creator->getLastName() ?></h3>
            <?php if ($loggedInUser && $loggedInUser->getId() === $creator->getId()): ?>
                <div class="button-container">
                    <a href="edit-article.php?id=<?= $article->getId() ?>" class="form-button">Éditer</a>
                </div>
                <form action="delete-article.php" method="post" onsubmit="return confirm('Êtes-vous sûr de vouloir supprimer cet article ?');">
                    <input type="hidden" name="id" value="<?= $article->getId() ?>">
                    <input type="submit" value="Supprimer" class="form-button">
                </form>
            <?php endif; ?>
        </div>
    <?php endforeach; ?>    
</div>