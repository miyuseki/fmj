<?php
session_start();

$dsn = 'mysql:host=mysql305.phy.lolipop.lan;dbname=LAA1602705-php2024;charset=utf8';
$username = 'LAA1602705';
$password = 'Itou0315';

try {
    $pdo = new PDO($dsn, $username, $password);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch (PDOException $e) {
    echo "データベース接続失敗: " . $e->getMessage();
    exit();
}

$search = '';
if (isset($_POST['search'])) {
    $search = htmlspecialchars($_POST['search'], ENT_QUOTES, 'UTF-8');
    $stmt = $pdo->prepare("SELECT * FROM admins WHERE name LIKE ?");
    $stmt->execute(['%' . $search . '%']);
} else {
    $stmt = $pdo->query("SELECT * FROM admins");
}

if (isset($_POST['delete_id'])) {
    $delete_id = (int)$_POST['delete_id'];
    $deleteStmt = $pdo->prepare("DELETE FROM admins WHERE id = ?");
    $deleteStmt->execute([$delete_id]);
    header("Location: g3_user_list_view.php"); 
    exit();
}

$admins = $stmt->fetchAll(PDO::FETCH_ASSOC);
?>

<!DOCTYPE html>
<html lang="jp">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="../style/reset.css">
    <link rel="stylesheet" href="../style/style.css">
    <link rel="stylesheet" href="../style/common_style.css">
    <link rel="stylesheet" href="../style/g3_style.css">

    <title>管理者一覧</title>
</head>

<body>
    <header>
        <span class="header-left">
            FMJ
        </span>
        <span class="header-right">
            <a href="admin_dashboard.php">戻る</a>
        </span>
    </header>

    <main>
        <div class="container">
            <nav class="sidebar">
                <h3>管理メニュー</h3>
                <ul>
                    <li><a href="g2_sign_up_view.php">ユーザー管理</a></li>
                    <li><a href="g3_user_list_view.php">商品管理</a></li>
                    <li><a href="g4_item_list_view.php">管理者登録</a></li>
                    <li><a href="g1_login_view.php">ログアウト</a></li>
                </ul>
            </nav>

            <div class="main-content">
                <h1>管理者一覧</h1>
                <form method="post" class="search-form">
                    <input type="search" name="search" placeholder="名前検索" value="<?= htmlspecialchars($search, ENT_QUOTES, 'UTF-8'); ?>">
                    <button type="submit">検索</button>
                </form>

                <table>
                    <thead>
                        <tr>
                            <th>ID</th>
                            <th>名前</th>
                            <th>メールアドレス</th>
                            <th>操作</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php if (!empty($admins)): ?>
                            <?php foreach ($admins as $admin): ?>
                                <tr>
                                    <td><?= htmlspecialchars($admin['id'], ENT_QUOTES, 'UTF-8'); ?></td>
                                    <td><?= htmlspecialchars($admin['name'], ENT_QUOTES, 'UTF-8'); ?></td>
                                    <td><?= htmlspecialchars($admin['email'], ENT_QUOTES, 'UTF-8'); ?></td>
                                    <td>
                                        <form method="post" style="display:inline;">
                                            <input type="hidden" name="delete_id" value="<?= $admin['id']; ?>">
                                            <button type="submit" onclick="return confirm('本当に削除しますか？');">削除</button>
                                        </form>
                                        <a href="admin_edit.php?id=<?= $admin['id']; ?>">編集</a>
                                    </td>
                                </tr>
                            <?php endforeach; ?>
                        <?php else: ?>
                            <tr>
                                <td colspan="4">該当する管理者が見つかりません。</td>
                            </tr>
                        <?php endif; ?>
                    </tbody>
                </table>
            </div>
        </div>
    </main>

    <style>
        body {
            font-family: Arial, sans-serif;
        }

        header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            padding: 10px 20px;
            background-color: #f4f4f4;
            border-bottom: 1px solid #ddd;
        }

        .container {
            display: flex;
        }

        .sidebar {
            width: 20%;
            background-color: #f9f9f9;
            border-right: 1px solid #ddd;
            padding: 15px;
        }

        .sidebar ul {
            list-style: none;
            padding: 0;
        }

        .sidebar ul li {
            margin-bottom: 10px;
        }

        .main-content {
            width: 80%;
            padding: 20px;
        }

        table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 20px;
        }

        table th, table td {
            border: 1px solid #ddd;
            padding: 10px;
            text-align: left;
        }

        table th {
            background-color: #f4f4f4;
        }
    </style>
</body>

</html>
