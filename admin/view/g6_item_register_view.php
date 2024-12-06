<?php

session_start();


if (!isset($_SESSION['admin_logged_in']) || $_SESSION['admin_logged_in'] !== true) {
    header('Location:g1_login_view.php'); 
    exit();
}


if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    
    $name = trim($_POST['name']);
    $description = trim($_POST['description']);
    $price = trim($_POST['price']);
    $image = '';

    
    if (isset($_FILES['image']) && $_FILES['image']['error'] === UPLOAD_ERR_OK) {
        $image_tmp = $_FILES['image']['tmp_name'];
        $image_name = basename($_FILES['image']['name']);
        $image_path = '../uploads/' . $image_name;

        move_uploaded_file($image_tmp, $image_path);
        $image = $image_path;
    }

    try {
        $pdo = new PDO('mysql:host=mysql305.phy.lolipop.lan;dbname=LAA1602705-php2024', 'LAA1602705', 'Itou0315');
        $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        $stmt = $pdo->prepare("INSERT INTO products (name, description, price, image) VALUES (?, ?, ?, ?)");
        $stmt->execute([$name, $description, $price, $image]);

        
        $message = '商品が正常に登録されました。';
    } catch (PDOException $e) {
        $message = 'データベース接続エラー: ' . $e->getMessage();
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
    <title>商品登録</title>
</head>
<body>
    <header>
        <div class="header-left">
            <a href="G3ホーム"><img src="../images/title.png" alt="タイトル画像"></a>
        </div>
        <div class="header-right">
            <a href="G8カート"><img src="../images/cart.png" alt="カート画像"></a>
        </div>
    </header>
    <hr>
    <main>
        <h1>商品登録</h1>

        <?php if (isset($message)) { echo "<p>$message</p>"; } ?>

        <form action="g6_item_register_view.php" method="POST" enctype="multipart/form-data">
            <div>
                <label for="name">商品名:</label>
                <input type="text" name="name" id="name" required>
            </div>
            <div>
                <label for="description">商品説明:</label>
                <textarea name="description" id="description"></textarea>
            </div>
            <div>
                <label for="price">価格:</label>
                <input type="number" name="price" id="price" required>
            </div>
            <div>
                <label for="image">商品画像:</label>
                <input type="file" name="image" id="image" required>
            </div>
            <button type="submit">登録</button>
        </form>
    </main>
    <footer></footer>
</body>
</html>
