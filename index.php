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
    <title>Hotpepper WEB API_ LAM JOLIE</title>
</head>
<body>
    
<?php include('./Navbar/navbar.php'); ?>

<!-- 検索エリア　開始-->
<section class="main-search">
    
    <img src="./Picture/Food_Solgan.png" id="food-solgan">

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
<!-- 検索エリア　終了-->


</body>
<script src="./js/index.js"></script>
</html>

