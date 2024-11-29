<?php
session_start();
require_once '../server/f0_connect_database.php';
require_once '../server/parse_string_to_array.php';


// ログイン確認
if (!isset($_SESSION['user'])) {
    header("Location: login.php");
    exit();
}

$user_id = $_SESSION['user'];

try {
    $pdo = connect_database();

    // ユーザーデータを取得
    $stmt = $pdo->prepare("SELECT * FROM user WHERE user_id = ?");
    $stmt->execute([$user_id]);
    $user = $stmt->fetch(PDO::FETCH_ASSOC);

    // ／区切りで保存したデータを分割して連想配列に保存
    $name = parseStringToArray($user['name']);
    $address = parseStringToArray($user['address']);

    if (!$user) {
        echo "ユーザーが見つかりません。";
        exit();
    }
} catch (PDOException $e) {
    echo "データベース接続エラー: " . $e->getMessage();
    exit();
}

?>
<!DOCTYPE html>
<html lang="jp">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="../style/g4_style.css"> 

    <title>マイページ画面</title>
</head>

<body>
    <header>
        <a href="g3_home.php">ホームに戻る</a>
        <a href="../server/logout.php">ログアウト</a>
    </header>
    <h1>FMJ</h1>
    <h2>マイページ</h2>

    <?php if (isset($message)) : ?>
    <p class="message">
        <?= htmlspecialchars($message, ENT_QUOTES, 'UTF-8') ?>
    </p>
    <?php endif; ?>

    <fieldset>
        <h3>ログイン情報</h3>
        <input type="text" name="mail_address" readonly
            value="<?= htmlspecialchars($user['mail_address'], ENT_QUOTES, 'UTF-8') ?>" readonly>
    </fieldset>

    <fieldset>
        <h3>住所情報</h3>
        <div class="double_row">
            <input type="text" name="last_kanji" readonly
                value="<?= htmlspecialchars($name['last_kanji'], ENT_QUOTES, 'UTF-8') ?>">
            <input type="text" name="first_kanji" readonly
                value="<?= htmlspecialchars($name['first_kanji'], ENT_QUOTES, 'UTF-8') ?>">
        </div>
        <div class="double_row">
            <input type="text" name="last_kana" readonly
                value="<?= htmlspecialchars($name['last_kana'], ENT_QUOTES, 'UTF-8') ?>">
            <input type="text" name="first_kana" readonly
                value="<?= htmlspecialchars($name['first_kana'], ENT_QUOTES, 'UTF-8') ?>">
        </div>
        <input type="text" name="zip_code" readonly
            value="<?= htmlspecialchars($user['zip_code'], ENT_QUOTES, 'UTF-8') ?>">
        <input type="text" name="prefectures" readonly
            value="<?= htmlspecialchars($address['prefectures'], ENT_QUOTES, 'UTF-8') ?>">
        <input type="text" name="street_address" readonly
            value="<?= htmlspecialchars($address['street_address'], ENT_QUOTES, 'UTF-8') ?>">
        <input type="text" name="mansion" readonly
            value="<?= htmlspecialchars($user['mansion'], ENT_QUOTES, 'UTF-8') ?>">
    </fieldset>

    <div class="button-container">
        <a href="g11_user_information.php" class="button">
            <span class="material-symbols-outlined">
                person
            </span>
            ユーザー情報変更
        </a>
        <a href="g13_wishlist.php" class="button">
            <span class="material-symbols-outlined">
                favorite
            </span>
            ウィッシュリスト
        </a>
        <a href="g5_review.php" class="button">
            <span class="material-symbols-outlined">
                history
            </span>
            レビュー履歴
        </a>
    </div>
</body>

</html>