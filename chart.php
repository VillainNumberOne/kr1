<?php
include 'assets/php/functions.php';
update_session();

$exchange = "Binance";

$ok = 1;

if (empty($_GET['ex'])) $ok = 0;
else $exchange = $_GET['ex'];

if ($exchange == 'EXMO'){
  $resolution = "D";
  $pair = "BTC_USD";
}
if ($exchange == 'Binance'){
  $resolution = "8h";
  $pair = "BTCUSDT";
}

$datetime_from = "2020-05-03T00:00";
$datetime_to = "2020-05-23T00:00";

if (empty($_GET['date_from'])) $ok = 0;
else $datetime_from = $_GET['date_from'];

if (empty($_GET['date_to'])) $ok = 0;
else $datetime_to = $_GET['date_to'];

if (empty($_GET['resolution'])) $ok = 0;
else $resolution = $_GET['resolution'];

if (empty($_GET['pair'])) $ok = 0;
else $pair = $_GET['pair'];

if (empty($_GET['indicators'])) $indicators=0;
else $indicators=1;


$symbols = get_symbols($exchange);
$intervals = get_intervals($exchange);

//Проверка на случай, если такие данные запрашивались в прошлый раз

$args = "?ex=$exchange&format=ac&date_from=$datetime_from&date_to=$datetime_to&resolution=$resolution&pair=$pair";

if (!empty($_SESSION['last_chart_args'])){
  if ($args != $_SESSION['last_chart_args']){
    $_SESSION['last_chart_args'] = $args;
    $ohlc_ac_data = get_ohlc_ac($exchange, strtotime($datetime_from), strtotime($datetime_to), $resolution, $pair);
    $_SESSION['last_ohlc_data'] = $ohlc_ac_data;
    $_SESSION['last_exchange'] = $exchange;
    $_SESSION['last_datetime_from'] = $datetime_from;
    $_SESSION['last_datetime_to'] = $datetime_to;
    $_SESSION['last_resolution'] = $resolution;
    $_SESSION['last_symbol'] = $pair;
  }
  else {
    $ohlc_ac_data = $_SESSION['last_ohlc_data'];
  }
}
else {
  $_SESSION['last_chart_args'] = $args;
  $ohlc_ac_data = get_ohlc_ac($exchange, strtotime($datetime_from), strtotime($datetime_to), $resolution, $pair);
  $_SESSION['last_ohlc_data'] = $ohlc_ac_data;
}

$indicators_flag = 0;
if (!empty($_SESSION['indicators'])){
  $indicators_flag = 1;
  $indicators = $_SESSION['indicators'];
  $colors = get_colors($indicators);
  $indicators_js = get_indicators($indicators);
}
else {
  $colors = "['#97fa2e', '#66DA26', '#546E7A', '#E91E63', '#FF9800']";
  $indicators = [];
  $indicators_js = "";
}

?>


