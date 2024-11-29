<?php
session_start();
require_once '../server/f0_connect_database.php';
require_once '../server/parse_string_to_array.php';

$user_id = null;
$user = [];
$name = [];
$address = [];
$is_logged_in = false;

// ログイン確認
if (isset($_SESSION['user'])) {
    $user_id = $_SESSION['user'];
    $is_logged_in = true;

    try {
        $pdo = connect_database();

        // ユーザーデータを取得
        $stmt = $pdo->prepare("SELECT * FROM user WHERE user_id = ?");
        $stmt->execute([$user_id]);
        $user = $stmt->fetch(PDO::FETCH_ASSOC);

        if ($user) {
            // ／区切りで保存したデータを分割して連想配列に保存
            $name = parseStringToArray($user['name']);
            $address = parseStringToArray($user['address']);
        } else {
            $is_logged_in = false; // ユーザーが見つからない場合
        }
    } catch (PDOException $e) {
        echo "データベース接続エラー: " . $e->getMessage();
        exit();
    }
}
?>
<!DOCTYPE html>
<html lang="jp">
<head>
<meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="../style/g9_style.css">
    <title>購入</title>
</head>
<body>
    <header>
        <a href="g3_home.php">ホームに戻る</a>
    </header>
    <h1>FMJ</h1>

    <?php if (isset($message)) : ?>
        <p class="message">
            <?= htmlspecialchars($message, ENT_QUOTES, 'UTF-8') ?>
        </p>
    <?php endif; ?>

    <form action="g10_change_user_information.php.php" method="post">
        <fieldset>
            <h3>商品の届け先を記入してください</h3>
            <h3>住所情報</h3>
            <div class="double_row">
                <input type="text" name="last_kanji"
                    value="<?= htmlspecialchars($is_logged_in ? $name['last_kanji'] : '', ENT_QUOTES, 'UTF-8') ?>"
                    placeholder="姓 (漢字)">
                <input type="text" name="first_kanji"
                    value="<?= htmlspecialchars($is_logged_in ? $name['first_kanji'] : '', ENT_QUOTES, 'UTF-8') ?>"
                    placeholder="名 (漢字)">
            </div>
            <div class="double_row">
                <input type="text" name="last_kana"
                    value="<?= htmlspecialchars($is_logged_in ? $name['last_kana'] : '', ENT_QUOTES, 'UTF-8') ?>"
                    placeholder="姓 (カナ)">
                <input type="text" name="first_kana"
                    value="<?= htmlspecialchars($is_logged_in ? $name['first_kana'] : '', ENT_QUOTES, 'UTF-8') ?>"
                    placeholder="名 (カナ)">
            </div>
            <input type="text" name="zip_code"
                value="<?= htmlspecialchars($is_logged_in ? $user['zip_code'] : '', ENT_QUOTES, 'UTF-8') ?>"
                placeholder="郵便番号">
            <input type="text" name="prefectures"
                value="<?= htmlspecialchars($is_logged_in ? $address['prefectures'] : '', ENT_QUOTES, 'UTF-8') ?>"
                placeholder="都道府県">
            <input type="text" name="street_address"
                value="<?= htmlspecialchars($is_logged_in ? $address['street_address'] : '', ENT_QUOTES, 'UTF-8') ?>"
                placeholder="住所">
            <input type="text" name="mansion"
                value="<?= htmlspecialchars($is_logged_in ? $user['mansion'] : '', ENT_QUOTES, 'UTF-8') ?>"
                placeholder="マンション名・部屋番号 (任意)">
        </fieldset>

        <fieldset>
            <h3>支払い方法</h3>
            <input type="radio" name="pay" checked value="default">代金引換
        </fieldset>

        <?php
        $merchandise_ids = $_POST['merchandise_ids'] ?? [];
        ?>

        <?php foreach ($merchandise_ids as $id): ?>
            <input type="hidden" name="merchandise_ids[]" value="<?= htmlspecialchars($id) ?>">
        <?php endforeach; ?>


        <button>確定</button>
    </form>

</body>

</html>