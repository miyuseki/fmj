<?php
session_start();
require_once '../server/f0_connect_database.php';

// ログイン確認
if (!isset($_SESSION['user'])) {
    header("Location: g1_login.php");
    exit();
}

try {
    $pdo = connect_database();
} catch (PDOException $e) {
    echo 'データベース接続エラー: ' . htmlspecialchars($e->getMessage());
    exit;
}

$user_id = $_SESSION['user'];

// POSTリクエスト処理（削除や数量変更）
if ($_SERVER["REQUEST_METHOD"] === "POST") {
    if (isset($_POST['action'])) {
        if ($_POST['action'] === 'delete') {
            // 商品を削除
            $merchandise_id = $_POST['merchandise_id'];
            $sql = 'DELETE FROM cart WHERE user_id = ? AND merchandise_id = ?';
            $stmt = $pdo->prepare($sql);
            $stmt->execute([$user_id, $merchandise_id]);
        } elseif ($_POST['action'] === 'update') {
            // 数量を更新
            $merchandise_id = $_POST['merchandise_id'];
            $quantity = $_POST['quantity'];
            if ($quantity > 0) {
                $sql = 'UPDATE cart SET quantity = ? WHERE user_id = ? AND merchandise_id = ?';
                $stmt = $pdo->prepare($sql);
                $stmt->execute([$quantity, $user_id, $merchandise_id]);
            } else {
                // 数量が0以下の場合は削除
                $sql = 'DELETE FROM cart WHERE user_id = ? AND merchandise_id = ?';
                $stmt = $pdo->prepare($sql);
                $stmt->execute([$user_id, $merchandise_id]);
            }
        }
    }
}

// カートの中身を取得
$sql = 'SELECT c.*, m.merchandise_name, m.price, m.image 
        FROM cart c 
        JOIN merchandise m ON c.merchandise_id = m.merchandise_id 
        WHERE c.user_id = ?';
$stmt = $pdo->prepare($sql);
$stmt->execute([$user_id]);
$cart_items = $stmt->fetchAll(PDO::FETCH_ASSOC);
?>

<!DOCTYPE html>
<html lang="ja">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>FMJ - カート</title>
    <style>
        body {
            font-family: Arial, sans-serif;
        }

        .cart-item {
            display: flex;
            justify-content: space-between;
            align-items: center;
            border-bottom: 1px solid #ccc;
            padding: 10px 0;
        }

        .cart-item img {
            max-width: 100px;
            border-radius: 5px;
        }

        .cart-item-info {
            flex: 1;
            margin-left: 10px;
        }

        .cart-item-actions {
            display: flex;
            flex-direction: column;
            align-items: flex-end;
        }

        .cart-item-actions form {
            margin-top: 5px;
        }

        input[type="number"] {
            width: 50px;
        }

        input[type="submit"] {
            padding: 5px 10px;
            background-color: #4CAF50;
            color: white;
            border: none;
            border-radius: 5px;
            cursor: pointer;
        }

        input[type="submit"]:hover {
            background-color: #45a049;
        }
    </style>
</head>

<body>
    <h1>カート</h1>
    <a href="javascript:history.back()">閉じる</a>


    <?php if (count($cart_items) > 0): ?>
        <?php foreach ($cart_items as $item): ?>
            <div class="cart-item">
                <img src="<?= htmlspecialchars($item['image']) ?>" alt="商品画像">
                <div class="cart-item-info">
                    <h3><?= htmlspecialchars($item['merchandise_name']) ?></h3>
                    <p>価格: <?= htmlspecialchars($item['price']) ?>円</p>
                    <p>数量: <?= htmlspecialchars($item['quantity']) ?></p>
                </div>
                <div class="cart-item-actions">
                    <!-- 数量変更フォーム -->
                    <form method="post">
                        <input type="hidden" name="action" value="update">
                        <input type="hidden" name="merchandise_id" value="<?= htmlspecialchars($item['merchandise_id']) ?>">
                        <input type="number" name="quantity" value="<?= htmlspecialchars($item['quantity']) ?>" min="1">
                        <input type="submit" value="数量を変更">
                    </form>
                    <!-- 削除フォーム -->
                    <form method="post">
                        <input type="hidden" name="action" value="delete">
                        <input type="hidden" name="merchandise_id" value="<?= htmlspecialchars($item['merchandise_id']) ?>">
                        <input type="submit" value="削除">
                    </form>
                </div>
            </div>
        <?php endforeach; ?>
        <form action="buy.php" method="post">
            <?php foreach ($cart_items as $item): ?>
                <input type="hidden" name="merchandise_ids[]" value="<?= htmlspecialchars($item['merchandise_id']) ?>">
            <?php endforeach; ?>
            <button type="submit">購入確認画面へ</button>
        </form>

    <?php else: ?>
        <p>カートに商品がありません。</p>
    <?php endif; ?>
</body>

</html>