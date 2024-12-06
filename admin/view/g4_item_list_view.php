<?php

$dsn = 'mysql:host=mysql305.phy.lolipop.lan;dbname=LAA1602705-php2024;charset=utf8';

$username = 'LAA1602705';

$password = 'Itou0315';

$search = '';

$items = [];

try {

    $pdo = new PDO($dsn, $username, $password);

    if ($_SERVER['REQUEST_METHOD'] === 'GET' && isset($_GET['search'])) {

        $search = $_GET['search'];

        $stmt = $pdo->prepare("SELECT * FROM items WHERE name LIKE :search OR description LIKE :search");

        $stmt->execute([':search' => '%' . $search . '%']);

    } else {

        $stmt = $pdo->query("SELECT * FROM items");

    }

    $items = $stmt->fetchAll();

} catch (PDOException $e) {

    echo "エラー: " . $e->getMessage();

}

?>
<!DOCTYPE html>
<html lang="ja">
<head>
<meta charset="UTF-8">
<title>商品一覧</title>
<link rel="stylesheet" href="style.css">
</head>
<body>
<h1>商品一覧</h1>
<form method="get">
<input type="text" name="search" placeholder="商品名または説明を検索" value="<?= htmlspecialchars($search) ?>">
<button type="submit">検索</button>
</form>
<table>
<tr>
<th>ID</th>
<th>商品名</th>
<th>価格</th>
<th>説明</th>
</tr>
<?php if (!empty($items)): ?>
<?php foreach ($items as $item): ?>
<tr>
<td><?= htmlspecialchars($item['id']) ?></td>
<td><?= htmlspecialchars($item['name']) ?></td>
<td><?= htmlspecialchars($item['price']) ?></td>
<td><?= htmlspecialchars($item['description']) ?></td>
</tr>
<?php endforeach; ?>
<?php else: ?>
<tr>
<td colspan="4">該当する商品が見つかりませんでした。</td>
</tr>
<?php endif; ?>
</table>
</body>
</html> 