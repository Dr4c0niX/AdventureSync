<?php
require("./layout/header.php");

$tripsManager = new TripsManager();
$usersManager = new UsersManager();
$trip = null;

if ($_GET && isset($_GET["id"])) {
    $trip = $tripsManager->getById($_GET["id"]); //AJOUTER POUR QUE SEUL CREATEUR SE CO
}

$loggedInUser = $usersManager->getLoggedInUser();

if (!$loggedInUser) {
    echo "<script>alert('Pourquoi voulez-vous modifier un voyage qui n\'est pas le vôtre ?'); window.location.href='trip.php';</script>";
    exit;
}

if ($trip && $trip->getUserId() !== $loggedInUser->getId()) {
    echo "<script>alert('Pourquoi voulez-vous modifier un voyage qui n\'est pas le vôtre ?'); window.location.href='trip.php';</script>";
    exit;
}

if ($_POST) {
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

<form method="post">
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
    <input type="submit" value="Update">
</form>