<?php
require("./layout/header.php");

$tripsManager = new TripsManager();
$usersManager = new UsersManager();
$trip = null;

if ($_GET && isset($_GET["id"])) 
{
    $trip = $tripsManager->getById($_GET["id"]); //AJOUTER POUR QUE SEUL CREATEUR SE CO
}

$loggedInUser = $usersManager->getLoggedInUser();

if (!$loggedInUser) 
{
    echo "<script>alert('Pourquoi voulez-vous modifier un voyage qui n\'est pas le vôtre ?'); window.location.href='trip.php';</script>";
    exit;
}

if ($trip && $trip->getUserId() !== $loggedInUser->getId()) 
{
    echo "<script>alert('Pourquoi voulez-vous modifier un voyage qui n\'est pas le vôtre ?'); window.location.href='trip.php';</script>";
    exit;
}

if ($_SERVER["REQUEST_METHOD"] === "POST") 
{
    if (isset($_FILES['image']) && $_FILES['image']['error'] === UPLOAD_ERR_OK) {
        $uploadDirectory = "images/upload/";
        $filename = basename($_FILES['image']['name']);
        $targetPath = $uploadDirectory . $filename;

        move_uploaded_file($_FILES['image']['tmp_name'], $targetPath);

        $trip->setImage($filename);
    }
    $trip->setTitle($_POST["title"]);
    $trip->setDescription($_POST["description"]);
    $trip->setDestination($_POST["destination"]);
    $trip->setStartDate($_POST["startDate"]);
    $trip->setEndDate($_POST["endDate"]);
    $trip->setCollaborative(isset($_POST["collaborative"]));
    $trip->setPrivate(isset($_POST["private"]));
    $trip->setCountOfPerson($_POST["countOfPerson"]);
    $tripsManager->update($trip);
    echo "<script>alert('Voyage mis à jour avec succès.'); window.location.href='trip.php';</script>";
}
?>

<form method="post" enctype="multipart/form-data">
    <label for="title">Titre:</label>
    <input type="text" id="title" name="title" value="<?= $trip->getTitle() ?>" required>
    <label for="description">Description:</label>
    <textarea id="description" name="description" required><?= $trip->getDescription() ?></textarea>
    <label for="destination">Destination:</label>
    <input type="text" id="destination" name="destination" value="<?= $trip->getDestination() ?>" required>
    <label for="startDate">Start Date:</label>
    <input type="date" id="startDate" name="startDate" value="<?= $trip->getStartDate() ?>" required>
    <label for="endDate">End Date:</label>
    <input type="date" id="endDate" name="endDate" value="<?= $trip->getEndDate() ?>" required>
    <label for="collaborative">Collaborative:</label>
    <input type="checkbox" id="collaborative" name="collaborative" <?= $trip->isCollaborative() ? "checked" : "" ?>>
    <label for="private">Private:</label>
    <input type="checkbox" id="private" name="private" <?= $trip->isPrivate() ? "checked" : "" ?>>
    <label for="countOfPerson">Count of Person:</label>
    <input type="number" id="countOfPerson" name="countOfPerson" value="<?= $trip->getCountOfPerson() ?>" min="1" required>
    
    <label for="image">Image actuelle:</label>
    <?php if ($trip->getImage()): ?>
        <img src="images/upload/<?= $trip->getImage() ?>" alt="image <?= $trip->getTitle() ?>">
    <?php else: ?>
        <p>Aucune image actuellement.</p>
    <?php endif; ?>

    <label for="image">Changer l'image:</label>
    <input type="file" id="image" name="image">
    <p>Si vous ne choisissez pas une nouvelle image, l'image actuelle restera en place.</p>
    
<input type="submit" value="Update">
</form>