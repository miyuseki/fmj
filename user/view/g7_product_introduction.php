<?php
session_start();
require_once '../server/f0_connect_database.php';

try {
    $pdo = connect_database();
} catch (PDOException $e) {
    echo 'データベース接続エラー: ' . htmlspecialchars($e->getMessage());
    exit;
}

$merchandise = null;
$reviews = null;

// 商品情報とレビューの取得（GETリクエスト）
if ($_SERVER["REQUEST_METHOD"] === "GET" && !empty($_GET['merchandise_id'])) {
    $sql = $pdo->prepare('SELECT * FROM merchandise WHERE merchandise_id = ?');
    $sql->execute([$_GET['merchandise_id']]);
    $merchandise = $sql->fetch(PDO::FETCH_ASSOC);

    if (!$merchandise) {
        echo '商品が見つかりませんでした。';
        exit;
    }
    // ーーーーーーーーーーーーーーーーーーーーーーーーーーーーーーーーーーーーーーーーーーーーーーーーーーーーー
    $sql = $pdo->prepare('SELECT * FROM review WHERE merchandise_id = ?');
    $sql->execute([$_GET['merchandise_id']]);
    $reviews = $sql->fetchAll(PDO::FETCH_ASSOC);
}

// カートに追加（POSTリクエスト）
if ($_SERVER["REQUEST_METHOD"] === "POST") {
    if (isset($_SESSION['user'])) {
        $user_id = $_SESSION['user'];
        $merchandise_id = $_POST['merchandise_id'] ?? null;
        $quantity = (int)($_POST['quantity'] ?? 0);

        // 入力値のバリデーション
        if (!$merchandise_id || $quantity <= 0) {
            echo '無効な入力です。';
            exit;
        }

        // カートにすでに商品があるか確認
        $sql = 'SELECT * FROM cart WHERE user_id = ? AND merchandise_id = ?';
        $stmt = $pdo->prepare($sql);
        $stmt->execute([$user_id, $merchandise_id]);
        $existing_cart_item = $stmt->fetch(PDO::FETCH_ASSOC);

        if ($existing_cart_item) {
            // 数量を更新
            $new_quantity = $existing_cart_item['quantity'] + $quantity;
            $update_sql = 'UPDATE cart SET quantity = ? WHERE user_id = ? AND merchandise_id = ?';
            $update_stmt = $pdo->prepare($update_sql);
            $update_stmt->execute([$new_quantity, $user_id, $merchandise_id]);
        } else {
            // 新しく追加
            $insert_sql = 'INSERT INTO cart (user_id, merchandise_id, quantity) VALUES (?, ?, ?)';
            $insert_stmt = $pdo->prepare($insert_sql);
            $insert_stmt->execute([$user_id, $merchandise_id, $quantity]);
        }

        $link = "detail.php?merchandise_id=$merchandise_id";

        // 処理完了後、カートページにリダイレクト
        header("Location: $link");
        exit();
    } else {
        header("Location: login.php");
        exit();
    }
}
?>
<!DOCTYPE html>
<html lang="jp">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="../style/style.css">
    <link rel="stylesheet" href="../style/g7_style.css">

    <title>商品詳細</title>
</head>

<body>
    <header class="header_button">
        <a href="g3_home.php"><img src="../images/title.png" alt="FMJ"></a>
        <a href="g8_cart.php"><img src="../images/cart.png" alt="カート"></a>
    </header>

    <hr>

    <main>
        <?php if ($merchandise): ?>
            <!-- 商品詳細表示 -->
            <div class="merchandise-detail">
                <div class="merchandise-image">
                    <img src="<?= htmlspecialchars($merchandise['image']) ?>" alt="商品画像">
                </div>
                <div class="merchandise-info">
                    <h1><?= htmlspecialchars($merchandise['merchandise_name']) ?></h1>
                    <p><?= htmlspecialchars($merchandise['explanation']) ?></p>
                    <p class="price"><?= htmlspecialchars($merchandise['price']) ?>円</p>

                    <form action="#" method="post">
                        <input type="hidden" name="merchandise_id" value="<?= htmlspecialchars($merchandise['merchandise_id']) ?>">
                        <input type="number" name="quantity" value="1" min="1"><br>
                        <input type="submit" value="カゴに入れる">
                    </form>

                    <form action="g9_buy.php" method="post">
                        <input type="hidden" name="merchandise_ids[]" value="<?= htmlspecialchars($merchandise['merchandise_id']) ?>">
                        <input type="submit" value="今すぐ買う">
                    </form>
                </div>
            </div>
        <?php else: ?>
            <p>商品が見つかりませんでした。</p>
        <?php endif; ?>

        <!-- 商品レビュー -->
        <div class="reviews">
            <div class="review">
                <h2>レビュー</h2>
            </div>

            <?php foreach ($reviews as $review): ?>
                <div class="stars-fixed">
                    <span>
                        <input id="review01" type="radio" name="review1" disabled <?php if ($review['rating'] == 5) echo 'checked'; ?>><label for="review01">★</label>
                        <input id="review02" type="radio" name="review1" disabled <?php if ($review['rating'] == 4) echo 'checked'; ?>><label for="review02">★</label>
                        <input id="review03" type="radio" name="review1" disabled <?php if ($review['rating'] == 3) echo 'checked'; ?>><label for="review03">★</label>
                        <input id="review04" type="radio" name="review1" disabled <?php if ($review['rating'] == 2) echo 'checked'; ?>><label for="review04">★</label>
                        <input id="review05" type="radio" name="review1" disabled <?php if ($review['rating'] == 1) echo 'checked'; ?>><label for="review05">★</label>
                    </span>
                </div>
                <fieldset>
                    <div class="review-content">
                        <p><?= htmlspecialchars($review['comment']) ?></p>
                    </div>
                </fieldset>
            <?php endforeach; ?>

            <div class="review">
                <h2>レビューを書く</h2>
            </div>

            <!-- レビュー内容を書くフォーム -->
            <div class="write-review">
                <form action="../server/post_review.php" method="post">
                    <div class="stars">
                        <span>
                            <input id="review06" type="radio" name="review" value="5"><label for="review06">★</label>
                            <input id="review07" type="radio" name="review" value="4"><label for="review07">★</label>
                            <input id="review08" type="radio" name="review" value="3"><label for="review08">★</label>
                            <input id="review09" type="radio" name="review" value="2"><label for="review09">★</label>
                            <input id="review10" type="radio" name="review" value="1"><label for="review10">★</label>
                        </span>
                    </div>
                    <input type="hidden" name="merchandise_id" value="<?= htmlspecialchars($merchandise['merchandise_id']) ?>">
                    <textarea name="comment"></textarea>
                    <?php
                    if (!isset($_SESSION['user'])) {
                        echo '<input type="submit" value="投稿">';
                    } else {
                        echo '<input type="submit" value="投稿" disable>';
                    }
                    ?>
                </form>
            </div>
        </div>
    </main>
</body>

</html>