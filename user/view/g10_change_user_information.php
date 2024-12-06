<?php
session_start();
require_once '../server/f0_connect_database.php';

// ログイン確認
if (!isset($_SESSION['user'])) {
    header("Location: g1_login.php");
    exit();
}

$user_id = $_SESSION['user'];

try {
    $pdo = connect_database();

    // POSTデータから商品IDリストを取得
    $merchandise_ids = $_POST['merchandise_ids'] ?? [];
    if (empty($merchandise_ids)) {
        echo '商品が選択されていません。';
        exit;
    }

    // 商品情報取得
    $placeholders = rtrim(str_repeat('?,', count($merchandise_ids)), ',');
    $sql = "SELECT * FROM merchandise WHERE merchandise_id IN ($placeholders)";
    $stmt = $pdo->prepare($sql);
    $stmt->execute($merchandise_ids);
    $items = $stmt->fetchAll(PDO::FETCH_ASSOC);
} catch (PDOException $e) {
    echo 'データベースエラー: ' . htmlspecialchars($e->getMessage());
    exit;
}
?>
<!DOCTYPE html>
<html lang="jp">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="../style/style.css">
    <link rel="stylesheet" href="../style/g10_style.css">

    <title>購入確認</title>
</head>
<body>

    <header class="header_button">
        <a href="g3_home.php"><img src="../images/title.png" alt="FMJ"></a>
        <a href="g8_cart.php"><img src="../images/cart.png" alt="カート"></a>
    </header>

    <hr>

    <h2>購入品</h2>
    <?php if (empty($items)): ?>
        <p class="no-items">選択された商品はありません。</p>
    <?php else: ?>
        <?php foreach ($items as $item): ?>
            <div class="item">
                <div class="item-info">
                    <img src="<?= htmlspecialchars($item['image'] ?? 'no-image.png') ?>" alt="商品画像">
                    <div>
                        <h3><?= htmlspecialchars($item['merchandise_name']) ?></h3>
                        <p>価格: <?= htmlspecialchars($item['price']) ?>円</p>
                    </div>
                </div>
            </div>
        <?php endforeach; ?>
    <?php endif; ?>

    <h2>ユーザー情報</h2>
    <form action="g12_buy_completed.php" method="post">
        <fieldset>
            <h3>住所情報</h3>
            <div class="double_row">
                <input type="text" name="last_kanji"
                    value="<?= htmlspecialchars($_POST['last_kanji'] ?? '', ENT_QUOTES, 'UTF-8') ?>"
                    placeholder="姓 (漢字)" readonly>
                <input type="text" name="first_kanji"
                    value="<?= htmlspecialchars($_POST['first_kanji'] ?? '', ENT_QUOTES, 'UTF-8') ?>"
                    placeholder="名 (漢字)" readonly>
            </div>
            <div class="double_row">
                <input type="text" name="last_kana"
                    value="<?= htmlspecialchars($_POST['last_kana'] ?? '', ENT_QUOTES, 'UTF-8') ?>"
                    placeholder="姓 (カナ)" readonly>
                <input type="text" name="first_kana"
                    value="<?= htmlspecialchars($_POST['first_kana'] ?? '', ENT_QUOTES, 'UTF-8') ?>"
                    placeholder="名 (カナ)" readonly>
            </div>
            <input type="text" name="zip_code"
                value="<?= htmlspecialchars($_POST['zip_code'] ?? '', ENT_QUOTES, 'UTF-8') ?>"
                placeholder="郵便番号" readonly><br>
            <input type="text" name="prefectures"
                value="<?= htmlspecialchars($_POST['prefectures'] ?? '', ENT_QUOTES, 'UTF-8') ?>"
                placeholder="都道府県" readonly><br>
            <input type="text" name="street_address"
                value="<?= htmlspecialchars($_POST['street_address'] ?? '', ENT_QUOTES, 'UTF-8') ?>"
                placeholder="住所" readonly><br>
            <input type="text" name="mansion"
                value="<?= htmlspecialchars($_POST['mansion'] ?? '', ENT_QUOTES, 'UTF-8') ?>"
                placeholder="マンション名・部屋番号 (任意)" readonly>
        </fieldset>
        <fieldset>
            <h3>予定日</h3>
            <?php
            // 現在の日付を取得
            $currentDate = new DateTime();

            // 2日から4日後の日付を生成して表示
            for ($i = 2; $i <= 4; $i++) {
                if ($i == 2) {
                    $futureDate = clone $currentDate;
                    $futureDate->modify("+{$i} days");
                    $formattedDate = $futureDate->format('Y-m-d'); // YYYY-MM-DD形式にフォーマット
                    echo "<label>";
                    echo "<input type='radio' checked name='schedule_date' value='{$formattedDate}'> {$formattedDate}";
                    echo "</label><br>";
                } else {
                    $futureDate = clone $currentDate;
                    $futureDate->modify("+{$i} days");
                    $formattedDate = $futureDate->format('Y-m-d'); // YYYY-MM-DD形式にフォーマット
                    echo "<label>";
                    echo "<input type='radio'name='schedule_date' value='{$formattedDate}'> {$formattedDate}";
                    echo "</label><br>";
                }
            }
            ?>
        </fieldset>

        <?php foreach ($merchandise_ids as $id): ?>
            <input type="hidden" name="merchandise_ids[]" value="<?= htmlspecialchars($id) ?>">
        <?php endforeach; ?>
        <button>
            確定
        </button>

    </form>
    <footer></footer>

</body>

</html>