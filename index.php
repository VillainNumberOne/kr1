<?php
include 'assets/php/functions.php';

update_session();

header("Location: /chart.php");


?>


<!DOCTYPE html>
<html lang="ru" dir="ltr">
  <head>
    <meta charset="utf-8">
    <title>Агрегатор исторических данным по криптовалютам</title>
    <link href="https://fonts.googleapis.com/css2?family=Montserrat:ital,wght@0,400;0,500;0,600;0,900;1,400;1,500&family=Open+Sans&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="assets/styles/styles.css">
  </head>
  <body>
    <center>

      <div class="line"  style="margin-top: 200px;"></div>
      <div class="table_element">
        <div class="table_item_n_i">
          1. <img src="https://s2.coinmarketcap.com/static/img/exchanges/32x32/270.png" alt="Binance" width="16" height="16">
        </div>
        <div class="table_item_name">
          Binance
        </div>
        <div class="table_item_cc_n">
          10
        </div>
        <div class="table_item_btc_volume">
          150
        </div>
        <div class="table_item_chart">
          График
        </div>
        <div class="table_item_download">
          Скачать
        </div>

      </div>
      <div class="line"></div>
      <div class="table_element">

      </div>
      <div class="line"></div>
      <div class="table_element">

      </div>
      <div class="line"></div>
      <div class="table_element">

      </div>
      <div class="line"></div>
      <div class="table_element">

      </div>
      <div class="line"></div>
    </center>
  </body>
</html>
