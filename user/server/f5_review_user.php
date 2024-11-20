<?php
session_start();
require 'f0_connect_database.php';

function review_user($user_id)
{
    $pdo = connect_database();
    $review_result = $pdo->prepare('SELECT * FROM review WHERE user_id=?');
    $review_result->execute([$user_id]);

    $merchandise_result = $pdo->prepare('SELECT * FROM merchandise WHERE ?');
    $merchandise_result->execute([$user_id]);

    

    foreach ($review_result as $row) {
        echo '<p><img src="', 'photograph_files/' . htmlspecialchars($row['photograph']), '" width=200></p>';
        echo htmlspecialchars($row['']);
        echo htmlspecialchars($row['title']), '<br>';
        echo htmlspecialchars($row['impression']), '<br>';
        echo htmlspecialchars($row['impression']), '<br>';
    }
}
