<?php
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

include 'functions.php';
update_session();

unset($_SESSION['indicators']);

// print_fancy($_SESSION['test_ok']);
// print_fancy_a($_SESSION['indicators']);

// <?php for ($i = 0; $i < count($indicators_js); $i++) echo $indicators_js[$i]; 
?>
