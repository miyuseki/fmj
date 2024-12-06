<?php
session_start();

if (!isset($_SESSION['admin_logged_in'])) {
    header("Location: g1_login_view.php");
    exit();
}


if (isset($_GET['logout'])) {
    session_destroy();
    header("Location: g1_login_view.php");
    exit();
}
?>
<!DOCTYPE html>
<html lang="jp">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="../style/reset.css">
    <link rel="stylesheet" href="../style/common_style.css">

    <title>管理者ダッシュボード</title>
</head>

<body>
    <header>
        <span class="header-left">FMJ</span>
        <span class="header-right">
            <a href="?logout" style="text-decoration: none; color: #000;">ログアウト</a>
        </span>
    </header>

    <main>
        <div class="container">
            <nav class="sidebar">
                <h3>管理メニュー</h3>
                <ul>
                    <li><a href="g3_user_list_view.php">ユーザー管理</a></li>
                    <li><a href="g4_item_list_view.php">商品管理</a></li>
                    <li><a href="g5_item_update_view.php">管理者登録</a></li>
                    <li><a href="?logout">ログアウト</a></li>
                </ul>
            </nav>

            <div class="main-content">
                <h1>管理者ダッシュボード</h1>
                <p>操作を選択してください。</p>
            </div>
        </div>
    </main>

    <style>
        body {
            font-family: Arial, sans-serif;
        }

        header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            padding: 10px 20px;
            background-color: #f4f4f4;
            border-bottom: 1px solid #ddd;
        }

        .container {
            display: flex;
        }

        .sidebar {
            width: 20%;
            background-color: #f9f9f9;
            border-right: 1px solid #ddd;
            padding: 15px;
        }

        .sidebar ul {
            list-style: none;
            padding: 0;
        }

        .sidebar ul li {
            margin-bottom: 10px;
        }

        .sidebar ul li a {
            text-decoration: none;
            color: #333;
        }

        .sidebar ul li a:hover {
            text-decoration: underline;
        }

        .main-content {
            width: 80%;
            padding: 20px;
        }

        .main-content h1 {
            margin-top: 0;
        }
    </style>
</body>

</html>
