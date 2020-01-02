<?php
$json = file_get_contents("export.json");
$arr = json_decode($json);

$res = "";
foreach($arr as $a){
    if($a->name=="PHPSESSID" || $a->name=="a_access_token" || $a->name=="remember_login" || $a->name=="a_refresh_token" || $a->name=="_abck" || $a->name=="bm_sv" || $a->name=="mobile_default" || $a->name=="dfp_segment_test_v3" || $a->name=="dfp_segment_test" || $a->name=="dfp_segment_test_v4" || $a->name=="lister_lifecycle"){
        $res .= $a->name."=".$a->value."; ";
    }
}
echo trim($res);
?>