<?php
session_start();
require 'f0_connect_database.php';

function review($user_id)
{
    $pdo = connect_database();

    $sql = 'SELECT * FROM review 
    LEFT OUTER JOIN review ON review.merchandise_id = merchandise.merchandise_id 
    WHERE user_id = ?';


    $review_result = $pdo->prepare($sql);
    $review_result->execute([$user_id]);
    $pdo=NULL;

    return $review_result;
}

function create_review($review_result){

echo '<form method="post" action="g5_review_view.html">
<button type="submit">削除</button>';


foreach ($review_result as $row) {
$id = htmlspecialchars($row['review_id']);
$photograph = htmlspecialchars($row['photograph']);
$name = htmlspecialchars($row['merchandise_name']);
$rating = htmlspecialchars($row['rating']);
$comment = htmlspecialchars($row['comment']);
$date = htmlspecialchars($row['date']);

// 商品情報の表示部分（HTMLを組み立て）
echo
'<input type="checkbox" name="review_id[]" value="' . $id . '">
<fieldset>
    <div class="row-item1">
        <img src="photograph_files/' . $photograph . '" width="200" alt="' . $name . '">
    </div>

    <div class="row-item2">
        <div class="col-item1">
            ' . $name . '
        </div>

        <div class="col-item2">
            ✰ ' . $rating . '
            ' . $comment . '
        </div>

        <div class="col-item3">
            ' . $date . '
        </div>
    </div>
</fieldset>';
}
echo '</form>';
}