<?php
session_start();
    require_once '../server/connect_database.php';
    $pdo = connect_database();

    $search = '';
    if (isset($_POST['search'])) {
        $search = htmlspecialchars($_POST['search'], ENT_QUOTES, 'UTF-8');
        $stmt = $pdo->prepare("SELECT * FROM user WHERE name LIKE ?");
        $stmt->execute(['%' . $search . '%']);
        $users = $stmt->fetchAll(PDO::FETCH_ASSOC);
    } else {
        $stmt = $pdo->prepare("SELECT * FROM user");
        $users = $stmt->fetchAll(PDO::FETCH_ASSOC);
    }


    if (isset($_POST['delete_id'])) {
        foreach ($_POST['delete_id'] as $row) {
            $sql = $pdo->prepare('DELETE FROM user WHERE user_id=?');
            $sql->execute([$row]);
        }
    }

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
                    <li><a href="g3_user_list_view.php">ユーザー管理</a></li>
                    <li><a href="g4_item_list_view.php">商品管理</a></li>
                    <li><a href="g5_item_update_view.php">管理者登録</a></li>
                    <li><a href="../server/logout.php">ログアウト</a></li>
                </ul>
            </nav>

            <div class="main-content">
                <h1>管理者一覧</h1>
                <form method="post" class="search-form">
                    <input type="search" name="search" placeholder="名前検索">
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
                        <?php if (isset($users)): ?>
                            <?php foreach ($users as $user): ?>
                                <tr>
                                    <td><?= htmlspecialchars($user['user_id'], ENT_QUOTES, 'UTF-8'); ?></td>
                                    <td><?= htmlspecialchars($user['name'], ENT_QUOTES, 'UTF-8'); ?></td>
                                    <td><?= htmlspecialchars($user['mail_address'], ENT_QUOTES, 'UTF-8'); ?></td>
                                    <td>
                                        <form method="post" style="display:inline;">
                                            <input type="hidden" name="delete_id[]" value="<?= $user['user_id']; ?>">
                                            <button type="submit" onclick="return confirm('本当に削除しますか？');">削除</button>
                                        </form>
                                        <a href="admin_edit.php?id=<?= $user['id']; ?>">編集</a>
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

        table th,
        table td {
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