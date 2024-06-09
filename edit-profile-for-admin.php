<?php
require("./layout/header.php");
require_once("./managers/UsersManager.php");
// Créer une nouvelle instance de UsersManager
$usersManager = new UsersManager();

if (!isset($_SESSION["is_connected"]) || !$usersManager->getLoggedInUser()->isAdmin()) 
{
    echo "<script>alert('Vous devez être connecté en tant qu\'administrateur pour accéder à cette page.'); window.location.href='./index.php';</script>";
    exit;
}
// Récupérer l'ID de l'utilisateur à partir de l'URL
$id = (int)$_GET['id'];
// Récupérer les informations de l'utilisateur à partir de la base de données
$user = $usersManager->getById($id);

// Si le formulaire a été soumis, mettre à jour les informations de l'utilisateur
if ($_SERVER['REQUEST_METHOD'] === 'POST') {

    $birthDate = new DateTime($_POST["birthDate"]);
    $today = new DateTime();
    $interval = $birthDate->diff($today);
    $age = $interval->y;

    if ($age < 16 || $birthDate > $today) {
        echo "<script>alert('L\'utilsateur doit avoir au moins 16 ans ou la date choisie est dans le futur.'); window.location.href='admin.php';</script>";
        exit;
    }
    $user->setEmail($_POST['email']);
    $user->setUsername($_POST['username']);
    $user->setFirstName($_POST['firstName']);
    $user->setLastName($_POST['lastName']);
    $user->setBirthDate($_POST['birthDate']);
    $isAdmin = isset($_POST['admin']) ? true : false;
    $user->setAdmin($isAdmin);
    $usersManager->update($user);

    echo "<script>alert('Profil mis à jour avec succès.'); window.location.href='admin.php';</script>";
    exit;}
?>

<div>
    <title>Modifier le profil</title>
</div>
<div>
    <h1>Modifier le profil de <?php echo $user->getFirstName() . ' ' . $user->getLastName(); ?></h1>

    <form method="post">
        <input type="hidden" name="id" value="<?php echo $user->getId(); ?>">
        <input type="hidden" name="id" value="<?php echo $id; ?>">

        <label for="email">Email :</label>
        <input type="email" id="email" name="email" value="<?php echo $user->getEmail(); ?>">
        <label for="username">Username :</label>
        <input type="text" id="username" name="username" value="<?php echo $user->getUsername(); ?>">
        <label for="firstName">Prénom :</label>
        <input type="text" id="firstName" name="firstName" value="<?php echo $user->getFirstName(); ?>">
        <label for="lastName">Nom :</label>
        <input type="text" id="lastName" name="lastName" value="<?php echo $user->getLastName(); ?>">
        <label for="birthDate">Date de naissance :</label>
        <input type="date" id="birthDate" name="birthDate" value="<?php echo $user->getBirthDate(); ?>">
        <label for="admin">Admin</label>
        <input type="checkbox" id="admin" name="admin" <?php echo $user->isAdmin() ? 'checked' : ''; ?>>
        <input type="submit" value="Mettre à jour">
    </form>
</div>