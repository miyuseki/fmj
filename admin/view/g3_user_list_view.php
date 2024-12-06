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

    if (!empty($_POST['user_id']) && is_array($_POST['user_id'])) {
        $action = $_POST['action'];
        $selectedItems = $_POST['user_id'];
        if ($action == 'delete') {
            $stmt = $pdo->prepare("DELETE FROM user WHERE user_id IN (" . implode(",", array_fill(0, count($selectedItems), "?")) . ")");
            $stmt->execute($selectedItems);
            $success_message = "選択したユーザーが削除されました。";
        }
    } else {
        $error_message =  "削除対象のユーザーが選択されていません。";
    }
}

try {
    if (!empty($_GET['query'])) {
        $stmt = $pdo->prepare("SELECT * FROM user WHERE name LIKE ?");
        $like = '%' . $_GET['query'] . '%';
        $stmt->execute([$like]);
        $users = $stmt->fetchAll(PDO::FETCH_ASSOC);
    } else {
        $stmt = $pdo->prepare("SELECT * FROM user");
        $stmt->execute();
        $users = $stmt->fetchAll(PDO::FETCH_ASSOC);
    }
} catch (Exception $e) {
    echo "エラー: " . htmlspecialchars($e->getMessage());
}
?>

<!DOCTYPE html>
<html lang="ja">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>ユーザー管理</title>
    <link rel="stylesheet" href="../style/g3_style.css">
    <script>
        function confirmDelete() {
            return confirm("選択したユーザーを本当に削除しますか？");
        }

        function toggleAll(source) {
            const checkboxes = document.querySelectorAll('tbody input[type="checkbox"]');
            for (const checkbox of checkboxes) {
                checkbox.checked = source.checked;
            }
        }
    </script>
</head>

<body>
    <div class="container">
        <header>
            <h1>FMJ</h1>
        </header>
        <main>

            <nav class="sidebar">
                <ul>
                    <li><a href="#" class="active">ユーザー管理</a></li>
                    <li><a href="g4_item_list_view">商品一覧</a></li>
                    <li><a href="g2_sign_up_view.php">管理者登録</a></li>
                </ul>
                <a href="../server/logout.php" class="logout-button">ログアウト</a>
            </nav>

            <section class="content">
                <!-- 検索フォーム -->
                <form action="#" method="get">
                    <input type="search" name="query" placeholder="ユーザー名検索" value="<?php echo htmlspecialchars($_GET['query'] ?? ''); ?>">
                </form>

                <!-- 商品管理フォーム -->
                <form action="#" method="post">
                    <div class="button-group">
                        <button class="delete-button" name="action" value="delete" onclick="return confirmDelete();">削除</button>
                    </div>
                    <div style="color: #4fec30; font-weight:bold"><?= $success_message ?></div>
                    <div style="color: red; font-weight:bold"><?= $error_message ?></div>
                    <table class="table">
                        <thead>
                            <tr>
                                <th><input type="checkbox" onclick="toggleAll(this)"></th>
                                <th>ID</th>
                                <th>名前/th>
                                <th>メールアドレス</th>
                                <th>住所</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php if (!empty($users)): ?>
                                <?php foreach ($users as $user): ?>
                                    <tr>
                                        <td><input type="checkbox" name="user_id[]" value="<?php echo htmlspecialchars($user['user_id']); ?>"></td>
                                        <td><?php echo htmlspecialchars($user['user_id']); ?></td>
                                        <td><?php echo htmlspecialchars($user['name']); ?></td>
                                        <td><?php echo htmlspecialchars($user['mail_address']); ?></td>
                                        <td><?php echo htmlspecialchars($user['address']); ?></td>
                                    </tr>
                                <?php endforeach; ?>
                            <?php else: ?>
                                <tr>
                                    <td colspan="5">ユーザーが見つかりませんでした。</td>
                                </tr>
                            <?php endif; ?>
                        </tbody>
                    </table>
                </form>
            </section>
        </main>
    </div>
</body>

</html>