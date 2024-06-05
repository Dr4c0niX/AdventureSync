<?php
    require("./layout/header.php");

    $usersManager = new UsersManager();
    $tripsManager = new TripsManager();
    $trips = $tripsManager->getAll();
    $loggedInUser = $usersManager->getLoggedInUser()
?>

<div>
    <?php foreach ($trips as $trip): ?>
        <div class="trip-card">
            <h1><?= $trip->getTitle() ?></h1>
            <p><?= $trip->getDescription() ?></p>
            <h3>Destination: <?= $trip->getDestination() ?></h3>
            <h3>Date de départ: <?= $trip->getStartDate() ?></h3>
            <h3>Date de fin: <?= $trip->getEndDate() ?></h3>
            <h3>Collaboratif: <?= $trip->isCollaborative() ? "Oui" : "Non" ?></h3>
            <h3>Privé: <?= $trip->isPrivate() ? "Oui" : "Non" ?></h3>
            <h3>Nombre de personnes: <?= $trip->getCountOfPerson() ?></h3>
            <?php
                $creator = $usersManager->getById($trip->getUserId());
            ?>
            <h2>Créateur : <?= $creator->getFirstName() . ' ' . $creator->getLastName() ?></h2>
            <?php if ($loggedInUser && $loggedInUser->getId() === $creator->getId()): ?>
                <a href="edit-trip.php?id=<?= $trip->getId() ?>">Éditer</a>
                <form action="delete-trip.php" method="post" onsubmit="return confirm('Êtes-vous sûr de vouloir supprimer ce voyage ?');">
                    <input type="hidden" name="id" value="<?= $trip->getId() ?>">
                    <input type="submit" value="Supprimer">
                </form>
            <?php endif; ?>
        </div>
    <?php endforeach; ?>
</div>