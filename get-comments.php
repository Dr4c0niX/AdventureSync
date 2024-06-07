<?php
require 'managers/CommentsManager.php';

$tripId = $_GET['tripId'];
$commentsManager = new CommentsManager();
$comments = $commentsManager->getByTripId($tripId);

header('Content-Type: application/json');
echo json_encode(['comments' => $comments]);
?>
