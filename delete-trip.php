<?php
require("./managers/TripsManager.php");

$tripsManager = new TripsManager();

if ($_POST && isset($_POST["id"])) {
    $tripsManager->delete($_POST["id"]);
}

header("Location: trip.php");