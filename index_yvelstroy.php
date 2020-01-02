<!DOCTYPE HTML>

<html>

<head>
  <title></title>
  <meta charset="utf-8">
</head>

<body>

<?php
set_time_limit(0);
date_default_timezone_set ("Europe/Kiev");

$dbnamedb = "olxwatch";
$userdb = "root";
$passdb = "";

$db = mysql_connect("localhost",$userdb,$passdb);

mysql_select_db($dbnamedb, $db);
mysql_query("SET character_set_results = 'utf8', character_set_client = 'utf8', character_set_connection = 'utf8', character_set_database = 'utf8', character_set_server = 'utf8'", $db);

$start_page_url = "https://yvelstroy.olx.ua/shop/";
$data = OLX_curl_get_page($start_page_url);

$max_page = 1;
preg_match_all('#<span>([0-9]+)<\/span>#', $data, $num_pages);

$max_page = $num_pages[1][count($num_pages[1])-1];

$goods = array();

$result = mysql_query("UPDATE products2 SET active=0");

for($h=0;$h<=3;$h++){
for($i=1; $i<=$max_page;$i++){     //echo "<br />".$start_page_url."?page=".$i;
    $item = array();
    $html = OLX_curl_get_page($start_page_url."?page=".$i);
    preg_match_all('#<h3 class="x-large lheight20 margintop5">\n                                <a href="(?<page>.*?)"\n                                   class="link linkWithHash detailsLink">\n                                    <strong>(?<title>.*?)<\/strong>#s', $html, $mix_info);
    //preg_match_all('#<img class="fleft" src="(?<photo>.*?)" alt=#s', $html, $photos);
    //preg_match_all('#<p class="price">\n                                <strong>(?<price>.*?)<\/strong>#s', $html, $prices);

     echo "<p>".count($mix_info['page'])." ".$start_page_url."?page=".$i."</p>";

    for($k=0; $k<count($mix_info['page']); $k++){
        $item['page'] = $mix_info['page'][$k];
        //$item['title'] = $mix_info['title'][$k];
        //$item['photo'] = $photos['photo'][$k];
        //$item['price'] = trim($prices['price'][$k]);
        //echo "<br />".mb_strtolower($mix_info['page'][$k]);
        //echo " ".$mix_info['title'][$k];
        //echo " ".$photos['photo'][$k];
        //echo " ".trim($prices['price'][$k]);
        $html_one_page = OLX_curl_get_page($mix_info['page'][$k]);

        preg_match_all('#в (?<time>.*?), (?<date>.*?), <small>Номер объявления: [0-9]+<\/small>#', $html_one_page, $dateadd);
        preg_match_all('#<div id="photo-gallery-opener" class="photo-handler rel inlblk">\n										<img src="(?<photo>.*?)"#s', $html_one_page, $photos);
        preg_match_all('#"ad_price":"(?<ad_price>.*?)","price_currency":"(?<price_currency>.*?)",#s', $html_one_page, $prices);
        preg_match_all('#<h1>(?<title>.*?)<\/h1>#s', $html_one_page, $titles);
        //print_r($dateadd);
        $item['photo'] = $photos['photo'][0];
        $item['price'] = $prices['ad_price'][0].$prices['price_currency'][0];
        $item['title'] = $titles['title'][0];
        //echo $dateadd['date'][0];
        //echo $dateadd['time'][0];
                                                  //date("Y-m-d H:i:s")
        $mysql_date = date("2001-01-01 01:01:01");
        try{
            $date = date_create_from_format('H:i:s j M Y', $dateadd['time'][0].":00 ".ruMonthsToEn($dateadd['date'][0]));
            $mysql_date = date_format($date, 'Y-m-d H:i:s');
        }catch(Exception $e){
            echo 'Выброшено исключение: '.$mix_info['page'][$k]." ",  $e->getMessage(), "\n";
        }
        $item['create_ad_date'] = $mysql_date;
        $item['update_date'] = date("Y-m-d H:i:s");
        //echo "<br />"."INSERT INTO products (page, title, image, price, create_ad_date, update_date, active) VALUES ('".mysql_real_escape_string($item['page'])."','".mysql_real_escape_string($item['title'])."','".mysql_real_escape_string($item['photo'])."','".mysql_real_escape_string($item['price'])."', '".date("Y-m-d H:i:s")."', '".date("Y-m-d H:i:s")."', 1) ON DUPLICATE KEY UPDATE active=1";
        if(mb_strlen($item['title'])>0){
            $result = mysql_query("INSERT INTO products2 (page, title, image, price, create_ad_date, update_date, active) VALUES ('".mysql_real_escape_string($item['page'])."','".mysql_real_escape_string($item['title'])."','".mysql_real_escape_string($item['photo'])."','".mysql_real_escape_string($item['price'])."', '".$item['create_ad_date']."', '".date("Y-m-d H:i:s")."', 1) ON DUPLICATE KEY UPDATE active=1, update_date='".date("Y-m-d H:i:s")."'");
            $item['prod_id'] = mysql_insert_id();
            if(!finDupl($goods, $item)){
                array_push($goods, $item);
            }
        }
        //$item
        //echo $mysql_date;
        //break;
    }

    //if($i==1)break;
}
}

$result = mysql_query("INSERT INTO updates2 (data, total, create_date) VALUES ('".mysql_real_escape_string(json_encode($goods, JSON_UNESCAPED_UNICODE))."', ".count($goods).", '".date("Y-m-d H:i:s")."')");

foreach($goods as $g){
    echo "<br />".$g['page'];
}








function finDupl($goods, $item){
    foreach($goods as $g){
        if($g['page'] == $item['page']){
            return true;
        }
    }
    return false;
}






function OLX_curl_get_page($url){

    $ch = curl_init();
    curl_setopt($ch, CURLOPT_HEADER, 0);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
    curl_setopt($ch, CURLOPT_FOLLOWLOCATION, 1);


curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, 0);
    curl_setopt($ch, CURLOPT_URL, $url);
    curl_setopt($ch, CURLOPT_USERAGENT, 'Mozilla/5.0 (X11; U; Linux i686; en-US) AppleWebKit/534.16 (KHTML, like Gecko) Chrome/10.0.648.204 Safari/534.16');
    curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
    $data = curl_exec($ch);
    $last_url = curl_getinfo($ch, CURLINFO_EFFECTIVE_URL);//echo $last_url;
    if(curl_error($ch))
    {
        //m_log("Ошибка в function OLX_curl_get_page(".$url."). Error: ".curl_error($ch));
    }
    curl_close($ch);
    return $data;
}
//$ru_months = array( 'Январь', 'Февраль', 'Март', 'Апрель', 'Май', 'Июнь', 'Июль', 'Август', 'Сентябрь', 'Октябрь', 'Ноябрь', 'Декабрь' );
function ruMonthsToEn($date){
    $ruMonths = array( 'января', 'февраля', 'марта', 'апреля', 'мая', 'июня', 'июля', 'августа', 'сентября', 'октября', 'ноября', 'декабря' );
    $enMonths = array( 'Jan', 'Feb', 'Mar', 'Apr', 'May', 'Jun', 'Jul', 'Aug', 'Sep', 'Oct', 'Nov', 'Dec' );
    return str_replace($ruMonths, $enMonths, $date);
}

mysql_close($db);
?>
</body>

</html>