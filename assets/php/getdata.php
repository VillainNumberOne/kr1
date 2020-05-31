<?php
include 'functions.php';

$exchange = $_GET['ex'];
$datetime_from = $_GET['date_from'];
$datetime_to = $_GET['date_to'];
$resolution = $_GET['resolution'];
$pair = $_GET['pair'];

// echo "$exchange $datetime_from $datetime_to $resolution $pair";

$output = shell_exec("python3 ../python/get_ohlc_ac.py $exchange $datetime_from $datetime_to $resolution $pair");

// print_fancy($output);

echo $output;


 ?>
