# Python Script to create a sample data file for the current month
# takes ../data/zaehler_kwh_sample.csv and copioes it to zaehler_kwh_<yyyymm>.csv where <yyyymm> is current year and month
# in the file it replaces the Column Datum 
# <day> = today, <day-1> = yesterday, <day-2> = before yesterday
import os, datetime

cwd = os.getcwd()
cdate = datetime.date.today()

if (cwd.endswith('scripts')):
    path = os.path.join(cwd,"..","public_html","data")
else:
    path = os.path.join(cwd,"public_html","data")
fname_sample = "zaehler_kwh_sample.csv"
fname_data = "zaehler_kwh_" + cdate.strftime("%Y%m") + ".csv"

f = open(os.path.join(path,fname_sample), "r")
f_neu = open(os.path.join(path,fname_data), "w")
for line in f:
    line = line.replace("<day-2>",cdate.strftime("%Y-%m-1"),1)
    line = line.replace("<day-1>",cdate.strftime("%Y-%m-2"),1)
    line = line.replace("<day>",cdate.strftime("%Y-%m-3"),1)
    # print(line)
    f_neu.write(line)
# end for

f.close()
f_neu.close()