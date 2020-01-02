<?php

/*include_once("olxconnect.php");
$olxconnect = new olxconnect();
$postdata= array("login[remember-me]"=>"on","login[password]"=>"28091989", "login[email_phone]"=>"ksjyn4ik@bigmir.net", "g-recaptcha-response"=>"03AMGVjXjwHaHRoTLYOU8cuaE9TRaqYPqEoHFeQ7kv3ppNIyRtZ0H8xLx0-OsF4BAS44eKcfA3FyDAiPG9aK5vWNB_dvzCX7zVLjOOB2sGhGrHgk235przmmSHLBII0b9U6M0yBhrClhIXx0Et3LnLtuF1JjgvUmkktBpLMw0JH_HveU4nhkCEcknganSH3B0rqrE50Py3wIeubn2UF7NXxadp-x5LCwVIKweZiTAtksdRtF2PrnmR3DUj_Oi4eB8hEgx5vR-FRsWP4y67_hgohcT1KPahIiCVEAlogin%5Bremember-me%5D=on&login%5Bpassword%5D=28091989&login%5Bemail_phone%5D=ksjyn4ik%40bigmir.net&g-recaptcha-response=03AMGVjXjwHaHRoTLYOU8cuaE9TRaqYPqEoHFeQ7kv3ppNIyRtZ0H8xLx0-OsF4BAS44eKcfA3FyDAiPG9aK5vWNB_dvzCX7zVLjOOB2sGhGrHgk235przmmSHLBII0b9U6M0yBhrClhIXx0Et3LnLtuF1JjgvUmkktBpLMw0JH_HveU4nhkCEcknganSH3B0rqrE50Py3wIeubn2UF7NXxadp-x5LCwVIKweZiTAtksdRtF2PrnmR3DUj_Oi4eB8hEgx5vR-FRsWP4y67_hgohcT1KPahIiCVEA");
print_r($olxconnect->POSTquery("https://www.olx.ua/account/?ref[0][action]=myaccount&ref[0][method]=index", $postdata));

return;*/
/*
$postArray['login[email_phone]']="irina190189@ukr.net";
$postArray['login[password]']="4053335";
*/

set_time_limit(0);
include_once("olxconnect.php");

//echo $olxconnect->GETquery("https://www.olx.ua/account/?ref[0][action]=myaccount&ref[0][method]=index");
const ANTI_CAPTCHA_KEY = "28bcc58b56b459a06f8d3c19ad2221b0";
const USER_LOGIN = "irina190189@ukr.net";
const USER_PASSWORD = "4053335";

$html = "";
$login_cookies = "";
//goto a;
/*******************************************************************************/
$ch = curl_init("https://www.olx.ua/myaccount/");
curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
curl_setopt($ch, CURLOPT_HEADER, true);

//curl_setopt($ch, CURLOPT_COOKIEJAR, $cookiefiles);
//curl_setopt($ch, CURLOPT_NOBODY,true);
//curl_setopt($ch, CURLOPT_COOKIEFILE, $cookiefiles);
curl_setopt($ch, CURLOPT_COOKIEJAR, dirname(__FILE__).'/cookies/cookie.txt');
curl_setopt($ch, CURLOPT_COOKIEFILE, dirname(__FILE__).'/cookies/cookie.txt');
curl_setopt($ch, CURLOPT_FOLLOWLOCATION, true);
curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, false);
curl_setopt($ch, CURLOPT_ENCODING , "gzip");
curl_setopt($ch, CURLOPT_SSL_VERIFYPEER ,false);
curl_setopt($ch, CURLOPT_HTTPHEADER, array(
'Host: www.olx.ua',
'Connection: keep-alive',
'Cache-Control: max-age=0',
'Upgrade-Insecure-Requests: 1',
'User-Agent: Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/73.0.3683.67 Safari/537.36',
'Accept: text/html,application/xhtml+xml,application/xml;q=0.9,image/webp,image/apng,*/*;q=0.8,application/signed-exchange;v=b3',
'Referer: https://www.olx.ua/',
'Accept-Encoding: gzip, deflate, br',
'Accept-Language: ru-RU,ru;q=0.9,en-US;q=0.8,en;q=0.7'
));
$html=curl_exec($ch); //echo "<b>".curl_getinfo($ch, CURLINFO_EFFECTIVE_URL)."</b>";    echo $html;
//echo $html;


$cookies_arr = json_decode(file_get_contents("export.json"));
$tmp = array();
foreach($cookies_arr as $imcook){
    //echo "<br />".$imcook->name;
    $tmp[] = $imcook->name."=".$imcook->value;
}
//$login_cookies =
$login_cookies = implode(";", $tmp);
//echo $login_cookies;
//die;





