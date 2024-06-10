<?php
require("./layout/header.php");

    $tripsManager = new TripsManager();
    $trips = $tripsManager->getAll();
    $loggedInUser = $usersManager->getLoggedInUser()
?>

<div>
    <p>Seulement les voyages dont la case 'privé' n'est pas cochée sont visibles sur cette page. Si vous avez créé un voyage et l'avez défini en privé, vous pouvez le retrouver en cliquant sur 'Modifier mon profil'.</p>
    <form action="trip.php" method="get"> <!-- Tri en fonction des pays-->
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
        <a href="trip.php" class="form-button">Réinitialiser</a>
    </form>
    <?php
        $country = $_GET['country'] ?? null;
        $trips = $tripsManager->getAll();
        if ($country) {
            $trips = array_filter($trips, function($trip) use ($country) {
                return $trip->getCountry() === $country;
            });
        }
    ?>
    <?php foreach ($trips as $trip): ?> <!-- Affichage des voyages publics, pas des  privés-->
        <?php if (!$trip->isPrivate()): ?>
            <div class="trip-card">
                <h1><?= $trip->getTitle() ?></h1>
                <p><?= $trip->getDescription() ?></p>
                <?php if ($trip->getImage()): ?>
                    <img src="images/upload/<?= $trip->getImage() ?>" alt="image <?= $trip->getTitle() ?>" class="trip-image">
                <?php endif; ?>            
                <h3>Destination: <?= $trip->getAddress() ?> , <?= $trip->getCountry() ?></h3>
                <h3>Date de début/fin : <?= $trip->getStartDate() ?> - <?= $trip->getEndDate() ?></h3>
                <h3>Nombre de personnes: <?= $trip->getCountOfPerson() ?></h3>
                <h3>Collaboratif: <?= $trip->isCollaborative() ? "Oui" : "Non" ?></h3>
                <?php
                    $creator = $usersManager->getById($trip->getUserId());
                ?>
                <h3>Créateur : <?= $creator->getFirstName() . ' ' . $creator->getLastName() ?></h3>
                <?php if ($loggedInUser && $loggedInUser->getId() === $creator->getId()): ?> <!--Option modifier ou supprimer pour le créateur du voyage -->
                    <div class="button-container">
                        <a href="edit-trip.php?id=<?= $trip->getId() ?>" class="form-button">Éditer</a>
                    </div>
                    <form action="delete-trip.php" method="post" onsubmit="return confirm('Êtes-vous sûr de vouloir supprimer ce voyage ?');">
                        <input type="hidden" name="id" value="<?= $trip->getId() ?>">
                        <input type="submit" value="Supprimer">
                    </form>
                <?php endif; ?>
                <?php if ($loggedInUser && $trip->isCollaborative() && $loggedInUser->getId() !== $creator->getId()): ?> <!--Bouton contacter le créateur si un utilisateur est connecté et que ce n'est pas le créateur -->
                    <div class="button-container">                    
                        <a href="mailto:<?= $creator->getEmail() ?>?subject=Contact pour <?= $trip->getTitle() ?>" target="_blank">Contacter le créateur de ce voyage</a>
                    </div>
                <?php endif; ?>
            </div>
        <?php endif; ?>
    <?php endforeach; ?>
</div>