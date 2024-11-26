<?php
// セッション開始（ログインユーザーがいる場合）
session_start();
require 'f0_connect_database.php';


// カートの表示
function showCartPage()
{
    $cart = [];

    if (isset($_SESSION['user_id'])) {
        $user_id = $_SESSION['user_id'];

        // ユーザーIDに基づいてカートの情報を取得
        $pdo = connect_database();
        $stmt = $pdo->prepare('SELECT * FROM cart WHERE user_id=?');
        $stmt->execute([$user_id]);
    } else {
        // 未ログインの場合はクッキーからカート情報を取得
        if (isset($_COOKIE['cart'])) {
            $cart = json_decode($_COOKIE['cart'], true);
        } else {
            // クッキーがない場合は空のカート情報を作成
            $cart = [];
            setcookie('cart', json_encode($cart), time() + 3600 * 24, '/'); // クッキーを作成（有効期間: 1日）
        }
    }

    require './views/g5_home.php';
}

// カートに追加する処理
function addToCart($merchandise_id, $quantity)
{
   
    if (isset($_SESSION['user_id'])) {
        $user_id = $_SESSION['user_id'];

        // ユーザーIDに基づいてカートの情報を取得
        $pdo = connect_database();
        $stmt = $pdo->prepare('SELECT * FROM cart WHERE user_id=?');
        $stmt->execute([$user_id]);
    } else {
        // 未ログインの場合
        $cart = [];

        // クッキーから既存のカート情報を取得
        if (isset($_COOKIE['cart'])) {
            $cart = json_decode($_COOKIE['cart'], true);
        } else {
            // クッキーが存在しない場合、新規作成
            $cart = [];
        }

        // カートに新しい商品を追加または既存の商品の数量を更新
        if (isset($cart[$merchandise_id])) {
            $cart[$merchandise_id] += $quantity; // 既存の商品の数量を更新
        } else {
            $cart[$merchandise_id] = $quantity; // 新しい商品を追加
        }

        // 更新されたカート情報をクッキーに保存
        setcookie('cart', json_encode($cart), time() + 3600 * 24, '/'); // クッキーの有効期間は1日
    }

    // カートページにリダイレクト
    header('Location: ./g5_cart.php');
    exit;
}
