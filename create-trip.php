<?php
require("./layout/header.php");

if (!isset($_SESSION["is_connected"])) {
    echo "<script>alert('Vous devez vous connecter pour accéder à cette page.'); window.location.href='./login.php';</script>";
    exit;
}

$tripsManager = new TripsManager(); 

if ($_POST && $_SESSION && $_SESSION["is_connected"]) {
    if (isset($_FILES['image'])) {
        $errors = array();
        $file_name = $_FILES['image']['name'];
        $file_size = $_FILES['image']['size'];
        $file_tmp = $_FILES['image']['tmp_name'];
        $file_type = $_FILES['image']['type'];
        $file_parts = explode('.', $_FILES['image']['name']);
        $file_ext = strtolower(end($file_parts));
        $extensions = array("jpeg", "jpg", "png");

        if (in_array($file_ext, $extensions) === false) {
            $errors[] = "extension not allowed, please choose a JPEG or PNG file.";
        }

        if ($file_size > 2097152) {
            $errors[] = 'File size must be exactly 2 MB';
        }

        if (empty($errors) == true) {
            move_uploaded_file($file_tmp, "images/upload/" . $file_name);
            echo "Success";
        } else {
            print_r($errors);
        }
    }

    $_POST['image'] = $file_name;
    $tripsManager->create(new Trip($_POST));
    echo "<script>alert('Bravo ! Vous venez de créer votre voyage');window.location.href='index.php'</script>";
}

?>
<div>
    <h1>Créer un article</h1>
    <form method="post" enctype="multipart/form-data">
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
        <label for="collaborative">Collaboratif</label>
        <input type="checkbox" name="collaborative" id="collaborative" class="form-control">
        <label for="private">Privé</label>
        <input type="checkbox" name="private" id="private" class="form-control">
        <label for="countOfPerson">Nombre de personnes</label>
        <input type="number" name="countOfPerson" id="countOfPerson" min="1" class="form-control" required>
        <label for="image">Photos de votre voyage</label>
        <input type="file" name="image" id="image" class="form-control">
        <input type="submit" value="Créer un voyage" class="mt-2 btn btn-primary">
    </form>
</div>