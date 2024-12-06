<?php

$dsn = 'mysql:host=mysql305.phy.lolipop.lan;dbname=LAA1602705-php2024;charset=utf8';

$username = 'LAA1602705';

$password = 'Itou0315';

$item = null;

$message = '';

// 商品情報を取得

if (isset($_GET['merchandise_id'])) {

    try {

        $pdo = new PDO($dsn, $username, $password);

        $stmt = $pdo->prepare("SELECT * FROM merchandise WHERE merchandise_id =?");

        $stmt->execute([$_GET['merchandise_id']]);

        $merchandise = $stmt->fetch(PDO::FETCH_ASSOC);
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

        $stmt = $pdo->prepare("UPDATE merchandise SET merchandise_name = :name, price = :price, description = :description WHERE merchandise_id = :id");

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
    <link rel="stylesheet" href="../style/g5_style.css">
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
                    <li><a href="g4_item_list_view.php" class="active">商品一覧</a></li>
                    <li><a href="g2_sign_up_view.php">管理者登録</a></li>
                </ul>
                <a href="../server/logout.php" class="logout-button">ログアウト</a>
            </nav>

            <section class="content">
            <h1>商品更新</h1>
            <form method="POST" enctype="multipart/form-data" class="form">
               <div class="form-group">
                  <label for="name">商品名:</label>
                  <input type="text" id="name" name="name" required　value="<?= htmlspecialchars($merchandise['merchandise_name']) ?>">
               </div>
               <div class="form-group">
                  <label for="price">価格:</label>
                  <input type="number" id="price" name="price" required value="<?= htmlspecialchars($merchandise['price']) ?>">
               </div>
               <div class="form-group">
                  <label for="description">説明:</label>
                  <textarea id="description" name="description" required value="<?= htmlspecialchars($merchandise['explanation']) ?>"></textarea>
               </div>
               <div class="form-group">
                  <label for="category">カテゴリー:</label>
                  <select id="category" name="category" required value="<?= htmlspecialchars($merchandise['category']) ?>">
                     <option value="直送">直送</option>
                     <option value="旬物">旬物</option>
                     <option value="名物">名物</option>
                     <option value="その他">その他</option>
                  </select>
               </div>
               <div class="form-group">
                  <label for="stock">在庫:</label>
                  <input type="number" id="stock" name="stock" required value="<?= htmlspecialchars($merchandise['stook']) ?>">
               </div>
               <div class="form-group">
                  <label for="image">:画像</label>
                  <input type="text" id="image" name="image" placeholder="https.." required value="<?= htmlspecialchars($merchandise['image']) ?>">
               </div>
               <button type="submit" class="btn">登録</button>
            </form>
            <?php if ($message) {
               echo "<p class='success'>$message</p>";
            } ?>
         </section>
        </main>
    </div>
</body>

</html>