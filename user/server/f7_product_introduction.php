<?php
require 'f0_connect_database.php';

function getMerchandise($merchandise_id)
{
    $pdo = connect_database();

    // SQLクエリの構築
    $merchandise = $pdo->prepare('SELECT* FROM merchandise WHERE merchandise_id=?');
    $merchandise->execute([$merchandise_id]);
    $pdo = NULL;
    return $merchandise;
}

function getReview($merchandise_id)
{
    $pdo = connect_database();

    $review = $pdo->prepare('SELECT* FROM review WHERE merchandise_id=?');
    $review->execute([$merchandise_id]);
    $pdo = NULL;
    return $review;
}

function writing_review($user_id, $merchandise_id, $rating, $comment)
{
    try {
        $pdo = connect_database();
        $review = $pdo->prepare('INSERT INTO review VALUES (?,null,?, ?, ?, date(Y,m,d)');
        $review->execute([$user_id, $merchandise_id, $rating, $comment]);
        $pdo = NULL;
        return true;
    } catch (PDOException $e) {
        return false;
    }
}

function create_introduction($merchandise, $review)
{

    $id = htmlspecialchars($merchandise['merchandise_id']);
    $photograph = htmlspecialchars($merchandise['photograph']);
    $name = htmlspecialchars($merchandise['merchandise_name']);
    $price = htmlspecialchars($merchandise['price']);
    $explanation = htmlspecialchars($merchandise['explanation']);

    $review_id = htmlspecialchars($review['review_id']);
    $rating = htmlspecialchars($review['rating']);
    $comment = htmlspecialchars($review['comment']);

    // 商品情報の表示部分（HTMLを組み立て）
    echo '<div class="row-item1">
                <img src="photograph_files/' . $photograph . '" width="200" alt="' . $name . '">

            <div class="row-item2">
                <div class="col-item1">
                    ' . $name . '
                </div>

                <div class="col-item2">
                    ' . $price . '
                </div>

                 <form method="post" action="g8_cart_view.html">
                        <input type="hidden" name="merchandise_id" value="' . $id . '">
                        <button type="submit">今すぐ買う</button>
                    </form>

                     <form method="post" action="g9_buy_view.html">
                        <input type="hidden" name="merchandise_id" value="' . $id . '">
                        <button type="submit">カゴに入れる</button>
                    </form>

                <div class="col-item3">
                    ' . $explanation . '
                   
            </div>

        
            <p>レビュー</p>
            <div class="stars">
            <span>
                <input id="review01" type="radio" name="review"><label for="review01">★</label>
                <input id="review02" type="radio" name="review"><label for="review02">★</label>
                <input id="review03" type="radio" name="review"><label for="review03">★</label>
                <input id="review04" type="radio" name="review"><label for="review04">★</label>
                <input id="review05" type="radio" name="review"><label for="review05">★</label>
            </span>
            </div>
            
            <form action="#" method=post>
            <textarea name="review_comment"></textarea>
            <div class="row-item6">
                <button type="submit">書く</button>
            </div>
            </form>


            <div class="row-item4">
                レビュー：' . $rating . '
            </div>
            <div class="row-item5">
                <fieldset>
                    ' . $comment . '
                </fieldset>

          
            </div>';
}
