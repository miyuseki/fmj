<?php
require_once 'connect_database.php';

$email = $_POST['email'];
$password = $_POST['password'];
$last_kanji = $_POST['last_kanji'];
$first_kanji = $_POST['first_kanji'];
$last_kana = $_POST['last_kana'];
$first_kana = $_POST['first_kana'];
$zip_code = $_POST['zip_code'];
$prefectures = $_POST['prefectures'];
$street_address = $_POST['street_address'];
$apartment_name = $_POST['apartment_name'];

$name = 'last_kanji/' . $last_kanji . '/first_kanji/' . $first_kanji . '/last_kana/' . $last_kana . '/first_kana' . $first_kana;
$address = 'prefectures/' . $prefectures . '/street_address/' . $street_address;

try {
    $pdo = connect_database();
    $stmt = $pdo->prepare("INSERT INTO user VALUES(NULL, ?, ?, ?, ?, ?, ?)");
    $stmt->execute([$email, $password, $name, $zip_code, $address, $apartment_name]);
} catch (PDOException $e) {
    header('Location: ../screen/signup.php');
}

header('Location: ../screen/login.php');