/*$site_code = "";
preg_match_all('#mixpanel":\{"siteCode":"(?<sitecode>.*?)"\}\}\}#ui', $html, $sc_matches);
$site_code = $sc_matches['sitecode'][0];


preg_match_all('/^Set-Cookie:\s*([^;]*)/mi', $html, $matches);
$cookies = array();
foreach($matches[1] as $item) {
    parse_str($item, $cookie);
    $cookies = array_merge($cookies, $cookie);

}
//var_dump($cookies);
$cookies = array_merge($cookies, array("mp_1799dc4067be971353b85127f766a0a4_mixpanel"=>'{"distinct_id":"166743a24d1a99-0f8a5d32f2bd72-20722047-f9bb6-166743a24d2276","$initial_referrer":"https://www.olx.ua/account/?ref[0][action]=myaccount&ref[0][method]=index","$initial_referring_domain":"www.olx.ua"};'));

$cookie2 = array();
foreach( $cookies as $key => $value ) {
  $cookie3[] = "{$key}={$value}";
};

$cookie2 = implode('; ', $cookie3); */


/*foreach($cookies as $ck=>$kk){
    echo "<br />".$ck.": ".$kk;
}
die;*/
/*****************************************************************************/
//file_put_contents("html.txt", $html);
$rucaptcha_in_response = "";
preg_match_all('#<div class="g-recaptcha" data-sitekey="(?<g_recaptcha_data_sitekey>.*?)"#s', $html, $g_recaptcha_data_sitekey_matches);
echo "<br />g_recaptcha_data_sitekey: ".$g_recaptcha_data_sitekey_matches['g_recaptcha_data_sitekey'][0];


$rucaptcha_in_response = file_get_contents("http://rucaptcha.com/in.php?key=".ANTI_CAPTCHA_KEY."&method=userrecaptcha&googlekey=".$g_recaptcha_data_sitekey_matches['g_recaptcha_data_sitekey'][0]."&pageurl=https://www.olx.ua/myaccount/");
$pieces = explode("|", $rucaptcha_in_response);
if($pieces[0]!="OK") { echo "<br />Задание на рукапче не создано!("; die;}
echo "<br />Задание на рукапча создано с id: ".$pieces[1];
/****************************************************************/
$g_recaptcha_response = "";
for($i=0;$i<100;$i++){
    $rucaptcha_resp_resp = file_get_contents("http://rucaptcha.com/res.php?key=".ANTI_CAPTCHA_KEY."&action=get&id=".$pieces[1]);
    //echo $rucaptcha_resp_resp;
    $mix = explode("|", $rucaptcha_resp_resp);
    if($mix[0] == "OK"){
        $g_recaptcha_response = $mix[1];
        echo "<br />Резльтат с рукапчи: ".$g_recaptcha_response;
        break;
    }
    sleep(5);
}

if(mb_strlen($g_recaptcha_response)<=0) {echo "<br />g_recaptcha не получен"; die;}
/**************************************************************************************/
//a: $g_recaptcha_response = "";




