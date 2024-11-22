<?php
session_start();
require 'f0_connect_database.php';

function user_info($mail_address,$name,$zip_code,$address,$mansion)
{
    $pdo = connect_database();
    $stmt = $pdo->prepare('select* from user');
    $stmt->execute([$mail_address,$name,$zip_code,$address,$mansion]);
    $user = $stmt->fetch(PDO::FETCH_ASSOC);
    $pdo=NULL;


    if($user) {
        $_SESSION['user_id'] = $user['user_id'];
        return true;
    }else{
        return false;
    }
}