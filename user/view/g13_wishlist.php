<?php
session_start();

if (!isset($_SESSION['user'])) {
    header("Location: g1_login.php");
    exit();
}

function checkWishList($id) {
    if(true) {
        return '♥';
    }else {
        return '♡';
    }
} 
?>
<!DOCTYPE html>
<html lang="jP">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="../style/g13_style.css">

    <title>ウィッシュリスト</title>
</head>
<body>
    <header>
        <div class="header-left">
            <a href="g3_home.php"><img src="../images/title.png" alt="FMJ"></a>   
        </div>
    </header>
    <hr>
    <main>
    <P>ウィッシュリスト</P>
    </main>
</body>
</html>