<?php

$dsn = 'mysql:host=mysql305.phy.lolipop.lan;dbname=LAA1602705-php2024;charset=utf8';

$username = 'LAA1602705';

$password = 'Itou0315';

$search = '';

$users = [];

try {

    $pdo = new PDO($dsn, $username, $password);

    if ($_SERVER['REQUEST_METHOD'] === 'GET' && isset($_GET['search'])) {

        $search = $_GET['search'];

        $stmt = $pdo->prepare("SELECT * FROM users WHERE name LIKE :search OR email LIKE :search");

        $stmt->execute([':search' => '%' . $search . '%']);

    } else {

        $stmt = $pdo->query("SELECT * FROM users");

    }

    $users = $stmt->fetchAll();

} catch (PDOException $e) {

    echo "エラー: " . $e->getMessage();

}

?>
<!DOCTYPE html>
<html lang="ja">
<head>
<meta charset="UTF-8">
<title>ユーザー一覧</title>
<link rel="stylesheet" href="style.css">
</head>
<body>
<h1>ユーザー一覧</h1>
<form method="get">
<input type="text" name="search" placeholder="名前またはメールアドレスを検索" value="<?= htmlspecialchars($search) ?>">
<button type="submit">検索</button>
</form>
<table>
<tr>
<th>ID</th>
<th>名前</th>
<th>メールアドレス</th>
</tr>
<?php if (!empty($users)): ?>
<?php foreach ($users as $user): ?>
<tr>
<td><?= htmlspecialchars($user['id']) ?></td>
<td><?= htmlspecialchars($user['name']) ?></td>
<td><?= htmlspecialchars($user['email']) ?></td>
</tr>
<?php endforeach; ?>
<?php else: ?>
<tr>
<td colspan="3">該当するユーザーが見つかりませんでした。</td>
</tr>
<?php endif; ?>
</table>
</body>
</html> 