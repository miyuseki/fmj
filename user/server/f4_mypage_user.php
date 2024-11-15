<?php
session_start();
require 'f0_connect_database.php';

function login_user($mail_address,$name,$zipcode,$address,$mansion)
{
    $pdo = connect_database();
    $stmt = $pdo->prepare('select* from user');
    $stmt->execute([$mail_address,$name,$zipcode,$address,$mansion]);
    $user = $stmt->fetch(PDO::FETCH_ASSOC);

    if($user) {
        $_SESSION['user_id'] = $user['user_id'];
        return true;
    }else{
        return false;
    }
}