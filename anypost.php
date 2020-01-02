<?php

$ch = curl_init("https://www.olx.ua/myaccount/answers/");
curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
// curl_setopt($ch, CURLOPT_HEADER, false);
curl_setopt($ch, CURLOPT_HEADER, true);
//curl_setopt($ch, CURLOPT_USERAGENT,'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/70.0.3538.54 Safari/537.36');
//curl_setopt($ch, CURLOPT_NOBODY,true);
//curl_setopt($ch, CURLOPT_COOKIEJAR, dirname(__FILE__).'/cookie.txt');
//curl_setopt($ch, CURLOPT_COOKIEFILE, dirname(__FILE__).'/cookies/cookie2.txt');
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
'User-Agent: Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/70.0.3538.67 Safari/537.36',
'Accept: text/html,application/xhtml+xml,application/xml;q=0.9,image/webp,image/apng,*/*;q=0.8',
'Referer: https://www.olx.ua/account/?ref%5B0%5D%5Baction%5D=myaccount&ref%5B0%5D%5Bmethod%5D=index',
'Accept-Encoding: gzip, deflate, br',
'Accept-Language: ru-RU,ru;q=0.9,en-US;q=0.8,en;q=0.7',
'Cookie: mobile_default=desktop; dfp_segment_test=48; dfp_segment_test_v3=73; lister_lifecycle=1519650720; dfp_segment_test_v4=21; a_refresh_token=c706fa5fdd9cebdbdedcec9d0c9524715531fb9a; a_access_token=da592fce865fab20c9ba21ed0cb4bac946d50db3; PHPSESSID=a91b8a0d9a5278ecc754771ec1d781f930a6fa2a; remember_login=23865891%3A1541622429%3B090b02f61c4ed77dab6479570e328df9; _abck=BD97A609DE67C2168A56685792D2E70650EFDEB436250000EA15205B09F39034~0~508iWysQseu1AUqx26exX9qJ9d7KLhj33ByWGFZKjyc=~-1~-1; bm_sv=903CCC11BED36E820348F672E9C3F9EC~XwPAnHAjcadhSekcY7IgSVu9U8zjV6/VUZnWrrJmEUttAOHouZmjgD6yZpsosU6//BD/wTvmzBIzaEanZGEoXiasm+YU5mM5sqjI3OcKunCbWB/jvlI2LDjMrIL/ppfUpYOFCv+vUa8/Jhm1QsyrPG/xlKCVitEPo75eclejYOk=;'
));
//                   echo "<br /><b>login[remember-me]=on&login[password]=".USER_PASSWORD."&login[email_phone]=".USER_LOGIN."&g-recaptcha-response=".$g_recaptcha_response."</b>";
//curl_setopt($ch, CURLOPT_POST, true);         //$postdata= array("login[remember-me]"=>"on", "login[password]"=>USER_LOGIN, "login[email_phone]"=>USER_PASSWORD, "g-recaptcha-response"=>$g_recaptcha_response);
//curl_setopt($ch, CURLOPT_POSTFIELDS, "login[remember-me]=on&login[password]=".USER_PASSWORD."&login[email_phone]=".USER_LOGIN."&g-recaptcha-response=".$g_recaptcha_response);   //print_r(http_build_query($postdata));
//echo "<br />POST выполнен";
$html=curl_exec($ch);
echo $html;

?>