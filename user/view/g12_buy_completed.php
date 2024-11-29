<?php
session_start();
require_once '../server/f0_connect_database.php';

// ユーザーがログインしているか確認
if (isset($_SESSION['user'])) {

    // DB接続
    $pdo = connect_database();

    // ユーザーIDの取得
    $user_id = $_SESSION['user']; // セッションからユーザーIDを取得

    // 商品IDがPOSTパラメータで送信されているか確認
    if (isset($_POST['merchandise_ids']) && is_array($_POST['merchandise_ids'])) {
        $merchandise_ids = $_POST['merchandise_ids']; // 送信された商品IDの配列

        foreach ($merchandise_ids as $merchandise_id) {
            $stmt = $pdo->prepare("DELETE FROM cart WHERE user_id=? AND merchandise_id=?");
            $stmt->execute([$user_id, $merchandise_id]);
        }
    }
}
?>
<!DOCTYPE html>
<html lang="jp">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="../style/g12_style.css">

    <title>購入完了</title>
</head>
<body>
    <h1>FMJ</h1>
    <p>ご利用いただきありがとうございます。<br>
        またのご利用をお待ちしております。
    </p>
    <p>
        <img src="../images/double_human.png" alt="お辞儀の写真">
    </p>
    <p>
        <a href="g3_home.php">ホームに戻る</a>
    </p>
</body>

</html>
</html>