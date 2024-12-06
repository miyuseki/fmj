<?php

$dsn = 'mysql:host=mysql305.phy.lolipop.lan;dbname=LAA1602705-php2024;charset=utf8';

$username = 'LAA1602705';

$password = 'Itou0315';

$item = null;

$message = '';

// 商品情報を取得

if (isset($_GET['id'])) {

    try {

        $pdo = new PDO($dsn, $username, $password);

        $stmt = $pdo->prepare("SELECT * FROM items WHERE id = :id");

        $stmt->execute([':id' => $_GET['id']]);

        $item = $stmt->fetch(PDO::FETCH_ASSOC);

    } catch (PDOException $e) {

        echo "エラー: " . $e->getMessage();

    }

}

// 商品情報を更新

if ($_SERVER['REQUEST_METHOD'] === 'POST') {

    $id = $_POST['id'];

    $name = $_POST['name'];

    $price = $_POST['price'];

    $description = $_POST['description'];

    try {

        $pdo = new PDO($dsn, $username, $password);

        $stmt = $pdo->prepare("UPDATE items SET name = :name, price = :price, description = :description WHERE id = :id");

        $stmt->execute([

            ':id' => $id,

            ':name' => $name,

            ':price' => $price,

            ':description' => $description,

        ]);

        $message = "商品情報を更新しました。";

    } catch (PDOException $e) {

        echo "エラー: " . $e->getMessage();

    }

}

?>
<!DOCTYPE html>
<html lang="ja">
<head>
<meta charset="UTF-8">
<title>商品更新</title>
<link rel="stylesheet" href="style.css">
</head>
<body>
<h1>商品更新</h1>
<?php if ($message): ?>
<p><?= htmlspecialchars($message) ?></p>
<?php endif; ?>
<?php if ($item): ?>
<form method="post">
<input type="hidden" name="id" value="<?= htmlspecialchars($item['id']) ?>">
<label for="name">商品名:</label>
<input type="text" name="name" id="name" value="<?= htmlspecialchars($item['name']) ?>" required>
<label for="price">価格:</label>
<input type="number" name="price" id="price" value="<?= htmlspecialchars($item['price']) ?>" required>
<label for="description">説明:</label>
<textarea name="description" id="description" required><?= htmlspecialchars($item['description']) ?></textarea>
<button type="submit">更新</button>
</form>
<?php else: ?>
<p>該当する商品が見つかりませんでした。</p>
<?php endif; ?>
</body>
</html> 