<?php

function connect_database()
{
    try{
    $pdo=new PDO(
        'mysql:host=mysql'
    );
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    return $pdo;
    } catch (PDOException $e) {
        echo 'データベース接続エラー: ' . htmlspecialchars($e->getMessage());
        exit;
    }
}