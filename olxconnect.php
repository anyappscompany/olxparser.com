<?php
class olxconnect{
    private $user_login;
    private $user_password;
    private $myaccount_page_html;
    private $g_recaptcha_data_sitekey;
    private $rucaptcha_task_id;
    private $g_recaptcha_response;
    private $anti_captcha_key;
    private $cookies;
    function olxconnect($_user_login, $_user_password, $_anti_captcha_key){
        $this->user_login = $_user_login;
        $this->user_password = $_user_password;
        $this->anti_captcha_key = $_anti_captcha_key;
        $this->myaccount_page_html = "";
        $this->g_recaptcha_data_sitekey = "";
        $this->rucaptcha_task_id = "";
        $this->cookies = array();
    }
    // получение кода со страницы https://www.olx.ua/myaccount/
    function get_myaccount_page_html(){
        for($i=0; $i<5; $i++){
            try{
                $this->myaccount_page_html = $this->GETquery("https://www.olx.ua/myaccount/");   //echo "<hr />".$this->myaccount_page_html;
                if(preg_match("/<section class=\"login-page\">/ui", $this->myaccount_page_html)) return $this->myaccount_page_html;
            }catch(Exception $e){
                echo '<br />Выброшено исключение: ',  $e->getMessage();
            }
            sleep(3);
        }
        return false;
    }
    // отправка на рукапчу задания и получние его ID
    function get_ru_captcha_task_id(){
        $rucaptcha_in_response = "";
        preg_match_all('#<div class="g-recaptcha" data-sitekey="(?<g_recaptcha_data_sitekey>.*?)"><\/div>#s', $this->myaccount_page_html, $g_recaptcha_data_sitekey_matches);
        $this->g_recaptcha_data_sitekey = $g_recaptcha_data_sitekey_matches['g_recaptcha_data_sitekey'][0];
        echo "<br />g_recaptcha_data_sitekey: ".$this->g_recaptcha_data_sitekey;
        for($i=0; $i<5; $i++){
            try{
                $rucaptcha_in_response = file_get_contents("http://rucaptcha.com/in.php?key=".$this->anti_captcha_key."&method=userrecaptcha&googlekey=".$this->g_recaptcha_data_sitekey."&pageurl=https://www.olx.ua/account/?ref[0][action]=myaccount&ref[0][method]=index");
                $pieces = explode("|", $rucaptcha_in_response);
                if($pieces[0]=="OK"){
                    $rucaptcha_task_id = $pieces[1];    echo "<br />Задание на рукапча создано с id: ".$pieces[1];
                    return $pieces[1];
                }
        }catch(Exception $e){
                echo '<br />Выброшено исключение: ',  $e->getMessage();
            }
            sleep(2);
        }
        echo "<br />Задание на рукапча не создано";
        return false;
    }
    // получение результатов с рукапчи
    function get_g_recaptcha_response(){
        $task = $this->get_ru_captcha_task_id();             //echo $task;
        if($task === false) return false; // если задание не создалось

        for($i=0;$i<100;$i++){
            try{
                $rucaptcha_resp_resp = file_get_contents("http://rucaptcha.com/res.php?key=".$this->anti_captcha_key."&action=get&id=".$task);
                $mix = explode("|", $rucaptcha_resp_resp);
                if($mix[0] == "OK"){
                    echo "<br />Резльтат с рукапчи: ".$mix[1];
                    return $mix[1];
                }
                //if($rucaptcha_resp_resp == "CAPCHA_NOT_READY") $i--;
                }catch(Exception $e){
                        echo '<br />Выброшено исключение: ',  $e->getMessage();
                }
                sleep(3);
        }
        echo "<br />Результат с рукапчи не получен";
        return false;
    }

    // вход на olx
    function login(){                         //paybalance-box__inner
        $recaptcha_response = $this->get_g_recaptcha_response();
        if($recaptcha_response === false) return false;

        $olx_login_html = "";
        $postdata= array("login[remember-me]"=>"on", "login[password]"=>$this->user_password, "login[email_phone]"=>$this->user_login, "g-recaptcha-response"=>$recaptcha_response);


        //for($i=0; $i<3; $i++){
            try{
                echo "<br />Вход на OLX. Попытка ".$i." ".http_build_query($postdata);
                //echo "[".$i."]";
                //echo "<hr />".$olx_login_html;

                $olx_login_html = $this->POSTquery("https://www.olx.ua/account/?ref[0][action]=myaccount&ref[0][method]=index", $postdata);
                echo $olx_login_html;
                if(preg_match("/paybalance-box__inner/ui", $olx_login_html)){
                    echo "LOGIN TRUE";
                    return true;
                }
            }catch(Exception $e){
                        echo '<br />Выброшено исключение: ',  $e->getMessage();
                }
            sleep(20);
        //}
        echo "LOGIN FALSE";
        return false;
    }

