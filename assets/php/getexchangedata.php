<?php
include 'functions.php';

$exchange = htmlspecialchars($_GET['ex']);

$output = shell_exec("python3 ../python/getexchangedata.py $exchange");

echo $output;
?>
