<?php 
require("./layout/header.php");

//vérification admin
if (!isset($_SESSION["is_connected"]) || !$usersManager->getLoggedInUser()->isAdmin()) 
{
    echo "<script>alert('Vous devez être connecté en tant qu\'administrateur pour accéder à cette page.'); window.location.href='./index.php';</script>";
    exit;
}

$usersManager = new UsersManager();
$articleManager = new ArticlesManager();
$tripManager = new TripsManager();

$users = $usersManager->getUsersOrderedByEmail();
$articles = $articleManager->getAll();
$trips = $tripManager->getAll();
?>

<div>
    <h1>Page administration</h1>
</div>

<div id="users">
    <h2>Utilisateurs</h2>
    <?php foreach ($users as $user): ?>
        <div class="user">
            <p><?php echo $user->getEmail() . ' - ' . $user->getFirstName() . ' ' . $user->getLastName() . ' - ' . $user->getId(); ?></p>
            <a href="edit-profile-for-admin.php?id=<?php echo $user->getId(); ?>">Modifier le profil</a>
        </div>
    <?php endforeach; ?>
</div>

<div class="grid-container">
    <div id="articles">
        <h2>Articles</h2>
        <?php foreach ($articles as $article): ?>
            <div class="card">
                <p><?php echo $article->getTitle(); ?></p>
                <form action="delete-article.php" method="post" onsubmit="return confirm('Êtes-vous sûr de vouloir supprimer cet article ?');">
                    <input type="hidden" name="id" value="<?php echo $article->getId(); ?>">
                    <button type="submit">Supprimer</button>
                </form>
            </div>
        <?php endforeach; ?>
    </div>

    <div id="trips">
        <h2>Voyages</h2>
        <?php foreach ($trips as $trip): ?>
            <div class="card">
                <p><?php echo $trip->getTitle(); ?></p>
                <p>Privé : <?= $trip->isPrivate() ? "Oui" : "Non"?></p>
                <form action="delete-trip.php" method="post" onsubmit="return confirm('Êtes-vous sûr de vouloir supprimer ce voyage ?');">
                    <input type="hidden" name="id" value="<?php echo $trip->getId(); ?>">
                    <button type="submit">Supprimer</button>
                </form>
            </div>
        <?php endforeach; ?>
    </div>
</div>
