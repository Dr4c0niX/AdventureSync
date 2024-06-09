<?php
require("./layout/header.php");

if (!isset($_SESSION["is_connected"])) 
{
    echo "<script>alert('Vous devez vous connecter pour accéder à cette page.'); window.location.href='./login.php';</script>";
    exit;
}

$articlesManager = new ArticlesManager();

if ($_POST && $_SESSION && $_SESSION["is_connected"]) 
{
    $startDate = new DateTime($_POST["startDate"]);
    $endDate = new DateTime($_POST["endDate"]);
    
    if ($endDate < $startDate) {
        echo "<script>alert('La date de fin ne peut pas être inférieure à la date de début.'); window.location.href='create-articlee.php';</script>";
        exit;
    }

    $file_name = '';
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
                echo "<script>alert('{$error}'); window.location.href='create-article.php'</script>";
            }
            exit;
        }

        move_uploaded_file($file_tmp, "images/upload/" . $file_name);
    }

    $_POST['image'] = $file_name;
    $articlesManager->create(new Article($_POST));
    echo "<script>alert('Bravo ! Vous venez de créer votre article');window.location.href='index.php'</script>";
}

?>

<div>
    <h1>Créer un article</h1>
    <form method="post" enctype="multipart/form-data">
        <label for="title">Titre</label>
        <input type="text" name="title" id="title" placeholder="Titre de l'article" class="form-control" required>
        <label for="description">Description</label>
        <textarea name="description" id="description" placeholder="Description de l'article" class="form-control" required></textarea>
        <label for="address">Adresse</label>
        <input type="text" name="address" id="address" placeholder="Adresse de l'article" class="form-control" required>
        <label for="country">Pays:</label>
        <select name="country" id="country" class="form-control" required></select>
        <label for="startDate">Date de début</label>
        <input type="date" name="startDate" id="startDate" class="form-control" required>
        <label for="endDate">Date de fin</label>
        <input type="date" name="endDate" id="endDate" class="form-control" required>
        <label for="image">Photos de votre voyage</label>
        <input type="file" name="image" id="image" class="form-control">
        <input type="submit" value="Créer un article" class="mt-2 btn btn-primary">
    </form>
</div>