from functions import *
import sys

def main(argv):
    if argv[6] == 'csv':
        print(df2csv(get_ohlc(argv[1], argv[2], argv[3], argv[4], argv[5])))
    elif argv[6] == 'json':
        print(df2json(get_ohlc(argv[1], argv[2], argv[3], argv[4], argv[5])))
    else:
        print("ERROR")

    # print("ERROR")

if __name__ == "__main__":
   main(sys.argv)
