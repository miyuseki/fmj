<?php

function connect_database()
{
    $host = 'mysql305.phy.lolipop.lan';
    $db_name = 'LAA1602705-php2024';
    $username = 'LAA1602705';
    $password = 'Itou0315';
    $conn=null;

            try {
                $conn = new PDO("mysql:host=" . $host . ";dbname=" . $db_name, $username, $password);
                $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
            } catch (PDOException $exception) {
                echo "Connection error: " . $exception->getMessage();
            }
        
            return $conn;
        }