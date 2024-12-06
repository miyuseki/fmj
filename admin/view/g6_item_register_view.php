<?php

$dsn = 'mysql:host=mysql305.phy.lolipop.lan;dbname=LAA1602705-php2024;charset=utf8';
$username = 'LAA1602705';
$password = 'Itou0315';

$message = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
   $name = $_POST['name'];
   $price = $_POST['price'];
   $description = $_POST['description'];
   $category = $_POST['category'];
   $image = $_POST['image'];

   try {
      $pdo = new PDO($dsn, $username, $password);
      $stmt = $pdo->prepare("INSERT INTO merchandise (merchandise_name, price, explanation, category, image) VALUES (:name, :price, :description, :category, :image)");
      $stmt->execute([
         ':name' => $name,
         ':price' => $price,
         ':description' => $description,
         ':category' => $category,
         ':image' => $image,
      ]);
      $message = "商品を登録しました。";
   } catch (PDOException $e) {
      echo "エラー: " . $e->getMessage();
   }
}

?>
<!DOCTYPE html>
<html lang="ja">

<head>
   <meta charset="UTF-8">
   <meta name="viewport" content="width=device-width, initial-scale=1.0">
   <title>アイテム登録</title>
   <link rel="stylesheet" href="../style/g5_style.css">
</head>

<style>
   /* リセットCSS */
   * {
      margin: 0;
      padding: 0;
      box-sizing: border-box;
   }

   h1 {
      color: #2c3e50;
      margin-bottom: 20px;
   }

   .form-group {
      margin-bottom: 15px;
   }

   label {
      font-weight: bold;
      display: block;
      margin-bottom: 5px;
   }

   input[type="text"],
   input[type="number"],
   textarea,
   select {
      width: 100%;
      padding: 10px;
      border: 1px solid #ddd;
      border-radius: 5px;
      margin-top: 5px;
      font-size: 1rem;
   }

   textarea {
      resize: vertical;
      height: 120px;
   }

   button.btn {
      background-color: #16a085;
      color: white;
      border: none;
      padding: 12px 20px;
      border-radius: 5px;
      cursor: pointer;
      font-size: 1rem;
      width: 100%;
      transition: background-color 0.3s ease;
   }

   button.btn:hover {
      background-color: #1abc9c;
   }

   .success {
      color: #27ae60;
      font-weight: bold;
      margin-top: 20px;
      text-align: center;
   }
</style>

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
            <h1>新しい商品を登録</h1>
            <form method="POST" enctype="multipart/form-data" class="form">
               <div class="form-group">
                  <label for="name">商品名:</label>
                  <input type="text" id="name" name="name" required>
               </div>
               <div class="form-group">
                  <label for="price">価格:</label>
                  <input type="number" id="price" name="price" required>
               </div>
               <div class="form-group">
                  <label for="description">説明:</label>
                  <textarea id="description" name="description" required></textarea>
               </div>
               <div class="form-group">
                  <label for="category">カテゴリー:</label>
                  <select id="category" name="category" required>
                     <option value="直送">直送</option>
                     <option value="旬物">旬物</option>
                     <option value="名物">名物</option>
                     <option value="その他">その他</option>
                  </select>
               </div>
               <div class="form-group">
                  <label for="stock">在庫:</label>
                  <input type="number" id="stock" name="stock" required>
               </div>
               <div class="form-group">
                  <label for="image">在庫:</label>
                  <input type="text" id="image" name="image" placeholder="https.." required>
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