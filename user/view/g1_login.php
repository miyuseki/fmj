<?php
session_start();
require_once '../server/f0_connect_database.php';

if ($_SERVER["REQUEST_METHOD"] === "POST") {
    try {
        $pdo = connect_database();
        $sql = $pdo->prepare('SELECT * FROM user WHERE mail_address = ? and pass = ?');
        $sql->execute([$_POST['email'], $_POST['password']]);
        $user = $sql->fetch();

        if ($user) {
            $_SESSION['user'] = $user['user_id'];
            header('Location: home.php');
            exit();
        } else {
            $error_message = 'ログインに失敗しました。メールアドレスまたはパスワードが間違っています。';
        }
    } catch (PDOException $exception) {
        echo "Connection error: " . $exception->getMessage();
    }
}
?>
<!DOCTYPE html>
<html lang="jp">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="../style/g1_style.css">

    <title>検索結果画面</title>
</head>

<body>
    <h1>FMJ</h1>
    <form action="#" method="post">
        <input type="email" name="email" placeholder="メールアドレス" required><br>
        <input type="password" name="password" placeholder="パスワード" required><br>
        <div class="center-button"><button>ログイン</button></div>
        <?php if (!empty($error_message)): ?>
            <p class="error-message"><?= htmlspecialchars($error_message) ?></p>
        <?php endif; ?>
    
            
        </form>

        <p class="register-link">新規登録は<a href="g2_signup.php">こちら</a>から</p>

        <p class="home-link"><a href="g3_home.php">ホームに戻る</a></p>

</body>

</html>

