<?php
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $admin_username = $_POST['username'] ?? '';
    $admin_password = $_POST['password'] ?? '';
    $stmt = $pdo->prepare("SELECT * FROM admins WHERE username = ?");
    $stmt->execute([$admin_username]);
    $admin = $stmt->fetch();
    if ($admin && password_verify($admin_password, $admin['password'])) {
        session_start();
        $_SESSION['admin_logged_in'] = true;
        header("Location: admin_home.php");
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
                <input type="email" name="mail_address" placeholder="ID" required><br>
                <input type="password" name="pass_word" placeholder="パスワード" required><br>
                <div class="button-container">
                    <button type="submit" class="login-button">ログイン</button>
                </div>
            </form>
        </div>
    </main>
</body>

</html>

