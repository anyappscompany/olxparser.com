<!DOCTYPE HTML>

<html>

<head>
  <title></title>
  <meta charset="utf-8">
  <style>
  .clip{
    white-space: nowrap; /* Запрещаем перенос строк */
    overflow: hidden; /* Обрезаем все, что не помещается в область */
    background: #E5E5FF; /* Цвет фона */
    text-overflow: ellipsis; /* Добавляем многоточие */
    width:400px;
    margin: 0px;
    padding: 0px;
   }
   .clip-today{
    white-space: nowrap; /* Запрещаем перенос строк */
    overflow: hidden; /* Обрезаем все, что не помещается в область */
    background: #66CC66; /* Цвет фона */
    text-overflow: ellipsis; /* Добавляем многоточие */
    width:400px;
    margin: 0px;
    padding: 0px;
   }

   .idrow{
       background-color: #292929;
       color:#E5E5FF;
   }
   a{
       color: #000000;
       font-weight: bold;
   }
   .ob-active{
       background-color: #E5EEFF;
   }
   .ob-off{
       background-color: #FFB8B8;
   }

  </style>
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


$content = "";
$result = mysql_query("SELECT * FROM products ORDER BY id ASC", $db);
$result2 = mysql_query("SELECT * FROM updates ORDER BY id ASC", $db);

$updates = array();

$content .= "<table border='0'><tr><td>title</td>";
while ($row2 = mysql_fetch_array($result2)) {
        $content .= "<td>".$row2['create_date']."</td>";
         array_push($updates, $row2);
}
$content .= "</tr>";

while ($row = mysql_fetch_assoc($result)) {
    $content .= "<tr>";

    $dat = date('Y-m-d', strtotime($row['create_ad_date']));
    if($dat === date("Y-m-d")){
        $content .= "<td><p class='clip-today' title='".$row['title']."'><span class='idrow'>".$row['id']."</span> <a target='_blank' href='".$row['page']."'>".$row['title']."</a></p></td>";
    }else{
        $content .= "<td><p class='clip' title='".$row['title']."'><span class='idrow'>".$row['id']."</span> <a target='_blank' href='".$row['page']."'>".$row['title']."</a></p></td>";
    }
    foreach($updates as $upd){
        $active = false;
        $goods_arr = json_decode($upd['data']);
        foreach($goods_arr as $ga){
            if($ga->page == $row['page']){
                $active = true;
                break;
            }else{
                $active = false;
            }
        }

        if($active){
            $content .= "<td class='ob-active'></td>";
        }else{
            $content .= "<td class='ob-off'></td>";
        }
    }
    $content .= "</tr>";
}
$content .= "</table>";




echo $content;



mysql_close($db);
?>
</body>

</html>