    function GETquery($url){
        $ch = curl_init($url);


        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
         curl_setopt($ch, CURLOPT_HEADER, true);
        curl_setopt($ch, CURLOPT_USERAGENT,'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/70.0.3538.54 Safari/537.36');
        //curl_setopt($ch, CURLOPT_COOKIEJAR, $cookiefiles);
        //curl_setopt($ch, CURLOPT_NOBODY,true);
        //curl_setopt($ch, CURLOPT_COOKIEFILE, $cookiefiles);
        curl_setopt($ch, CURLOPT_COOKIEJAR, dirname(__FILE__).'/cookie.txt');
        curl_setopt($ch, CURLOPT_COOKIEFILE, dirname(__FILE__).'/cookie.txt');
        curl_setopt($ch, CURLOPT_FOLLOWLOCATION, true);
        curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, false);
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER ,false);
        curl_setopt($ch, CURLOPT_HTTPHEADER, array(
    'Host: www.olx.ua',
'Connection: keep-alive',
'Upgrade-Insecure-Requests: 1',
'User-Agent: Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/70.0.3538.54 Safari/537.36',
'Accept: text/html,application/xhtml+xml,application/xml;q=0.9,image/webp,image/apng,*/*;q=0.8',
'Referer: https://www.olx.ua/',

'Accept-Language: ru-RU,ru;q=0.9,en-US;q=0.8,en;q=0.7'
));
        //////курл
        $html=curl_exec($ch);

        preg_match_all('/^Set-Cookie:\s*([^;]*)/mi', $html, $matches);        // get cookie

