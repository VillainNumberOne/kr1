from functions import *
import sys

def main(argv):
    print(df2ac(get_ohlc(argv[1], argv[2], argv[3], argv[4], argv[5])))
    # print(argv)


if __name__ == "__main__":
   main(sys.argv)
