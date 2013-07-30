import json
import os
import sys
from pprint import pprint
from collections import OrderedDict
from collections import defaultdict

path = os.path.dirname(os.path.realpath(__file__));
fullpath = path + '/queryinfo.json';


def ExtractDate(datevalue):
    dateinfo, time =  ((l) for l in datevalue.split(' '));
    year, month, date = (int(x) for x in dateinfo.split('-'))
    return month;

def ReadData():
    json_data = [];
    with open(fullpath) as f:
        for line in f:                                                                                                                                        
            json_data.append(json.loads(line));
    f.close();
    word_dict = defaultdict(list);
    for item in json_data:
        monthValue = ExtractDate(item['date']);
        temp_str = '';
        temp_str=str(monthValue) + ','+ str(item['value']);
        word_dict[item['name']].append(temp_str);
    return word_dict;


combined_dict = defaultdict(list);
def CombineValues(values_dict):
    for key,values in values_dict.iteritems():
        month_array=defaultdict(int);
        temp=0;
        for morevalues in values:
            month,actualValue = (int(x) for x in morevalues.split(','));
            month_array[month]+=actualValue - temp;
            temp = actualValue;
        for keymonth,valuemonth in month_array.iteritems():
            str_temp = str(keymonth)+ ","+ str(valuemonth);
            combined_dict[key].append(str_temp);
            str_temp='';
    for keydict,valuedict in combined_dict.iteritems():
        print "%s: %s" % (keydict, valuedict);

            
def main():
   try:
        values_dict = ReadData();
        CombineValues(values_dict);
        
   except IOError as e:
        print "I/O error({0}): {1}".format(e.errno, e.strerror);
    
if __name__ == "__main__":
    sys.exit(main());