foreach($matches[1] as $item) {
    parse_str($item, $cookie);
    $this->cookies = array_merge($this->cookies, $cookie);
}
//print_r($this->cookies);

        return $html;
        unset($url);
    }

    function POSTquery($url, $postdata){
        $ch = curl_init($url);

        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        // curl_setopt($ch, CURLOPT_HEADER, false);
        curl_setopt($ch, CURLOPT_HEADER, true);
        curl_setopt($ch, CURLOPT_USERAGENT,'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/70.0.3538.54 Safari/537.36');
        //curl_setopt($ch, CURLOPT_COOKIEJAR, $cookiefiles);
        //curl_setopt($ch, CURLOPT_NOBODY,true);
        //curl_setopt($ch, CURLOPT_COOKIEFILE, $cookiefiles);
        curl_setopt($ch, CURLOPT_COOKIEJAR, dirname(__FILE__).'/cookie.txt');
        curl_setopt($ch, CURLOPT_COOKIEFILE, dirname(__FILE__).'/cookie.txt');
        curl_setopt($ch, CURLOPT_FOLLOWLOCATION, true);
        curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, false);
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER ,false);
        //curl_setopt($ch, CURLOPT_COOKIEFILE, 'cookies.jar');
        //curl_setopt($ch, CURLOPT_COOKIEJAR, 'cookies.jar');
        curl_setopt($ch, CURLOPT_HTTPHEADER, array(
    'Cache-Control: max-age=0',
'Accept: text/html,application/xhtml+xml,application/xml;q=0.9,image/webp,image/apng,*/*;q=0.8',
'Accept-Encoding: gzip, deflate, br',
'Accept-Language: ru-RU,ru;q=0.9,en-US;q=0.8,en;q=0.7',
'User-Agent: Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/70.0.3538.67 Safari/537.36',
'Content-Type: application/x-www-form-urlencoded',
'Referer: https://www.olx.ua/account/?ref%5B0%5D%5Baction%5D=myaccount&ref%5B0%5D%5Bmethod%5D=index',
'Origin: https://www.olx.ua',
'Upgrade-Insecure-Requests: 1',
'Connection: keep-alive',
'Host: www.olx.ua',
'Cookie: mobile_default=desktop; optimizelyEndUserId=oeu1458859784325r0.9095683588241277; favouritesBar=1; ldTd=true; optimizelySegments=%7B%221033243617%22%3A%22none%22%2C%221039805884%22%3A%22false%22%2C%221044912896%22%3A%22search%22%2C%221099920464%22%3A%22gc%22%7D; optimizelyBuckets=%7B%228225978916%22%3A%228239180794%22%7D; smcx_328387_last_shown_at=1490971076623; highlight_safedeal_search_filter=true; pa=1499622585228.61550.8539757546512878www.olx.ua0.5476592408536907+1; smcx_378357_last_shown_at=1500577472395; highlight_promoteme=true; last_paidads_code_topupaccount=topupaccount_49; last_paidads_provider_topupaccount=payment_chk_2; __zlcmid=j5gpm7w8BxfdFv; _ga=GA1.2.1701745862.1466705667; dfp_segment_test=48; dfp_user_id=8dbd78c4-1905-93d8-b1e7-4ffcea47a9b5; __utmc=250720985; optimizelyBuckets=%7B%228577982582%22%3A%228576051517%22%2C%228455803748%22%3A%228462611249%22%2C%229858968603%22%3A%229940516722%22%7D; surveyPopupInited=rollingNPSOLXUAwave1; dfp_segment_test_v3=73; lister_lifecycle=1519650720; used_adblock=adblock_disabled; sawSaveLayer=1; __gads=ID=5b16810934c53b2e:T=1522953964:S=ALNI_MZ354bYjj9HP8mvI7c_BXAiN6EoAQ; smcx_0_last_shown_at=1523987082134; searchFavTooltip=1; lang=ru; dfp_segment_test_v4=21; fingerprint=fbdc4f53959cdb4af47c1cffae5cc2e300d2d70001a8f455d11dae88fc3236ac3fef60c9cf99daee3fef60c9cf99daeefaed307981c3a6ca4e1f7a2acddfea3321f2c817b2cc220c3fef60c9cf99daee3fef60c9cf99daee4e1f7a2acddfea33b497a357830277b800d2d70001a8f455308e012c59cf7bdd93ba89d2bc096e2993ba89d2bc096e295a1778be62509b00e8380ebb100f22a6ea59d4056f003ddf2c6a8939be9dd0d3f6e9813aa2a88b6b5bca2f51cd5bb96f628eb7a8a649586881c63c85d4a9e431841ff26a6ec22c3e6255da10575393646255da105753936400ab77cc9433c49756b16d11aecc8186428983c35d9b74cafa3266dd6c454ee009470efce3c1f17e279d401915fc1588fc9233e1090cf76937273db529111a0de0a8d9bd2152e9e06df5e1f064573a1144ade9b70b076f747cf587ca35748f297cf587ca35748f297cf587ca35748f297cf587ca35748f297cf587ca35748f297cf587ca35748f297cf587ca35748f297cf587ca35748f297cf587ca35748f297cf587ca35748f29e4788261c6c83237; last_locations=338-0-0-%D0%98%D0%B7%D1%8E%D0%BC-%D0%A5%D0%B0%D1%80%D1%8C%D0%BA%D0%BE%D0%B2%D1%81%D0%BA%D0%B0%D1%8F+%D0%BE%D0%B1%D0%BB%D0%B0%D1%81%D1%82%D1%8C-izyum_69413-0-0-%D0%91%D1%83%D0%B4%D0%B5%D0%BD%D0%B5%D1%86-%D0%A7%D0%B5%D1%80%D0%BD%D0%BE%D0%B2%D0%B8%D1%86%D0%BA%D0%B0%D1%8F+%D0%BE%D0%B1%D0%BB%D0%B0%D1%81%D1%82%D1%8C-budenets_11963-0-0-%D0%94%D0%BD%D0%B5%D0%BF%D1%80%D0%BE%D0%B2%D0%BE%D0%B5-%D0%94%D0%BD%D0%B5%D0%BF%D1%80%D0%BE%D0%BF%D0%B5%D1%82%D1%80%D0%BE%D0%B2%D1%81%D0%BA%D0%B0%D1%8F+%D0%BE%D0%B1%D0%BB%D0%B0%D1%81%D1%82%D1%8C-dneprovoe; NREUM=s=1537173533885&r=775051&p=697938; disabledgeo=1; _gid=GA1.2.165558398.1539454681; ab_test_device_id=e350c935-e470-4ea4-a527-ed902688cd86; user_business_status=normal; dfp_seg_test_mweb_v7=50; is_tablet=0; a_refresh_token=c706fa5fdd9cebdbdedcec9d0c9524715531fb9a; a_grant_type=device; mweb_observed_id=257408467; _abck=BD97A609DE67C2168A56685792D2E70650EFDEB436250000EA15205B09F39034~0~508iWysQseu1AUqx26exX9qJ9d7KLhj33ByWGFZKjyc=~-1~-1; dfp_segment_test_v2=50; cookieBarSeenV2=true; __utmz=250720985.1539637036.1145.103.utmcsr=google|utmccn=(organic)|utmcmd=organic|utmctr=(not%20provided); a_access_token=e916fd1ea47b077755f6cb7293a61e10936c6964; grant_type=password; optimizelySegments=%7B%221033243617%22%3A%22none%22%2C%221039805884%22%3A%22false%22%2C%221043958548%22%3A%22referral%22%2C%221044912896%22%3A%22search%22%2C%221066831625%22%3A%22none%22%2C%221087063111%22%3A%22gc%22%2C%221099920464%22%3A%22gc%22%2C%221107551002%22%3A%22true%22%7D; widthHeader=true; mp_1799dc4067be971353b85127f766a0a4_mixpanel=%7B%22distinct_id%22%3A%20%22166743a24d1a99-0f8a5d32f2bd72-20722047-f9bb6-166743a24d2276%22%2C%22%24initial_referrer%22%3A%20%22https%3A%2F%2Fwww.olx.ua%2Faccount%2F%3Fref%255B0%255D%255Baction%255D%3Dmyaccount%26ref%255B0%255D%255Bmethod%255D%3Dindex%22%2C%22%24initial_referring_domain%22%3A%20%22www.olx.ua%22%7D; user_id=10114581; bm_sz=08479E924EA19BF51F8A4111780DD538~QAAQXmReaOxaQDZmAQAA5eUsg2elTxuwlNwxCq96nh6m+AlTC2P6gvHgQwFtWC0K5koXnHrEKG9BgjXid0Gh+6LG0QfSHphE67TtgVpu2RA2QWxEcaEIUUNzTj5eNR8P0cHYeGCl3z8DAwsWc0BS8uuwHXBgpjOHmLUSX117M+Ks+E+36A+HZnXAQLjgMZE=; newrelicInited=0; bm_mi=19BD1A94E61ADF20DC8960352976540C~mIUyZ1XUOfpc6YxqRljo3KFXeSaPAfrUQsXJguUKhe8c/M2QBkavy5RjPn3CnPIk7EIh5sp+nDM2e2DMaRHHY86EFxbCJpFF1aLZng+ClF1TKu7kBpEWtcrpvJKaDSi3tJqXsU4wGGiXq6OlvLBJf72uM/PeXugnN5kgbwl7uCRhItaZ2K6Utip0r2cTYq3vlsNWBdtYiac2FZ8Ss6towNkgVQbBvdVhuFXKLE83jDCj2qtF4lP4ihpI5ZsrGK08uQFFxNMJ1/SJP9bl7Znnmw==; __utma=250720985.1701745862.1466705667.1539799051.1539806494.1160; ak_bmsc=C44DD461A23075EFC8E8FFDC2BD50263685E6434403C00001D95C75BE7C55C18~plB3GNy40AnVLAzfnkyFaiHwMAnVKLXCjmh3SONxgJYUIHxknUqjUh7iQq9QKEvFp5IYGUYDAY5VNCYhvD2b0Cco9fYwCThdKAzapmN2qB90qch3SE0xTFNHHshH/pPB5Ku7vIf2MJ1AGQoJwb3a47Spt5hky6CZo7U5JI+9sOlmBIrPyEWl3diJEn+x3jrO5V0WD4ysgtwifp4/9IYlAUS3gychT+LE6FpCVfjW/dpl7SEE02+0sYqr0IneEUo0yE; PHPSESSID=27c5488e15e139ab756db51e9d16cb0708c94e69; pt=e359971d6fd39d57a0093bb9efc2d926d471ed37409b832c6daea330706e3e2298e66c994fedd684919cc33dc5c29923e003be2a16e73a05d81ae83a3c3fa348; onap=160dfcd245fxceeda63-1166-166839e7021x1dbdda5-26-1539811728; mp_1799dc4067be971353b85127f766a0a4_mixpanel=%7B%22distinct_id%22%3A%20%22166743a24d1a99-0f8a5d32f2bd72-20722047-f9bb6-166743a24d2276%22%2C%22%24initial_referrer%22%3A%20%22https%3A%2F%2Fwww.olx.ua%2Faccount%2F%3Fref%255B0%255D%255Baction%255D%3Dmyaccount%26ref%255B0%255D%255Bmethod%255D%3Dindex%22%2C%22%24initial_referring_domain%22%3A%20%22www.olx.ua%22%2C%22%24search_engine%22%3A%20%22google%22%7D; pvps=1; from_detail=1; __utmb=250720985.7.10.1539806494; bm_sv=3572E2CDD87DAC1FBC930CAB77EC36ED~H2Pbz2eQgGGo5KeUbFZjMQmz+SHMxupDMJE3r81uyynA5+eV8Nbbdj8oYDLdlrrGKZ/F8CZ83iY3CTRwC1B5Z7cJxi3eWqa/B5dh8Yce4hb4Kz1RDDqgKFjZ3NxP46WKUjRdzEBA0o/6OXjNrILFOJGkfXpyX/G8NvMbf8qnhaY='

));

        curl_setopt($ch, CURLOPT_POST, true);
        curl_setopt($ch, CURLOPT_POSTFIELDS, http_build_query($postdata));   //print_r(http_build_query($postdata));
        //////курл
        $html=curl_exec($ch);
        return $html;
        unset($url);
    }



}

?>