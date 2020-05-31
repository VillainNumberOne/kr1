<?php


?>

<!DOCTYPE html>
<html lang="en" dir="ltr">
  <head>
    <meta charset="utf-8">
    <title></title>
  </head>
  <body>
    <form action="assets/php/getdata.php" method="get">
      <input type="text" name="ex" value="exmo">
      <input type="text" name="data" value="ohlcva">
      <input type="text" name="format" value="csv"><br>
      <input type="date" name="date_from" value="" required>
      <input type="date" name="date_to" value="" required><br>
      <input type="text" name="resolution" value="D" required><br>
      <input type="text" name="currency_b" value="BTC" required><br>
      <input type="text" name="currency_s" value="RUB" required><br>
      <input type="submit">
    </form>
  </body>
</html>
