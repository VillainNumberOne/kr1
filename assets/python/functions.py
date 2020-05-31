import numpy as np
import pandas as pd
import requests
import json
import io
import datetime


# In[166]:


def get_symbols_exmo():
    url = "https://api.exmo.com/v1.1/ticker"

    response = requests.request("POST", url)
    df = pd.DataFrame(eval(response.text))

    return np.sort(df.columns.values)


def get_symbols_binance():
    url = "https://api.binance.com/api/v1/exchangeInfo"
    response = requests.request("GET", url).text.replace('true', '1').replace('false', '0')
    symbols_start = response.find('\"symbols\":')
    response = response[symbols_start+10:-1]
    df = pd.DataFrame(eval(response))

    return np.sort(df.symbol.to_numpy())

def get_symbols(exchange):
    if exchange == 'EXMO':
        result = get_symbols_exmo()
    if exchange == 'Binance':
        result = get_symbols_binance()

    result_string = ""

    for i in range(len(result)):
        result_string += result[i] + ","

    return result_string[:-1]

def get_ohlc_exmo(datetime_from, datetime_to, resolution, symbol):

    # symbol = currency_b+"_"+currency_s

    url = "https://api.exmo.com/v1.1/candles_history?symbol="+symbol+"&resolution="+resolution+"&from="+str(datetime_from)+"&to="+str(datetime_to)

    payload = {}
    headers= {}

    try:
        response = requests.request("GET", url, headers=headers, data = payload).text.replace('{\"candles\":', '')[:-1]
        df = pd.DataFrame(eval(response))
    except NameError:
        response = requests.request("GET", url, headers=headers, data = payload).text.replace('{\"candles\":', '')[:-1]
        df = pd.DataFrame(eval(response))

    df = df.rename(columns={"t":"Date", "o":"Open", "c":"Close", "h":"High", "l":"Low", "v":"Volume"})
    df.insert(6, 'Adj Close', 0)

    # column = pd.DataFrame(df['Date'])
    #
    # for i in range(len(column)):
    #
    #     timestamp = datetime.datetime.fromtimestamp(column['Date'][i]/1000)
    #     column['Date'][i] = timestamp.strftime('%Y-%m-%d')
    #
    # df['Date'] = column


    # df['Open'] = df['Open'].apply(lambda x: round(x, 4))
    # df['High'] = df['High'].apply(lambda x: round(x, 4))
    # df['Low'] = df['Low'].apply(lambda x: round(x, 4))
    # df['Close'] = df['Close'].apply(lambda x: round(x, 4))

    return df[['Date', 'Open', 'High', 'Low', 'Close']]

def get_ohlc_binance(datetime_from, datetime_to, resolution, symbol):

    datetime_from = str(datetime_from) + "000"
    datetime_to = str(datetime_to) + "000"

    # url = "https://api.binance.com/api/v1/klines?symbol="+str(symbol)+"&interval="+str(resolution)+"&startTime="+str(datetime_from)+"&endTime="+str(datetime_to)
    url = "https://api.binance.com/api/v1/klines?symbol="+symbol+"&interval="+resolution+"&startTime="+datetime_from+"&endTime="+datetime_to

    # print(url)

    response = requests.request("GET", url)
    df = pd.DataFrame(eval(response.text))

    df = df.rename(columns={0:"Date", 1:"Open", 2:"High", 3:"Low", 4:"Close", 5:"Volume"})
    df = df[['Date', 'Open', 'High', 'Low', 'Close', 'Volume']]
    df = df.astype('float64')
    df['Date'] = df['Date'].astype(int)


    # df['Open'] = df['Open'].apply(lambda x: round(x, 4))
    # df['High'] = df['High'].apply(lambda x: round(x, 4))
    # df['Low'] = df['Low'].apply(lambda x: round(x, 4))
    # df['Close'] = df['Close'].apply(lambda x: round(x, 4))

    return df

def get_ohlc(exchange, datetime_from, datetime_to, resolution, symbol):
    if exchange == 'EXMO':
        result = get_ohlc_exmo(datetime_from, datetime_to, resolution, symbol)
    if exchange == 'Binance':
        result = get_ohlc_binance(datetime_from, datetime_to, resolution, symbol)

    return result

def df2csv(df):
    s = io.StringIO()
    df.to_csv(s, index=False)

    return s.getvalue()

def df2json(df):
    s = io.StringIO()
    df.to_json(s)

    return s.getvalue()

def df2ac(df):
    result_string = "["

    for i in range(len(df)):
        result_string += "{x: new Date("+str(df['Date'][i])+"), y: ["+str(df['Open'][i])+", "+str(df['High'][i])+", "+str(df['Low'][i])+", "+str(df['Close'][i])+"]},\n"

    return result_string[:-2]+"]"



# In[ ]:
