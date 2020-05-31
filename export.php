<?php
include 'assets/php/functions.php';
update_session();

if (!empty($_GET['format'])) $format = $_GET['format'];
else $format = "csv";


$exchange = $_SESSION['last_exchange'];
$datetime_from = strtotime($_SESSION['last_datetime_from']);
$datetime_to = strtotime($_SESSION['last_datetime_to']);
$resolution = $_SESSION['last_resolution'];
$symbol = $_SESSION['last_symbol'];

header('Content-type: text/plain');
header('Content-Disposition: attachment; filename="dataset.'.$format.'"');

$output = shell_exec("python3 assets/python/export.py $exchange $datetime_from $datetime_to $resolution $symbol $format");

echo $output;

?>
