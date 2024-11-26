<?php
session_start();
require 'f0_connect_database.php';

function sign_user($mail_address, $password, $last_name,$first_name,  $last_kana,$first_kana,$zip_code, $address1, $address2, $mansion)
{
    $name=$last_name.'/'.$first_name.'/'.$last_kana.'/'.$first_kana;
    $address= $address1.'/'.$address2;
    try {
        $pdo = connect_database();
        $stmt = $pdo->prepare('INSERT INTO user(mail_address,pass,name,zip_code,address,mansion)values(?,?,?,?,?,?)');
        $stmt->execute([$mail_address, $password, $name, $zip_code, $address, $mansion]);
        $pdo=NULL;

    } catch (PDOException $e) {
        header("Location: g2_signup_view.php");
        return;
    }

    header("Location: g1_login_view.php");
    return;
}
