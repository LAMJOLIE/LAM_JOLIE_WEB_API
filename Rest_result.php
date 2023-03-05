<?php 
//ホットペッパーAPIキー：
define('HOTPEPPER_ACCESSKEY', '0463df58fd1dfaf8');

//検索ジャンルID：検索件数
define('HOTPEPPER_COUNTS', 30);

define('HOTPEPPER_ORDER',4);

/**
 * グルメサーチAPIのURLを取得する
 * @param	double $latitude  緯度（世界測地系）
 * @param	double $longitude 経度（世界測地系）
 * @param	double $distance  範囲（メートル）
 * @return	string URL グルメサーチAPIのURL
*/
function getURL_GourmetSearchAPI($latitude, $longitude, $distance, $keyword) {
    $range_tbl = array(1=>300, 2=>500, 3=>1000, 4=>2000, 5=>3000);
    $apikey = HOTPEPPER_ACCESSKEY;
    $counts = HOTPEPPER_COUNTS;
    $order= HOTPEPPER_ORDER;
    
    $range = count($range_tbl);
        foreach ($range_tbl as $key=>$val) {
            if ($distance <= $val) {
                $range = $key;
                break;
            }
        }
        
        $url = "http://webservice.recruit.co.jp/hotpepper/gourmet/v1/?key={$apikey}&lat={$latitude}&lng={$longitude}&range={$range}&keyword={$keyword}&count={$counts}&order={$order}&format=json"; 
     
    return $url;
}

/**
 * グルメサーチAPIを利用して指定座標の近くにあるレストランを検索
 * @param	double $latitude  緯度（世界測地系）
 * @param	double $longitude 経度（世界測地系）
 * @param	double $distance  範囲（メートル）
 * @param	array $items 情報を格納する配列
 * @return	array(ヒットした施設数, メッセージ, APIのURL)
*/
function searchRestaurant($latitude, $longitude, $distance, $keyword) {
         $msg = '';
         $url = getURL_GourmetSearchAPI($latitude, $longitude, $distance, $keyword);
        //  echo($url);
         $json = json_decode(mb_convert_encoding(@file_get_contents($url, false), "UTF-8"));
         $items = array();
     
         //レスポンス・チェック
         if ($json == NULL) {
             if (isset($json->results->results_available) && ((int)($json->results->results_available) <= 0)) {
                 $msg = '検索範囲に店舗が無いか，ホットペッパーAPIのエラーです';
             }
             return array(FALSE, $msg, $url);
         }
         
         //応答解釈
         $n = 0;
         foreach ($json->results->shop as $element) {
             $items[$n]['id']        = (string)$element->id;
             $items[$n]['name']      = (string)$element->name;
             $items[$n]['url']       = (string)$element->urls->pc;
             $items[$n]['category']  = (string)$element->genre->catch;
             $items[$n]['open']      = (string)$element->open;
             $items[$n]['close']     = (string)$element->close;
             $items[$n]['address']   = (string)$element->address;
             $items[$n]['access']   = (string)$element->mobile_access;
             $items[$n]['image']     = (string)$element->photo->pc->l;
             $items[$n]['genre']     = (string)$element->genre->name;
             $items[$n]['latitude']  = (double)$element->lat;
             $items[$n]['longitude'] = (double)$element->lng;
             $items[$n]['description'] =<<< EOT
            <span class="small"><a href="{$items[$n]['url']}" target="_blank">{$items[$n]['name']}</a><br />{$items[$n]['address']}<br />営業時間：{$items[$n]['open']}／定休日：{$items[$n]['close']}<br /><img src="{$items[$n]['image']}" /></span>
            EOT;
             $n++;
         }
     
        // return array($n, '', $url);
        return($items);
    }

    //Indexページから情報を取得する
    $latitude = filter_input(INPUT_GET, "lat");
    $longitude = filter_input(INPUT_GET, "lng");
    $distance = filter_input(INPUT_GET, "distance");
    $keyword = filter_input(INPUT_GET, "searchR");
    $rests = searchRestaurant($latitude, $longitude, $distance, $keyword);
    $restsCount = count($rests);
?>
<!DOCTYPE html>
<html lang="jp">
<head>
    <meta charset="UTF-8" />
    <meta name="viewpoint" content="width=device-width,initial-scale=1.0" />
    <meta http-equiv="X-UA-Compatible" contnt="IE=edge" />
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Raleway&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="./css/index.css">
    <link rel="stylesheet" href="./css/Rest_result.css">
    <script src="https://kit.fontawesome.com/4d6369389a.js" crossorigin="anonymous"></script>
    <title>Hotpepper WEB API_ LAM JOLIE</title>
</head>
<body>

<?php include('./Navbar/navbar.php'); ?>

<section class="main-search">

    <div id="search" class="search">
    <form action="Rest_result.php" method="GET" class="search-bar" target="_self" enctype="">
            <input type="hidden" id="lat"  name="lat">
            <input type="hidden" id="lng"  name="lng">
            <input type="text" placeholder="現在地付近のレストランを検索" name="searchR">

            <div class="select">
                <select name="distance" id="distance">
                    <option value="300">300m</option>
                    <option value="500">500m</option>
                    <option value="1000">1000m</option>
                    <option value="2000">2000m</option>
                    <option value="3000">3000m</option>
                </select>
            </div>

            <button type="submit" value="send"><img src="./Picture/search.png"></button>

        </form> 

    </div>
</section>

<!-- レストランの検索結果　開始-->
<div class="place-content-wrap">

    <select name="orderBtn" id="order">  <!-- ソート順の機能がまだ完成していません　-->
        <option value="4">おすすめ順</option>
        <option value="1">店名かな順</option>
    </select>

<?php for($i = 1; $i <= ($restsCount / 10); $i++) : ?>
    <section class="place-content" id="page<?= $i ?>">
        <?php for($j = (($i - 1) * 10); $j < ($i * 10); $j++) : ?>
            <a href="detail.php?id=<?= $rests[$j]["id"] ?>" class="col-md-12 col-lg-10 mx-auto item-box">
                <div class="restaurant-item">
                        <diV class="col-md-7 center-item">
                            <img src="<?= $rests[$j]['image'] ?>" alt="">

                            <div class="information">
                                <h3><?= $rests[$j]['name'] ?></h3>
                                <p><i class="fa-solid fa-utensils"></i>　<?= $rests[$j]['genre'] ?></p>
                                <p><i class="fa-solid fa-location-dot"></i>　<?= $rests[$j]['address'] ?></p>
                                <p><i class="fa-sharp fa-solid fa-compass"></i>　<?= $rests[$j]['access'] ?></p>
                            </div>
                        </diV>
                </div>
            </a>
        <?php endfor;?> 
    </section>
<?php endfor;?> 
<!-- レストランの検索結果　終了 -->
</div>

<!-- ページングボタン　開始-->
<div id="paging"></div>
<!-- ページングボタン　終了 -->
</body>

<script src="./js/index.js"></script>
<script src="./js/paging.js"></script>

</html>

