<?php
session_start();
require_once '../server/f0_connect_database.php';

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
    <link rel="stylesheet" href="../style/g11_style.css">

    <body>
        <header>
            <a href="g4_my_page.php">戻る</a>
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