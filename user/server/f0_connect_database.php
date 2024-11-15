<?php

function connect_database()
{
    try{
    $pdo=new PDO(
            'mysql:host=mysql305.phy.lolipop.lan;
            dbname=LAA1602705-php2024;charset=utf8',
            'LAA1602705',
            'Pass0221');

    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    return $pdo;
    } catch (PDOException $e) {
        echo 'データベース接続エラー: ' . htmlspecialchars($e->getMessage());
        exit;
    }
}