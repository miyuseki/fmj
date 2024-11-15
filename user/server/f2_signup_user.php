<?php
session_start();
require 'f0_connect_database.php';

function sign_user($mail_address, $pass, $name, $zipcode, $address, $mansion)
{
    try {
        $pdo = connect_database();
        $stmt = $pdo->prepare('INSERT INTO user(mail_address,pass,name,zipcode,address,mansion)values(?,?,?,?,?,?)');
        $stmt->execute([$mail_address, $pass, $name, $zipcode, $address, $mansion]);
    } catch (PDOException $e) {
        header("Location: g2_signup_view.php");
        return;
    }

    header("Location: g1_login_view.php");
    return;
}
