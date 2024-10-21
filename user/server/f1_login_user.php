<?php
session_start();
require 'f0_connect_database.php';

function login_user($mail_address, $pass)
{
    $pdo = connect_database();
    $stmt = $pdo->prepare('SELECT * FROM user WHERE mail_address=? AND pass=?');
    $stmt->execute([$mail_address, $pass]);
    $user = $stmt->fetch(PDO::FETCH_ASSOC);

    if($user) {
        $_SESSION['user_id'] = $user['user_id'];
        return true;
    }else{
        return false;
    }
}