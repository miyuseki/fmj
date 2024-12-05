<?php
session_start();
require_once '../server/f0_connect_database.php';

try {
    $pdo = connect_database();
} catch (PDOException $e) {
    echo 'データベース接続エラー: ' . htmlspecialchars($e->getMessage());
    exit;
}

$sql = null;
$params = [];

// ウィッシュリストへの追加処理
if (isset($_POST['merchandise_id']) && isset($_SESSION['user'])) {
    $merchandise_id = $_POST['merchandise_id'];
    $user_id = $_SESSION['user'];

    // すでにウィッシュリストに追加されているかチェック
    $sql = $pdo->prepare('SELECT * FROM wishlist WHERE user_id = ? AND merchandise_id = ?');
    $sql->execute([$user_id, $merchandise_id]);
    $wishlist = $sql->fetch(PDO::FETCH_ASSOC);

    // ウィッシュリストにない場合、追加する
    if (!$wishlist) {
        $sql = $pdo->prepare('INSERT INTO wishlist (user_id, merchandise_id) VALUES (?, ?)');
        $sql->execute([$user_id, $merchandise_id]);
    }
}

// 検索処理
if (isset($_GET['search']) && !empty($_GET['search'])) {
    $sql = $pdo->prepare('SELECT * FROM merchandise WHERE merchandise_name LIKE ?');
    $params[] = '%' . $_GET['search'] . '%';
} elseif (isset($_GET['category'])) {
    $sql = $pdo->prepare('SELECT * FROM merchandise WHERE category = ?');
    $params[] = $_GET['category'];
}
?>
<!DOCTYPE html>
<html lang="jp">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="../style/style.css">
    <link rel="stylesheet" href="../style/g6_style.css">
    <title>検索結果画面</title>
</head>

<body>
    <header class="header_button">
        <a href="g3_home.php"><img src="../images/title.png" alt="FMJ"></a>
        <a href="g8_cart.php"><img src="../images/cart.png" alt="カート"></a>
    </header>
    <hr>
    <main>
        <form action="g6_search_result.php" method="get">
            <div class="container">
                <h2>検索</h2>
                <input type="search" name="search" placeholder="例　いちご">
            </div>
        </form>

        <h1>検索結果</h1>
        <fieldset>
            <?php
            if ($sql) {
                $sql->execute($params);
                $results = $sql->fetchAll(PDO::FETCH_ASSOC);

                if (empty($results)) {
                    echo '<h1>検索結果なし</h1>';
                } else {
                    echo '<div class="product-container">';
                    foreach ($results as $row):
            ?>
                        <div class="product-item">
                            <form action="g7_product_introduction.php" method="get">
                                <input type="hidden" name="merchandise_id" value="<?= htmlspecialchars($row['merchandise_id']) ?>">
                                <img src="<?= htmlspecialchars($row['image']) ?>" alt="商品画像">
                                <p>商品名: <?= htmlspecialchars($row['merchandise_name']) ?></p>
                                <p>価格: <?= htmlspecialchars($row['price']) ?>円</p>
                                <input type="submit" value="購入">
                            </form>
                            <form action="#" method="post">
                                <?php if (isset($_SESSION['user'])): ?>
                                    <button class="wish-button" type="submit" name="merchandise_id" value="<?= htmlspecialchars($row['merchandise_id']) ?>">♡</button>
                                <?php else: ?>
                                    <button class="wish-button" type="button" disabled>♡</button>
                                <?php endif; ?>
                            </form>
                        </div>
            <?php
                    endforeach;
                    echo '</div>';
                }
            }
            ?>
        </fieldset>
    </main>

    <footer></footer>
</body>

</html>