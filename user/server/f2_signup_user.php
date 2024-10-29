<?php
session_start();
require 'f0_connect_database.php';

function login_user($mail_address,$pass,$name,$zipcode,$address,$mansion)
{
    $pdo = connect_database();
    $stmt = $pdo->prepare('INSERT INTO students(mail_address,pass,name,zipcode,address,mansion)values(?,?,?,?,?,?)');
    $stmt->execute([$mail_address,$pass,$name,$zipcode,$address,$mansion]);
    $user = $stmt->fetch(PDO::FETCH_ASSOC);

    if($user) {
        $_SESSION['user_id'] = $user['user_id'];
        return true;
    }else{
        return false;
    }
}