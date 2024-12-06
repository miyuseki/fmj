<?php
session_start();
if (!isset($_SESSION['admin_logged_in'])) {
    header("Location: g1_login_view.php");
    exit();
}

require_once '../server/connect_database.php';
$pdo = connect_database();
$success_message = '';
$error_message = '';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $id = $_POST['ID'];
    $password = $_POST['password'];
    $password_check = $_POST['password_check'];

    try {
        // パスワード再確認チェック
        if ($password !== $password_check) {
            $error_message = "パスワードが一致しません。再確認してください。";
        } else {
            // IDの重複チェック
            $stmt = $pdo->prepare("SELECT * FROM management WHERE management_id = ?");
            $stmt->execute([$id]);
            $admin = $stmt->fetch(PDO::FETCH_ASSOC);

            if ($admin) {
                $error_message = "このIDは既に登録されています。";
            } else {
                // 新規ユーザー登録
                $stmt = $pdo->prepare("INSERT INTO management (management_id, management_pass) VALUES (?, ?)");
                $stmt->execute([$id, $password]);

                $success_message = "登録成功しました。";
            }
        }
    } catch (Exception $e) {
        $error_message = "登録中にエラーが発生しました: " . htmlspecialchars($e->getMessage());
    }
}
?>

<!DOCTYPE html>
<html lang="ja">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>ユーザー管理</title>
    <link rel="stylesheet" href="../style/g2_style.css">
</head>

<body>
    <div class="container">
        <header>
            <h1>FMJ</h1>
        </header>
        <main>
            <nav class="sidebar">
                <ul>
                    <li><a href="g3_user_list_view.php">ユーザー管理</a></li>
                    <li><a href="g4_item_list_view">商品一覧</a></li>
                    <li><a href="#" class="active">管理者登録</a></li>
                </ul>
                <a href="../server/logout.php" class="logout-button">ログアウト</a>
            </nav>
            <section class="content">
                <!-- 登録フォーム -->
                <form action="#" method="post">
                    <label for="ID">ID</label><br>
                    <input type="text" name="ID" id="ID" required><br>

                    <label for="password">パスワード</label><br>
                    <input type="password" name="password" id="password" required><br>

                    <label for="password_check">パスワードの再確認</label><br>
                    <input type="password" name="password_check" id="password_check" required><br>

                    <button type="submit">登録</button><br>

                    <!-- メッセージの表示 -->
                    <span style="color: #4fec30; font-weight:bold"><?= $success_message ?></span>
                    <span style="color: red; font-weight:bold"><?= $error_message ?></span>
                </form>
            </section>
        </main>
    </div>
</body>

</html>