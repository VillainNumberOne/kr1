function prepare_data(t_ohlc, apply, period){
  var apply_array = [];

  if (apply != 4){
    for (var i = 0; i < t_ohlc.length; i++) {
      var current_data = {x: t_ohlc[i]['x'], y: t_ohlc[i]['y'][apply]}
      apply_array.push(current_data);
    }
  }
  else {
    for (var i = 0; i < t_ohlc.length; i++) {
      var current_data = {x: t_ohlc[i]['x'], y: (t_ohlc[i]['y'][0] + t_ohlc[i]['y'][1] + t_ohlc[i]['y'][2] + t_ohlc[i]['y'][3])/4}
      apply_array.push(current_data);
    }
  }

  return apply_array;
}

function SMA(t_ohlc, apply, period){
  var apply_array = prepare_data(t_ohlc, apply, period);

  var result_array = [];
  for (var i = 0; i < apply_array.length; i++) {
    var current_window = 0;
    if (i+1 < period){
      current_window = i+1;
    }
    else {
      current_window = period;
    }

    var sum = 0;
    for (var j = i - current_window + 1; j <= i; j++) {
      sum += apply_array[j]['y'];
    }

    var average_value = sum / current_window;
    var average_value1 = average_value.toFixed(9);

    var current_data = {x: apply_array[i]['x'], y: average_value1}
    result_array.push(current_data);
  }

  return result_array;
}

function EMA(t_ohlc, apply, period){
  var smoothing = 2;
  var apply_array = prepare_data(t_ohlc, apply, period);

  var result_array = [];
  for (var i = 0; i < apply_array.length; i++) {

    var ema_today = 0;
    if (i > 0){
      ema_today = (apply_array[i]['y'] - result_array[i-1]['y']) * (smoothing/(1+period)) + result_array[i-1]['y'];
    }
    else {
      ema_today = apply_array[i]['y'];
    }

    var current_data = {x: apply_array[i]['x'], y: ema_today};
    result_array.push(current_data);
  }

  return result_array;
}

function WMA(t_ohlc, apply, period){
  var apply_array = prepare_data(t_ohlc, apply, period);

  var result_array = [];
  for (var i = 0; i < apply_array.length; i++) {
    var current_window = 0;
    if (i+1 < period){
      current_window = i+1;
    }
    else {
      current_window = period;
    }

    var sum = 0;
    var weight = 1;
    for (var j = i - current_window + 1; j <= i; j++) {
      sum += apply_array[j]['y'] * weight;
      weight++;
    }

    var average_value = sum / (((1+current_window)/2)*current_window);
    var average_value1 = average_value.toFixed(9);

    var current_data = {x: apply_array[i]['x'], y: average_value1}
    result_array.push(current_data);
  }

  return result_array;
}
