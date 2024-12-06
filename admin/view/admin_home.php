<?php

session_start();

if (!isset($_SESSION['admin_logged_in'])) {

    header("Location: g1_login_view.php");

    exit();

}

?>
<!DOCTYPE html>
<html lang="ja">
<head>
<meta charset="UTF-8">
<title>管理者ホーム</title>
<link rel="stylesheet" href="style.css">
</head>
<body>
<h1>管理者ホーム</h1>
<nav>
<ul>
<li><a href="g3_user_list_view.php">ユーザー一覧</a></li>
<li><a href="g4_item_list_view.php">商品一覧</a></li>
<li><a href="g6_item_register_view.php">商品登録</a></li>
</ul>
</nav>
<a href="logout.php">ログアウト</a>
</body>
</html> 