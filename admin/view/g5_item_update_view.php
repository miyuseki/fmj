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

$item_id = isset($_GET['id']) ? (int)$_GET['id'] : 0;
$stmt = $pdo->prepare("SELECT * FROM items WHERE id = ?");
$stmt->execute([$item_id]);
$item = $stmt->fetch(PDO::FETCH_ASSOC);

if (!$item) {
    echo "商品が見つかりません。";
    exit();
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $name = htmlspecialchars($_POST['name'], ENT_QUOTES, 'UTF-8');
    $price = (int)$_POST['price'];
    $description = htmlspecialchars($_POST['description'], ENT_QUOTES, 'UTF-8');

    $updateStmt = $pdo->prepare("UPDATE items SET name = ?, price = ?, description = ? WHERE id = ?");
    $updateStmt->execute([$name, $price, $description, $item_id]);

    header("Location: manage_products.php?update_success=1");
    exit();
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

    <title>商品情報更新</title>
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
                    <li><a href="manage_users.php">ユーザー管理</a></li>
                    <li><a href="manage_products.php">商品管理</a></li>
                    <li><a href="admin_register.php">管理者登録</a></li>
                    <li><a href="admin_login.php?logout">ログアウト</a></li>
                </ul>
            </nav>

            <div class="main-content">
                <h1>商品情報更新</h1>
                <form method="post" class="update-form">
                    <div class="form-group">
                        <label for="name">商品名:</label>
                        <input type="text" id="name" name="name" value="<?= htmlspecialchars($item['name'], ENT_QUOTES, 'UTF-8'); ?>" required>
                    </div>

                    <div class="form-group">
                        <label for="price">価格:</label>
                        <input type="number" id="price" name="price" value="<?= $item['price']; ?>" required>
                    </div>

                    <div class="form-group">
                        <label for="description">説明:</label>
                        <textarea id="description" name="description" rows="5" required><?= htmlspecialchars($item['description'], ENT_QUOTES, 'UTF-8'); ?></textarea>
                    </div>

                    <div class="form-actions">
                        <button type="submit" class="btn-primary">更新</button>
                        <a href="manage_products.php" class="btn-secondary">キャンセル</a>
                    </div>
                </form>
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

        .update-form {
            max-width: 600px;
            margin: auto;
        }

        .form-group {
            margin-bottom: 15px;
        }

        .form-group label {
            display: block;
            margin-bottom: 5px;
            font-weight: bold;
        }

        .form-group input, .form-group textarea {
            width: 100%;
            padding: 10px;
            border: 1px solid #ddd;
            border-radius: 4px;
        }

        .form-actions {
            display: flex;
            justify-content: space-between;
        }

        .btn-primary {
            background-color: #007bff;
            color: #fff;
            padding: 10px 15px;
            border: none;
            border-radius: 4px;
            text-decoration: none;
        }

        .btn-secondary {
            background-color: #6c757d;
            color: #fff;
            padding: 10px 15px;
            border: none;
            border-radius: 4px;
            text-decoration: none;
        }
    </style>
</body>

</html>