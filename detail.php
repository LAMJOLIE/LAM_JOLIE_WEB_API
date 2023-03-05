<?php 
//ホットペッパーAPIキー：
define('HOTPEPPER_ACCESSKEY', '0463df58fd1dfaf8');

//検索ジャンルID：検索件数
define('HOTPEPPER_COUNTS', 30);

/* グルメサーチAPIの指定したレストランのURLを取得*/
function getURL_GourmetSearchAPI($shopid) {
    $apikey = HOTPEPPER_ACCESSKEY;
    $shopid= filter_input(INPUT_GET, "id");
        
        $url = "http://webservice.recruit.co.jp/hotpepper/gourmet/v1/?key={$apikey}&id={$shopid}&format=json"; 
     
    return $url;
}

/* グルメサーチAPIを利用して指定したレストランの情報を取得*/
function searchRestaurant($shopid) {
         $msg = '';
         $url = getURL_GourmetSearchAPI($shopid);
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
             $items[$n]['access']   = (string)$element->access;
             $items[$n]['image']     = (string)$element->photo->pc->l;
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

    $shopid= filter_input(INPUT_GET, "id");
    $rests = searchRestaurant($shopid);

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
    <link rel="stylesheet" href="./css/detail.css">
    <title>Hotpepper WEB API_ LAM JOLIE</title>
</head>
<body>
<?php include('./Navbar/navbar.php'); ?>


<!-- 検索結果の詳細　開始-->
<section class="detail">
                <?php foreach($rests as $rest): ?>

                    <div class="detail-container">
                        <div class="pic">
                            <img src ="<?= $rest['image'] ?>">
                        </div>
                        
                        <div class="form-detail">
                            
                            <div class="from-group">
                                    <label class="label">名称</label>
                                    <p class="info"><?= $rest['name'] ?></p><br>
                                </div>

                                <div class="from-group">
                                    <label class="label">住所</label>
                                    <p class="info"><?= $rest['address'] ?></p><br>
                                </div>

                                <div class="from-group">
                                    <label class="label">営業時間</label>
                                    <p class="info"><?= $rest['open'] ?></p><br>
                                </div>

                                <div class="from-group">
                                    <label class="label">アクセス</label>
                                    <p class="info"><?= $rest['access'] ?></p><br>
                                </div>


                                <div class="from-group">
                                    <label class="label">ホームページ</label>
                                    <p  class="info"><a href="<?= $rest['url'] ?>" target="_blank"><?= $rest['url'] ?></a></p>
                                </div>
                                </ul>
                            </div>
                            

                        <?php endforeach; ?>

            </div>
        </section>
<!-- 検索結果の詳細　終了-->
</body>
<script src="./js/index.js"></script>
</html>
