<?php session_start() ?>
<!DOCTYPE html>
<html lang="ja">

<head>
<meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="../style/style.css">
    <link rel="stylesheet" href="../style/g3_style.css">
    <style>
        footer {
            margin: 5vh;
            text-align: center;
        }

        footer a{
            font-size: 20px;
        }
    </style>
</head>

<body>
    <header>
        <a href="g4_mypage.php">マイページ</a>
        <a href="g8_cart.php">🛒</a>
        <img src="../images/full_title.png" alt="ページのタイトル">
        <img src="../images/header.png" alt="ヘッダー画像">
    </header>

    <main>
        <h1>コンセプト</h1>
        <p>福岡の魅力である、おいしい食べ物を<br>全国の方に知ってもらう！！</p>

        <form action="g7_product_introduction.php" method="get">
            <div class="container">
                <h2>検索</h2>
                <input type="search" name="search" placeholder="例　いちご">
            </div>
        </form>

        <form action="g6_search_result.php" method="get">
            <div id="category_form">
                <h2>カテゴリー別</h2>
                <div id="category">
                    <button type="submit" name="category" value="直送"><img src="../images/direct_button.png"
                            alt="直送"></button>
                    <button type="submit" name="category" value="名物"><img src="../images/specialty_button.png"
                            alt="名物"></button>
                    <button type="submit" name="category" value="その他"><img src="../images/other_button.png"
                            alt="その他"></button>
                    <button type="submit" name="category" value="不明"><img src="../images/unknown_button.png"
                            alt="不明"></button>
                </div>
            </div>
        </form>
    </main>

    <footer>
        <?php if ($_SESSION['user'] ?? false): ?>
            <a href="../server/logout.php">ログアウト</a>
        <?php else: ?>
            <a href="g1_login.php">ログイン</a>
        <?php endif; ?>
    </footer>

</body>

</html>