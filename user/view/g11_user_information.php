<?php
session_start();
require_once '../server/f0_connect_database.php';
require_once '../server/parse_string_to_array.php';


// ログイン確認
if (!isset($_SESSION['user'])) {
    header("Location: g1_login.php");
    exit();
}

$user_id = $_SESSION['user'];
$message = '';

try {
    $pdo = connect_database();

    // パスワード変更処理
    if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['now_password'], $_POST['new_password'], $_POST['new_password_check'])) {
        $now_password = $_POST['now_password'];
        $new_password = $_POST['new_password'];
        $new_password_check = $_POST['new_password_check'];

        if ($now_password === $user['pass']) {
            if ($new_password === $new_password_check) {
                $stmt = $pdo->prepare("UPDATE user SET pass = ? WHERE user_id = ?");
                $stmt->execute([$new_password, $user_id]);
                $message = "パスワードが更新されました。";
            } else {
                $message = "新しいパスワードが一致しません。";
            }
        } else {
            $message = "現パスワードが正しくありません。";
        }
    }

    // メールアドレス変更処理
    if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['mail_address'])) {
        $mail_address = $_POST['mail_address'];
        $stmt = $pdo->prepare("UPDATE user SET mail_address = ? WHERE user_id = ?");
        $stmt->execute([$mail_address, $user_id]);
        $message = "メールアドレスが更新されました。";
    }

    // 住所情報変更処理
    if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['last_kanji'], $_POST['first_kanji'], $_POST['zip_code'], $_POST['prefectures'], $_POST['street_address'], $_POST['mansion'])) {

        $last_kanji = htmlspecialchars($_POST['last_kanji'], ENT_QUOTES, 'UTF-8');
        $first_kanji = htmlspecialchars($_POST['first_kanji'], ENT_QUOTES, 'UTF-8');
        $zip_code = htmlspecialchars($_POST['zip_code'], ENT_QUOTES, 'UTF-8');
        $prefectures = htmlspecialchars($_POST['prefectures'], ENT_QUOTES, 'UTF-8');
        $street_address = htmlspecialchars($_POST['street_address'], ENT_QUOTES, 'UTF-8');
        $mansion = htmlspecialchars($_POST['mansion'], ENT_QUOTES, 'UTF-8');

        $last_kana = isset($_POST['last_kana']) ? htmlspecialchars($_POST['last_kana'], ENT_QUOTES, 'UTF-8') : '';
        $first_kana = isset($_POST['first_kana']) ? htmlspecialchars($_POST['first_kana'], ENT_QUOTES, 'UTF-8') : '';

        // 名前情報を生成
        $name = 'last_kanji/' . $last_kanji . '/first_kanji/' . $first_kanji .
            '/last_kana/' . $last_kana . '/first_kana/' . $first_kana;

        // 住所情報を生成
        $address = 'prefectures/' . $prefectures .
            '/street_address/' . $street_address .
            '/mansion/' . $mansion;

        $stmt = $pdo->prepare("UPDATE user SET name = ?, address = ?, zip_code = ?, mansion = ? WHERE user_id = ?");
        $stmt->execute([$name, $address, $_POST['zip_code'], $_POST['mansion'], $user_id]);
        $message = "住所情報が更新されました。";
    }
} catch (PDOException $e) {
    echo "データベース接続エラー: " . $e->getMessage();
    exit();
}

// ユーザーデータを取得
$stmt = $pdo->prepare("SELECT * FROM user WHERE user_id = ?");
$stmt->execute([$user_id]);
$user = $stmt->fetch(PDO::FETCH_ASSOC);

