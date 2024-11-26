<?php
session_start();
require 'f0_connect_database.php';

function buy_user($pass, $name, $zip_code, $address, $mansion)
{


    if ($_SESSION["user_id"] ?? false) {
        $pdo = connect_database();
        $stmt = $pdo->prepare('SELECT *FROM user(name,zip_code,address,mansion)values(?,?,?,?,?)');
        $stmt->execute([$name, $zip_code, $address, $mansion]);
        $user = $stmt->fetch(PDO::FETCH_ASSOC);
        $pdo = NULL;

        $names=explode('/',$user['name']);
        $addresses=explode('/',$user['address']);

        return [
            $user,$names,$addresses
        ];



    }

    header("Location: g1_login_view.php");
    return;
}
