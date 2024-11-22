
<?php
require 'f0_connect_database.php';
require 'f13_wishlist.php';

function search_result($merchandise_name, $category)
{
    $pdo = connect_database();

    // SQLクエリの構築
    $sql = 'SELECT * FROM review 
            LEFT OUTER JOIN merchandise ON review.merchandise_id = merchandise.merchandise_id 
            WHERE merchandise_name LIKE :merchandise_name OR category = :category';

    // プリペアドステートメント
    $stmt = $pdo->prepare($sql);
    $searchKey = '%' . $merchandise_name . '%';

    // パラメータのバインド
    $stmt->bindParam(':merchandise_name', $searchKey, PDO::PARAM_STR);
    $stmt->bindParam(':category', $category, PDO::PARAM_STR);

    // クエリの実行
    $stmt->execute();
    $pdo=NULL;

    return $stmt;
}


    function create_search($stmt){

        // 結果のループ
        foreach ($stmt as $row) {
            $id = htmlspecialchars($row['merchandise_id']);
            $photograph = htmlspecialchars($row['photograph']);
            $name = htmlspecialchars($row['merchandise_name']);
            $price = htmlspecialchars($row['price']);
            $rating = htmlspecialchars($row['rating']);
            $heart = checkWishlist($id); // ここでwishリストチェック
    
            // 商品情報の表示部分（HTMLを組み立て）
            echo '<fieldset>
                    <div class="row-item1">
                        <img src="photograph_files/' . $photograph . '" width="200" alt="' . $name . '">
                    </div>
    
                    <div class="row-item2">
                        <div class="col-item1">
                            ' . $name . '
                        </div>
    
                        <div class="col-item2">
                            ✰ ' . $rating . '
                            ' . $price . '
                        </div>
    
                        <div class="col-item3">
                            ' . $heart . '
                            <form method="post" action="g9_buy_view.html">
                                <input type="hidden" name="merchandise_id" value="' . $id . '">
                                <button type="submit">購入</button>
                            </form>
                        </div>
                    </div>
                </fieldset>';
        }
    }
