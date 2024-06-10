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

if ($_SERVER["REQUEST_METHOD"] === "POST") {
    $startDate = new DateTime($_POST["startDate"]);
    $endDate = new DateTime($_POST["endDate"]);

    if ($endDate < $startDate) {
        echo "<script>alert('La date de fin ne peut pas être inférieure à la date de début.'); window.location.href='edit-article.php?id={$article->getId()}';</script>";
        exit;
    }

    if (isset($_FILES['image']) && $_FILES['image']['name'] != '') {
        $errors = array();
        $file_name = $_FILES['image']['name'];
        $file_size = $_FILES['image']['size'];
        $file_tmp = $_FILES['image']['tmp_name'];
        $file_type = $_FILES['image']['type'];
        $file_parts = explode('.', $_FILES['image']['name']);
        $file_ext = strtolower(end($file_parts));
        $extensions = array("jpeg", "jpg", "png", "webp");

        if (in_array($file_ext, $extensions) === false) {
            $errors[] = "extension not allowed, please choose a JPEG or PNG file.";
        }

        if ($file_size > 2097152) {
            $errors[] = 'File size must be exactly 2 MB';
        }

        if (!empty($errors)) {
            foreach($errors as $error) {
                echo "<script>alert('{$error}'); window.location.href='edit-article.php?id={$article->getId()}';</script>";
            }
            exit;
        }

        move_uploaded_file($file_tmp, "images/upload/" . $file_name);
        $article->setImage($file_name);
    }

    $article->setTitle($_POST["title"]);
    $article->setDescription($_POST["description"]);
    $article->setAddress($_POST["address"]);
    $article->setCountry($_POST["country"]);
    $article->setStartDate($_POST["startDate"]);
    $article->setEndDate($_POST["endDate"]);
    $articlesManager->update($article);
    echo "<script>alert('Article mis à jour avec succès.'); window.location.href='article.php';</script>";
}
?>

<form method="post" enctype="multipart/form-data">
    <label for="title">Titre :</label>
    <input type="text" id="title" name="title" value="<?= $article->getTitle() ?>" required>
    <label for="description">Description :</label>
    <textarea id="description" name="description" required><?= $article->getDescription() ?></textarea>
    <label for="address">Adresse :</label>
    <input type="text" id="address" name="address" value="<?= $article->getAddress() ?>" required>
    <label for="country">Pays :</label>
    <select name="country" id="country" required></select>
    <label for="startDate">Date de début :</label>
    <input type="date" id="startDate" name="startDate" value="<?= $article->getStartDate() ?>" required>
    <label for="endDate">Date de fin :</label>
    <input type="date" id="endDate" name="endDate" value="<?= $article->getEndDate() ?>" required>

    <label for="image">Image actuelle:</label>
    <?php if ($article->getImage()): ?>
        <img src="images/upload/<?= $article->getImage() ?>" alt="image <?= $article->getTitle() ?>">
    <?php else: ?>
        <p>Aucune image actuellement.</p>
    <?php endif; ?>
    <label for="image">Changer l'image:</label>
    <input type="file" id="image" name="image">
    <p>Si vous ne choisissez pas une nouvelle image, l'image actuelle restera en place.</p>

    <input type="submit" value="Mettre à jour">
</form>