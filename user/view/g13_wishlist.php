<?php
session_start();
require_once '../server/f0_connect_database.php';

if (!isset($_SESSION['user'])) {
    header('Location: g1_login.php');
    exit();
}

try {
    $pdo = connect_database();
} catch (PDOException $e) {
    echo 'データベース接続エラー: ' . htmlspecialchars($e->getMessage());
    exit;
}

// ウィッシュリストの削除
if (isset($_POST['merchandise_id'])) {
    foreach ($_POST['merchandise_id'] as $row) {
        $sql = $pdo->prepare('DELETE FROM wishlist WHERE merchandise_id = ? AND user_id = ?');
        $sql->execute([$row, $_SESSION['user']]);
    }
}

$reviews = null;

// ウィッシュリストの取得
if (isset($_SESSION['user'])) {
    $sql = $pdo->prepare('SELECT * FROM wishlist WHERE user_id = ?');
    $sql->execute([$_SESSION['user']]);
    $wishlists = $sql->fetchAll(PDO::FETCH_ASSOC);

    if (!$wishlists) {
        echo 'ウィッシュリストなし';
    }
}

?>


<!DOCTYPE html>
<html lang="ja">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="../style/g5_style.css">
    <title>レビュー一覧</title>
    <style>
        h1 {
            text-align: center;
            font-size: 24px;
            margin-bottom: 20px;
        }

        .review-list {
            list-style: none;
            padding: 0;
        }

        .review-item {
            display: flex;
            border: 1px solid #ddd;
            border-radius: 8px;
            margin-bottom: 20px;
            padding: 15px;
            background-color: #fafafa;
        }

        .review-item img {
            max-width: 200px;
            height: auto;
            margin-right: 20px;
            border-radius: 8px;
        }

        .review-item .details {
            flex-grow: 1;
        }

        .review-item h3 {
            font-size: 20px;
            margin-bottom: 10px;
        }

        .review-item p {
            font-size: 14px;
            color: #555;
        }

        .review-item .rating {
            font-weight: bold;
            color: #ff9900;
        }

        .review-item .date {
            font-size: 12px;
            color: #888;
        }

        .review-item .checkbox {
            margin-top: 10px;
        }

        button {
            padding: 10px 20px;
            font-size: 16px;
            background-color: #747474;
            color: white;
            border: none;
            border-radius: 5px;
            cursor: pointer;
        }

        button:hover {
            background-color: #525252;
        }
    </style>
</head>

<body>
    <header>
        <a href="g4_mypage.php">戻る</a>
        <a href="../server/logout.php">ログアウト</a>
    </header>
    <h1>FMJ</h1>

    <div class="container">
        <h1>ウィッシュリスト</h1>

        <form method="post" action="#">
            <button type="submit">削除</button>
            <ul class="review-list">

                <!-- レビュー-->
                <?php foreach ($wishlists as $wishlist): ?>
                    <?php
                    $sql = $pdo->prepare('SELECT * FROM merchandise WHERE merchandise_id = ?');
                    $sql->execute([$wishlist['merchandise_id']]);
                    $merchandise = $sql->fetch(PDO::FETCH_ASSOC);
                    ?>
                    <li class="review-item">
                        <img src="<?= $merchandise['image'] ?>" alt="<? $merchandise['merchandise_name'] ?>">
                        <div class="details">
                            <h3><a href="g7_product_introduction.php?merchandise_id=<?= $merchandise['merchandise_id'] ?>"><?= $merchandise['merchandise_name'] ?></a></h3>
                            <div class="checkbox">
                                <input type="checkbox" name="merchandise_id[]" value="<?= $merchandise['merchandise_id'] ?>"> 削除する
                            </div>
                        </div>
                    </li>
                <?php endforeach; ?>
            </ul>
        </form>
    </div>

</body>

</html>