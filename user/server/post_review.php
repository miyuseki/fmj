<?php
session_start();
require_once 'f0_connect_database.php';

$user_id = $_SESSION['user'];
$merchandise_id = $_POST['merchandise_id'];
$rating = $_POST['review'];
$comment = $_POST['comment'];

try {
    $pdo = connect_database();
    $stmt = $pdo->prepare("INSERT INTO review VALUES(?,NULL, ?, ?, ?, default)");
    $stmt->execute([$user_id, $merchandise_id, $rating, $comment]);
} catch (PDOException $e) {
    echo $e;
}

header("Location: ../view/g7_product_introduction.php?merchandise_id=$merchandise_id");