//$postdata= array("login[remember-me]"=>"on", "login[password]"=>USER_LOGIN, "login[email_phone]"=>USER_PASSWORD, "g-recaptcha-response"=>"fff");
$ch = curl_init("https://www.olx.ua/account/?ref%5B0%5D%5Baction%5D=myaccount&ref%5B0%5D%5Bmethod%5D=index");
curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
// curl_setopt($ch, CURLOPT_HEADER, false);
curl_setopt($ch, CURLOPT_HEADER, true);
//curl_setopt($ch, CURLOPT_USERAGENT,'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/70.0.3538.54 Safari/537.36');
//curl_setopt($ch, CURLOPT_NOBODY,true);
curl_setopt($ch, CURLOPT_COOKIEJAR, dirname(__FILE__).'/cookies/cookie.txt');
//curl_setopt($ch, CURLOPT_COOKIEFILE, dirname(__FILE__).'/cookies/cookie.txt');
curl_setopt($ch, CURLOPT_FOLLOWLOCATION, true);
curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, false);
curl_setopt($ch, CURLOPT_SSL_VERIFYPEER ,false);
curl_setopt($ch, CURLOPT_ENCODING , "gzip");
curl_setopt($ch, CURLOPT_HTTPHEADER, array(
'Host: www.olx.ua',
'Connection: keep-alive',
'Cache-Control: max-age=0',
'Origin: https://www.olx.ua',
'Upgrade-Insecure-Requests: 1',
'Content-Type: application/x-www-form-urlencoded',
'User-Agent: Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/73.0.3683.67 Safari/537.36',
'Accept: text/html,application/xhtml+xml,application/xml;q=0.9,image/webp,image/apng,*/*;q=0.8,application/signed-exchange;v=b3',
'Referer: https://www.olx.ua/account/?ref%5B0%5D%5Baction%5D=myaccount&ref%5B0%5D%5Bmethod%5D=index',
'Accept-Encoding: gzip, deflate, br',
'Accept-Language: ru-RU,ru;q=0.9,en-US;q=0.8,en;q=0.7',
'Cookie: '.'newrelicInited=0; mobile_default=desktop; dfp_segment_test=57; dfp_segment_test_v3=41; dfp_segment_test_v4=51; used_adblock=adblock_disabled; ldTd=true; _ga=GA1.2.1168431950.1552165050; _gid=GA1.2.549244820.1552165050; fingerprint=MTI1NzY4MzI5MTs0OzA7MDswOzA7MDswOzA7MDswOzE7MTsxOzE7MTsxOzE7MTsxOzE7MTsxOzE7MTswOzE7MTsxOzA7MDsxOzE7MTsxOzE7MTsxOzE7MTsxOzA7MTsxOzA7MTsxOzE7MDswOzA7MDswOzA7MTswOzE7MTswOzA7MTsxOzA7MDsxOzE7MDsxOzE7MTsxOzA7MTswOzI5NDkwNDQyNTA7MjsyOzI7MjsyOzI7MzsxMjM3Njc3NTc5OzE2NTk1ODk2NDk7MTsxOzE7MTsxOzE7MTsxOzE7MTsxOzE7MTsxOzE7MTsxOzA7MDswOzM1NjM2MzY2Nzs1MzgwOTg3Nzg7NDExNTIzMTc0NjszMzA4Mzg4NDE7MTAwNTMwMTIwMzsxOTIwOzEwODA7MjQ7MjQ7MTgwOzEyMDsxODA7MTIwOzE4MDsxMjA7MTgwOzEyMDsxODA7MTIwOzE4MDsxMjA7MTgwOzEyMDsxODA7MTIwOzE4MDsxMjA7MTgwOzEyMDswOzA7MA==; dfp_user_id=07d167fc-4d5f-9dd5-8351-d4518018af5b-ver2; laquesis=; laquesis_ff=; __utmc=250720985; __utmz=250720985.1552165051.1.1.utmcsr=(direct)|utmccn=(direct)|utmcmd=(none); _abck=C74630CD1C4E4F2690E097C9110160A0~0~YAAQbmReaGA/e19pAQAAUSI/ZAFf7jxA3DNLjK5vt0Hq+SBY3C7SZcKkc5/laSeTuDGK8hh9oKDxXzCSBDpvW47MKnzYnuocl16HvTXgcr/yPQaQhlBj7aE1+Pm2DXKATIkc1vcD7B5QRGFB64A9Z0XNdqg30uMmQ2WPJTYLhJLTAEQFLg6QRLkTS32eVLBnHJxH6G9Ig9EpN2P5hmbVQkpU30jtX49AtUqQt2o/ElqXflar8xhCWWB8ShQwKeZhqvgRR6ytfsv0jr8mVUqLN9c8kEG5M9Qlop3FLD+tvfCu7B3BGw==~-1~-1~-1; optimizelyEndUserId=oeu1552165050983r0.6800970867885556; newrelicInited=0; user_id=10114581; __diug=true; pt=26a8e5a82004054236f5f1f7519fae66518b10d5e994e96a7157fccb26c72d1a4985fc9ba57e39bfc02933592a1a3ca3e6eb15ec9f3baceec0cd7c636bdd261a; lister_lifecycle=1552165738; from_detail=0; dfp_segment=%5B%5D; ak_bmsc=601DF855A79ED292C6920716425683855C7B66AC975A000010F3845C0921A52A~pl6J0MQoonnSM22/aCfyoYlne7BQYgvysDpsXmf6JnQLW9u0XweAQefAMH4xsm08uIJTxJ1i59RNuxfGz+h/oeUkM5Z0B+X3cD1gb7l6ELkGX3dybyjNjuDKpwsPOjp4vwJLTM1T77vbQ/KfsA09aBSjtTBxbDtjAnNfn/P374H2byeJbdFKMw0De0MrnenYpiJrOhL7/L/uMIixtC8/YVh/moTD72cq+ctWMriOc4RSD587BUkftLK0BHUX2DI1qCCKJzEB4Vi+c3xN7wCpwvuazGIKgNspj0UL34UVR5J8gv83H11M/POHDHy0LL6RM+Uf8as9TyQfifGLckLf95yw==; PHPSESSID=d77c8ce0ac6ceb8e40503c06cb50ab0b006bf9c7; new_dfp_segment_dfp_user_id_07d167fc-4d5f-9dd5-8351-d4518018af5b-ver2=%5B%5D; bm_sz=70F81DE80ECC841903F9A7C3C607E032~YAAQp2Z7XIw5u1VpAQAAOuejZwOF0bYsnlwN8fPHRkvD0nXLT55iSoRhujnB7mrzQXzlxIzNiJ0Nxk+iGbMlm39rEv5QJX1DaRY+HU9+DfBmrPlbrCqWlACjpRPRvEafEgajlXfdAZ3rJOlX6hwcW318hz9X93t3k7Iu94LjVvYc2ViN8oGpRKyuPH4=; lqstatus=1552223187|||; __utma=250720985.1168431950.1552165050.1552219749.1552221987.3; __utmt=1; pvps=1; _gat_clientNinja=1; onap=169643f175ex294c9d48-3-16967a3dda6x8904b83-9-1552223886; __utmb=250720985.4.10.1552221987; bm_sv=1B0AE36B019521220B759E821AC58946~z0XHUrNcrZJ39VTeZ16sREVDZyBQQaOM+K52kxFMxgTmpD+afI8CCCS8Hm3IP0Nlkh5y9PqlVV5LIhIlGJ74RwIHFZVM8liiUdA9+v0qDt8ckTUYF2nUsAqmniXtcTJL3JzkA2figAPAmV+T3JDCXZ2FPEq4+JxZKUm/Mq78aHY='
));
                   echo "<br /><b>login[password]=".USER_PASSWORD."&login[email_phone]=".USER_LOGIN."&g-recaptcha-response=".$g_recaptcha_response."</b>";
