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

    if (!empty($_POST['merchandise_id']) && is_array($_POST['merchandise_id'])) {
        $action = $_POST['action'];
        $selectedItems = $_POST['merchandise_id'];
        if ($action == 'delete') {
            $stmt = $pdo->prepare("DELETE FROM merchandise WHERE merchandise_id IN (" . implode(",", array_fill(0, count($selectedItems), "?")) . ")");
            $stmt->execute($selectedItems);
            $success_message = "選択した商品が削除されました。";
        }
    } else {
        $error_message =  "削除対象の商品が選択されていません。";
    }
}

try {
    if (!empty($_GET['query'])) {
        $stmt = $pdo->prepare("SELECT * FROM merchandise WHERE merchandise_name LIKE ?");
        $like = '%' . $_GET['query'] . '%';
        $stmt->execute([$like]);
        $merchandises = $stmt->fetchAll(PDO::FETCH_ASSOC);
    } else {
        $stmt = $pdo->prepare("SELECT * FROM merchandise");
        $stmt->execute();
        $merchandises = $stmt->fetchAll(PDO::FETCH_ASSOC);
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
    <title>商品管理</title>
    <link rel="stylesheet" href="../style/g4_style.css">
    <script>
        function confirmDelete() {
            return confirm("選択した商品を本当に削除しますか？");
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
                    <li><a href="g3_user_list_view.php">ユーザー管理</a></li>
                    <li><a href="#" class="active">商品一覧</a></li>
                    <li><a href="g2_sign_up_view.php">管理者登録</a></li>
                </ul>
                <a href="../server/logout.php" class="logout-button">ログアウト</a>
            </nav>

            <section class="content">
                <!-- 検索フォーム -->
                <form action="#" method="get">
                    <input type="search" name="query" placeholder="商品名検索" value="<?php echo htmlspecialchars($_GET['query'] ?? ''); ?>">
                </form>

                <!-- 商品管理フォーム -->
                <form action="#" method="post">
                    <div class="button-group">
                        <a href="g6_item_register_view.php" class="edit-button">商品登録</a>
                        <button class="delete-button" name="action" value="delete" onclick="return confirmDelete();">削除</button>
                    </div>
                    <div style="color: #4fec30; font-weight:bold"><?= $success_message ?></div>
                    <div style="color: red; font-weight:bold"><?= $error_message ?></div>
                    <table class="table">
                        <thead>
                            <tr>
                                <th><input type="checkbox" onclick="toggleAll(this)"></th>
                                <th>商品ID</th>
                                <th>商品名</th>
                                <th>価格</th>
                                <th>在庫数</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php if (!empty($merchandises)): ?>
                                <?php foreach ($merchandises as $merchandise): ?>
                                    <tr>
                                        <td><input type="checkbox" name="merchandise_id[]" value="<?php echo htmlspecialchars($merchandise['merchandise_id']); ?>"></td>
                                        <td><a href="g5_item_update_view.php?merchandise_id=<?= htmlspecialchars($merchandise['merchandise_id']); ?>"><?php echo htmlspecialchars($merchandise['merchandise_id']); ?></a></td>
                                        <td><?php echo htmlspecialchars($merchandise['merchandise_name']); ?></td>
                                        <td><?php echo htmlspecialchars($merchandise['price']); ?></td>
                                        <td><?php echo htmlspecialchars($merchandise['stock']); ?></td>
                                    </tr>
                                <?php endforeach; ?>
                            <?php else: ?>
                                <tr>
                                    <td colspan="5">商品が見つかりませんでした。</td>
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