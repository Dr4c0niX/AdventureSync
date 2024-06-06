<?php
require("./managers/ArticlesManager.php");

$articlesManager = new ArticlesManager();

if ($_POST && isset($_POST["id"])) {
    $articlesManager->delete($_POST["id"]);
}

header("Location: article.php");