curl_setopt($ch, CURLOPT_POST, true);         //$postdata= array("login[remember-me]"=>"on", "login[password]"=>USER_LOGIN, "login[email_phone]"=>USER_PASSWORD, "g-recaptcha-response"=>$g_recaptcha_response);
//curl_setopt($ch, CURLOPT_POSTFIELDS, "login[remember-me]=on&login[password]=".USER_PASSWORD."&login[email_phone]=".USER_LOGIN."&g-recaptcha-response=".$g_recaptcha_response);   //print_r(http_build_query($postdata));
curl_setopt($ch, CURLOPT_POSTFIELDS, "login[password]=".USER_PASSWORD."&login[email_phone]=".USER_LOGIN."&g-recaptcha-response=".$g_recaptcha_response);   //print_r(http_build_query($postdata));
echo "<br />POST выполнен";
$html=curl_exec($ch);
echo $html;
/*$olxconnect = new olxconnect(USER_LOGIN, USER_PASSWORD, ANTI_CAPTCHA_KEY);

//$postdata= array("login[password]"=>USER_PASSWORD, "login[email_phone]"=>USER_LOGIN, "g-recaptcha-response"=>"03AMGVjXj_k74xMKtPkg5QY0Y-1aMb49kMfWcGDJZ4CcruqemySn0XovCysDYmqlL53Dtpa_r1Bits53mWgt-ZwdM6bwfIbQEG23kPXK4w1cwtfiOjYCu-1wuPG7iOkZ-LY5_gUlpvP7LMG8QUoMd9KXkYGcna_e98R1VGBAsfqlvU56uEW1HzU5dp4lyUGIfMsw1xCsn6XsD863Q10kOg0cN11EERbZSEGgtZNb43uIpefg5-mr51RwKV1woLEgPW0aIMXI-2HCHOkjLKpXZUVTwIWAVsZfStEA");
//echo  $olxconnect->POSTquery2("https://www.olx.ua/account/?ref[0][action]=myaccount&ref[0][method]=index", $postdata); die;

// получение кода со страницы https://www.olx.ua/myaccount/
$myaccount_page_html = $olxconnect->get_myaccount_page_html();
if(!$myaccount_page_html) die; // завершить, если не загрузилась страница
echo $myaccount_page_html;
//echo $olxconnect->get_ru_captcha_task_id();

echo $olxconnect->login(); die;




//$postdata= array("login[remember-me]"=>"on","login[password]"=>"28091989", "login[email_phone]"=>"ksjyn4ik@bigmir.net", "g-recaptcha-response"=>$g_recaptcha_response);
$postdata= array("login[password]"=>"28091989", "login[email_phone]"=>"ksjyn4ik@bigmir.net", "g-recaptcha-response"=>$g_recaptcha_response);
print_r($olxconnect->POSTquery("https://www.olx.ua/account/?ref[0][action]=myaccount&ref[0][method]=index", $postdata));
//print_r($olxconnect->POSTquery("https://www.olx.ua/myaccount/settings/", $postdata));*/
?>