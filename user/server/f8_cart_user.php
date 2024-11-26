<?php
session_start();
require 'cart.php';
require 'f0_connect_database.php';

function cart($user_id)
{
    $pdo = connect_database();

    $sql = 'SELECT * FROM cart 
    LEFT OUTER JOIN cart ON cart.merchandise_id = merchandise.merchandise_id 
    WHERE user_id = ?';


    $cart = $pdo->prepare($sql);
    $cart->execute([$user_id]);
    $pdo=NULL;

    return $cart;
}

function create_cart($cart){

echo '<form method="post" action="g8_cart_view.html">
<button type="submit">削除</button>
<form action="g9_buy_view.html" method=post>';


foreach ($cart as $row) {
$id = htmlspecialchars($row['cart_id']);
$photograph = htmlspecialchars($row['photograph']);
$name = htmlspecialchars($row['merchandise_name']);
$quantity = htmlspecialchars($row['quantity']);


// 商品情報の表示部分（HTMLを組み立て）
echo
'<input type="checkbox" name="cart_id[]" value="' . $id . '">
<fieldset>
    <div class="row-item1">
        <img src="photograph_files/' . $photograph . '" width="200" alt="' . $name . '">
    </div>

    <div class="row-item2">
        <div class="col-item1">
            ' . $name . '
        </div>

        <div class="col-item2">
            ✰ ' . $quantity . '
        </div>
    </div>
</fieldset>';
}
'<div class="row-item6">
    <button type="submit">書く</button>
</div>';
echo '</form>';
}