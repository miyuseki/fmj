<?php
session_start();
require_once '../server/connect_database.php';
$pdo = connect_database();

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $admin_username = $_POST['username'] ?? '';
    $admin_password = $_POST['password'] ?? '';
    $stmt = $pdo->prepare("SELECT * FROM management WHERE management_id = ? AND management_pass = ?");
    $stmt->execute([$admin_username, $admin_password]);
    $admin = $stmt->fetch();
    if ($admin) {
        session_start();
        $_SESSION['admin_logged_in'] = true;
        header("Location: g3_user_list_view.php");
        exit;
    } else {
        echo "Invalid username or password.";
    }
}
?>
<!DOCTYPE html>
<html lang="jp">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="../style/reset.css">
    <link rel="stylesheet" href="../style/g1_style.css">

    <title>ログイン</title>
</head>

<body>
    <header>
        <span class="header-left">FMJ</span>
        <span class="header-right">管理者</span>
    </header>

    <main>
        <div class="form">
            <h2>ログイン</h2>
            <?php if (isset($error_message)): ?>
                <p style="color: red;"><?= htmlspecialchars($error_message) ?></p>
            <?php endif; ?>
            <form action="" method="post">
                <input type="text" name="username" placeholder="ID" required><br>
                <input type="password" name="password" placeholder="パスワード" required><br>
                <div class="button-container">
                    <button type="submit" class="login-button">ログイン</button>
                </div>
            </form>
        </div>
    </main>
</body>

</html>