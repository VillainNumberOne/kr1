<?php
include 'assets/php/functions.php';
update_session();

$ok = 1;

// if (empty($_SESSION['last_chart_args'])) $ok = 0;

if (empty($_GET['apply'])) $ok = 0; else $apply = $_GET['apply'];
if (empty($_GET['period'])) $ok = 0; else $period = $_GET['period'];
if (empty($_GET['color'])) $ok = 0; else $color = $_GET['color'];
if (empty($_GET['indicator'])) $ok = 0; else $indicator = $_GET['indicator'];

if ($ok == 1){
  $indicator_array = [$indicator, $apply, $period, $color];
  if (empty($_SESSION['indicators'])) $_SESSION['indicators'] = [$indicator_array];
  else {
    $_SESSION['indicators'] = [$indicator_array];
  }
}
else $_SESSION['test_ok'] = "NOT OK(((((( $ok";


// if(!$ok) header("Location: /chart.php");


?>
