<?php

$dsn = 'mysql:host=mysql305.phy.lolipop.lan;dbname=LAA1602705-php2024;charset=utf8';

$username = 'LAA1602705';

$password = 'Itou0315';

$message = '';


if ($_SERVER['REQUEST_METHOD'] === 'POST') {

    $name = $_POST['name'];

    $price = $_POST['price'];

    $description = $_POST['description'];

    try {

        $pdo = new PDO($dsn, $username, $password);

        $stmt = $pdo->prepare("INSERT INTO items (name, price, description) VALUES (:name, :price, :description)");

        $stmt->execute([

            ':name' => $name,

            ':price' => $price,

            ':description' => $description,

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
<style>

      body {

         font-family: Arial, sans-serif;

      }

      .container {

         width: 80%;

         margin: auto;

      }

      .form-group {

         margin-bottom: 1em;

      }

      label {

         display: block;

         margin-bottom: 0.5em;

      }

      input, textarea, select {

         width: 100%;

         padding: 0.5em;

         border: 1px solid #ccc;

         border-radius: 4px;

      }

      .btn {

         padding: 0.5em 1em;

         background-color: #007BFF;

         color: white;

         border: none;

         border-radius: 4px;

         cursor: pointer;

      }

      .btn:hover {

         background-color: #0056b3;

      }
</style>
</head>
<body>
<div class="container">
<h1>新しいアイテムを登録</h1>
<form method="POST" enctype="multipart/form-data">
<div class="form-group">
<label for="name">アイテム名:</label>
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
<option value="direct">直接</option>
<option value="food">食品</option>
<option value="goods">商品</option>
<option value="other">その他</option>
</select>
</div>
<div class="form-group">
<label for="stock">在庫:</label>
<input type="number" id="stock" name="stock" required>
</div>
<button type="submit" class="btn">登録</button>
</form>
</div>
</body>
</html>'; 