<!DOCTYPE html>
<html lang="en">
  <head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <meta http-equiv="X-UA-Compatible" content="ie=edge" />
    <title>Агрегатор исторических данным по криптовалютам</title>

    <link href="https://fonts.googleapis.com/css2?family=Montserrat:ital,wght@0,400;0,500;0,600;0,900;1,400;1,500&family=Open+Sans&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="assets/styles/styles.css">
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/promise-polyfill@8/dist/polyfill.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/eligrey-classlist-js-polyfill@1.2.20171210/classList.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/findindex_polyfill_mdn"></script>
    <script src="https://cdn.jsdelivr.net/npm/apexcharts"></script>
    <script src="/assets/js/indicators.js"></script>
    <script src="/assets/js/ajaxrequest.js"></script>


    <script src="../../assets/ohlc.js"></script>
  </head>
  <header>
    <center>
      <div class="container_header" style="margin-top: 16px;">
        <a href="chart.php?ex=Binance" <?php if($exchange == 'Binance') echo "style=\"color: #1f80ff\""; ?> class="header_link">Binance</a>
        <a href="chart.php?ex=EXMO" <?php if($exchange == 'EXMO') echo "style=\"color: #1f80ff\""; ?> class="header_link">EXMO</a>
        <!-- <a href="chart.php?ex=Kraken" class="header_link">Kraken</a> -->
      </div>
    </center>
  </header>

  <body style="background-color: #ededed;">

    <form action="chart.php" method="get">
    <center>
      <div style=" margin-top: -30px;">
        <font class="h2" color="#2f3c4a"><b>Исторические данные. Источник: <?=$exchange?></b></font>
      </div>

        <input type="hidden" name=ex value="<?=$exchange?>">
        <input type="hidden" name="format" value="ac">
        <div class="table_element" style="height: 75px; margin-top: 20px;">


          <div class="table_item_1" style="margin-top: -10px;">
            от:
            <input type="datetime-local" name="date_from" value="<?=$datetime_from?>" required>
          </div>

          <div class="table_item_1" style="margin-top: -10px;">
            до:
            <input type="datetime-local" name="date_to" value="<?=$datetime_to?>" required>
          </div>

          <div class="table_item_1" style="margin-top: -10px;">
            интервал:
            <select class="selector" name="resolution">
              <?php
              echo '<option value = "'.$resolution.'">По умолчанию '.$resolution.'</option>';
              for ($i = 0; $i < count($intervals); $i++) {
                echo "<option>".$intervals[$i]."</option>";
              }
              ?>
            </select>
          </div>

          <div class="table_item_1" style="margin-top: -10px;">
            валютная пара:
            <select class="selector" name="pair">
              <?php
              echo '<option value = "'.$pair.'">По умолчанию '.$pair.'</option>';
              for ($i = 0; $i < count($symbols); $i++) {
                echo "<option>".$symbols[$i]."</option>";
              }
              ?>
            </select>

          </div>

          <div class="table_item_1" style="margin-top: 5px;">
            <input type="submit" name="" value="Обновить">
          </div>
        </div>
      </form>

      <div id="chart" style="background-color: white;"></div>

      <!-- <div class="table_element" style="margin-top: -65px;">
        <div class="table_item_1">
          <input type="submit" name="" value="Обновить">
        </div>
      </div> -->
      <div style="margin-top: -35px;">
        экспорт: <a href="export.php">csv</a>, <a href="export.php?format=json">json</a>
      </div>

      <br>
      <font class="h2" color="#2f3c4a"><b>Индикаторы</b></font>

      <form class="" action="#" method="post">

       <div class="table_element" style="margin-top: 20px;">
         <div class="table_item_1">
           Индикатор:
           <select id="indicator" class="selector" name="indicator">
             <option selected>SMA</option>
             <option>EMA</option>
             <option>WMA</option>
           </select>
         </div>

         <div class="table_item_1">
           применить для:
           <select id="apply" class="selector" name="apply">
             <option value="Open">Open</option>
             <option value="High">High</option>
             <option value="Low">Low</option>
             <option value="Close" selected>Close</option>
             <option value="Avg">Avg</option>
           </select>
         </div>


         <div class="table_item_1">
           период:
           <input id="period" type="number" name="period1" value="30" required>
         </div>


         <div class="table_item_1">
           цвет:
           <select id="color" class="selector" name="color">
             <option value="#d92727">Красный</option>
             <option value="#ffed29">Желтый</option>
             <option value="#00db71">Зеленый</option>
             <option value="#1f80ff" selected>Синий</option>
             <option value="#333333">Черный</option>
           </select>
         </div>

       </form>

         <div class="table_item_1">
           <button class="btn1" style="margin-top: 3.5px;" type="button">Построить</button>
         </div>

       </div>
       <a onclick="delete_indicators()" style="margin-bottom: 50px;">удалить все индикаторы</a>
       <br><br><br>

    </center>

    <script>
        var t_ohlc = <?=$ohlc_ac_data?>;

        var options = {
          colors: <?=$colors?>,
          series: [
            <?php if ($indicators_flag) echo $indicators_js; ?>

            {
              name: '<?=$pair?>',
              type: 'candlestick',
              data: t_ohlc,
            },

          ],
          chart: {
          height: 500,
          type: 'line',
          toolbar: {
            show: true,
            offsetX: 0,
            offsetY: 0,
            tools: {
              download: false,
              selection: true,
              zoom: true,
              zoomin: true,
              zoomout: true,
              pan: true,
              reset: false,
              customIcons: []
            },
            autoSelected: 'zoom'
          },
          animations: {
               enabled: true,
               easing: 'easeinout',
               speed: 300,
           },
           zoom: {
              enabled: true,
              type: 'xy',
              autoScaleYaxis: true,
              zoomedArea: {
                fill: {
                  color: '#90CAF9',
                  opacity: 0.4
                },
                stroke: {
                  color: '#0D47A1',
                  opacity: 0.4,
                  width: 1
                }
              }
          },
          events: {
            markerClick: function(event, chartContext, { seriesIndex, dataPointIndex, config}) {
              return false;
            },
            legendClick: function(chartContext, seriesIndex, config) {
              return false;
            }
          }
        },
        legend: {
          onItemClick: {
               toggleDataSeries: false
           },
           fontSize: '18px',
        },
        yaxis:{
          decimalsInFloat: 7
        },

        title: {
          text: 'Валютная пара <?php echo "$pair, $resolution"?>',
          align: 'left'
        },
        stroke: {
          width: [3, 1]
        },

        tooltip: {
          shared: true,
          custom: [function({seriesIndex, dataPointIndex, w}) {
            return w.globals.series[seriesIndex][dataPointIndex]
          }, function({ seriesIndex, dataPointIndex, w }) {
            var o = w.globals.seriesCandleO[seriesIndex][dataPointIndex]
            var h = w.globals.seriesCandleH[seriesIndex][dataPointIndex]
            var l = w.globals.seriesCandleL[seriesIndex][dataPointIndex]
            var c = w.globals.seriesCandleC[seriesIndex][dataPointIndex]
            return (
              '<div class="apexcharts-tooltip-candlestick">' +
              '<div>Open: <span class="value">' +
              o +
              '</span></div>' +
              '<div>High: <span class="value">' +
              h +
              '</span></div>' +
              '<div>Low: <span class="value">' +
              l +
              '</span></div>' +
              '<div>Close: <span class="value">' +
              c +
              '</span></div>' +
              '</div>'
            )
          }]
        },
        plotOptions: {
            candlestick: {
              colors: {
                upward: '#11a68a',
                downward: '#ad134e'
              },
              fill: {
                 opacity: 0
              }
            }
        },

        xaxis: {
          type: 'datetime'
        }
        };
        document.getElementById('chart').innerHTML = '';
        var chart = new ApexCharts(
          document.querySelector("#chart"),options
        );
        chart.render();

    </script>


  </body>
</html>
