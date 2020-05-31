<?php

ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

function print_fancy($text){
  print_r("<pre>".$text."</pre>");
}

function print_fancy_a($text){
  echo "<pre>";
  var_dump($text);
  echo "</pre>";
}

function del_pre($text){
  $text = str_replace("<pre>", "", $text);
  $text = str_replace("</pre>", "", $text);
  return $text;
}

function connect(){
  require '../../../../../home/m/classified/connection.php';

  $mysqli = new mysqli($host, $user, $password, $database);
  if ($mysqli->connect_errno) {
    echo "Не удалось подключиться к MySQL: (" . $mysqli->connect_errno . ") " . $mysqli->connect_error;
  }
  $mysqli->query("SET NAMES UTF8");
  $mysqli->query("SET CHARACTER SET UTF8");

  return $mysqli;
}

function select2array($query){
  $mysqli = connect();

  $result = $mysqli->query($query);
  $mysqli->close();

  $array = [];
  while($row = $result->fetch_array(MYSQLI_ASSOC)){
    array_push($array, $row);
  }

  return $array;
}

function execute_query($query){
  $mysqli = connect();
  $result = $mysqli->query($query);
  echo $mysqli->error;
  $mysqli->close();
}

function get_symbols($exchange){
  $symbols = select2array("SELECT `symbols` FROM `Exchanges` WHERE `Exchanges`.`name` = '$exchange'");
  return explode(',',$symbols[0]['symbols']);
}

function get_intervals($exchange){
  $symbols = select2array("SELECT `intervals` FROM `Exchanges` WHERE `Exchanges`.`name` = '$exchange'");
  return explode(',',$symbols[0]['intervals']);
}



function update_exchange_data(){
  $current_date = new DateTime();
  $now = $current_date->getTimestamp();
  $now = (int)$now;
  $names = select2array("SELECT `name`, `last_update`, `id` FROM `Exchanges` WHERE 1");

  for ($i = 0; $i < count($names); $i++){
    if ((int)$now - (int)$names[$i]['last_update'] > 86400){
      $name = $names[$i]['name'];
      $id = $names[$i]['id'];
      $url = "http://localhost/assets/php/getexchangedata.php?ex=".$name;
      $symbols = file_get_contents($url);
      $count = count(explode(',',$symbols));

      execute_query("UPDATE `Exchanges` SET `last_update` = '$now', `symbols` = '$symbols', `symbols_count` = '$count' WHERE `id` = $id");
    }
  }


}

function get_colors($indicator_array){
  $colors = [];
  for ($i = 0; $i < count($indicator_array); $i++){
    array_push($colors, $indicator_array[$i][3]);
  }
  $colors_str = implode(",", $colors);
  return "['".$colors_str."','#11a68a']";
}

// { name: 'line', type: 'line', data: SMA(t_ohlc, 0, 10)},

function get_indicators($indicator_array){
  $indicators = [];
  for ($i = 0; $i < count($indicator_array); $i++){

    $apply_str = $indicator_array[$i][1];
    if ($apply_str == 'Open') $apply = 0;
    if ($apply_str == 'High') $apply = 1;
    if ($apply_str == 'Low') $apply = 2;
    if ($apply_str == 'Close') $apply = 3;
    if ($apply_str == 'Avg') $apply = 4;
    $period = $indicator_array[$i][2];

    $name = "'".$indicator_array[$i][0]."(".$indicator_array[$i][2].", ".$apply_str.")'";
    $type = "'line'";
    $data = $indicator_array[$i][0]."(t_ohlc, ".$apply.", ".$period.")";

    $current_line = "{name: $name, type: $type, data: $data},";
    array_push($indicators, $current_line);
  }

  // print_fancy_a($indicators);
  return implode(',',$indicators);
}

function get_ohlc_ac($exchange, $datetime_from, $datetime_to, $resolution, $pair){
  $ohlc = htmlspecialchars(file_get_contents("http://localhost//assets/php/getdata.php?ex=$exchange&data=ohlcva&format=ac&date_from=$datetime_from&date_to=$datetime_to&resolution=$resolution&pair=$pair"));
  return $ohlc;
}


function update_session(){
  if(session_id() == '' || !isset($_SESSION)) {
      session_start();
      $_SESSION['LAST_ACTIVITY'] = time();
  }

  if (isset($_SESSION['LAST_ACTIVITY']) && (time() - $_SESSION['LAST_ACTIVITY'] > 1800)) {
      session_unset();
      session_destroy();
  }
  $_SESSION['LAST_ACTIVITY'] = time();

}



 ?>