// ／区切りで保存したデータを分割して連想配列に保存
$name = parseStringToArray($user['name']);
$address = parseStringToArray($user['address']);
?>
<!DOCTYPE html>
<html lang="jp">
<head>
<meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="../style/g11_style.css">

    <body>
        <header>
            <a href="g4_mypage.php">戻る</a>
            <a href="../server/logout.php">ログアウト</a>
        </header>
        <h1>FMJ</h1>
        <h2>マイページ</h2>
    
        <?php if (isset($message)) : ?>
            <p class="message">
                <?= htmlspecialchars($message, ENT_QUOTES, 'UTF-8') ?>
            </p>
        <?php endif; ?>
    
        <!-- パスワード変更フォーム -->
        <fieldset id="passwordForm">
            <form id="registrationForm" method="post">
                <input type="password" id="nowPassword" name="now_password" required placeholder="現パスワード">
                <div id="nowPasswordError" class="message"></div>
    
                <input type="password" id="newPassword" name="new_password" required placeholder="新パスワード">
                <div id="newPasswordError" class="message"></div>
    
                <input type="password" id="newPasswordCheck" name="new_password_check" required placeholder="新パスワード(確認)">
                <div id="newPasswordMatchError" class="message"></div>
    
                <input type="submit" value="変更">
            </form>
        </fieldset>
    
        <!-- メールアドレス変更フォーム -->
        <fieldset>
            <form action="" method="post">
                <input type="email" name="mail_address"
                    value="<?= htmlspecialchars($user['mail_address'], ENT_QUOTES, 'UTF-8') ?>" required
                    placeholder="メールアドレス">
                <input type="submit" value="変更">
            </form>
        </fieldset>
    
        <!-- 住所情報変更フォーム -->
        <fieldset>
            <form action="" method="post">
                <div class="double_row">
                    <input type="text" name="last_kanji"
                        value="<?= htmlspecialchars($name['last_kanji'], ENT_QUOTES, 'UTF-8') ?>" required placeholder="姓">
                    <input type="text" name="first_kanji"
                        value="<?= htmlspecialchars($name['first_kanji'], ENT_QUOTES, 'UTF-8') ?>" required placeholder="名">
                </div>
                <div class="double_row">
                    <input type="text" name="last_kana"
                        value="<?= htmlspecialchars($name['last_kana'], ENT_QUOTES, 'UTF-8') ?>" required placeholder="セイ">
                    <input type="text" name="first_kana"
                        value="<?= htmlspecialchars($name['first_kana'], ENT_QUOTES, 'UTF-8') ?>" required placeholder="メイ">
                </div>
                <input type="text" name="zip_code" value="<?= htmlspecialchars($user['zip_code'], ENT_QUOTES, 'UTF-8') ?>"
                    required placeholder="郵便番号">
                <input type="text" name="prefectures"
                    value="<?= htmlspecialchars($address['prefectures'], ENT_QUOTES, 'UTF-8') ?>" required
                    placeholder="都道府県 市区町村">
                <input type="text" name="street_address"
                    value="<?= htmlspecialchars($address['street_address'], ENT_QUOTES, 'UTF-8') ?>" required
                    placeholder="番地">
                <input type="text" name="mansion" value="<?= htmlspecialchars($user['mansion'], ENT_QUOTES, 'UTF-8') ?>"
                    placeholder="マンション">
                <input type="submit" value="変更">
            </form>
        </fieldset>
    
        <script>
            const form = document.getElementById('registrationForm');
            const nowPassword = document.getElementById('nowPassword');
            const newPassword = document.getElementById('newPassword');
            const newPasswordCheck = document.getElementById('newPasswordCheck');
    
            const nowPasswordError = document.getElementById('nowPasswordError');
            const newPasswordError = document.getElementById('newPasswordError');
            const newPasswordMatchError = document.getElementById('newPasswordMatchError');
    
            form.addEventListener('submit', (e) => {
                let valid = true;
    
                // 現パスワードチェック（例として空でないか確認）
                if (!nowPassword.value) {
                    nowPasswordError.textContent = '現パスワードを入力してください。';
                    valid = false;
                } else {
                    nowPasswordError.textContent = '';
                }
    
                // 新パスワード形式チェック
                const passwordRegex = /^(?=.*[a-zA-Z])(?=.*\d)[A-Za-z\d]{8,}$/;
                if (!passwordRegex.test(newPassword.value)) {
                    newPasswordError.textContent = 'パスワードは英数字8文字以上で入力してください。';
                    valid = false;
                } else {
                    newPasswordError.textContent = '';
                }
    
                // 新パスワードの一致確認
                if (newPassword.value !== newPasswordCheck.value) {
                    newPasswordMatchError.textContent = 'パスワードが一致しません。';
                    valid = false;
                } else {
                    newPasswordMatchError.textContent = '';
                }
    
                // バリデーション失敗時に送信を防止
                if (!valid) {
                    e.preventDefault();
                }
            });
        </script>
    
    </body>
    
    </html>