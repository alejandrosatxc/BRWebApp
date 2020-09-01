import sys
import json
import re

debug = open("pythonout.txt", "w")
s = sys.argv[3]
debug.write(s)
#f.close()

worddir = sys.argv[1]                           #Directory contianing xml files that make up a .docx
varlist = json.loads(sys.argv[2])               #List of variables in json form turned into a dictonary
xmlfiles = sys.argv[3].strip('][').split(',')   #List of xmlfiles in .docx

debug.write(str(xmlfiles))
debug.write(str(varlist))
for x in varlist:
    s = x + ' ' + str(type(x)) + '\n'
    debug.write(s)

for xml in xmlfiles:
    path = worddir + xml                        #Create full path of xmlfile
    xmlfile = open(path, "r+")                  #Open file for reading and writing
    contents = xmlfile.read()                   #Get contents of file
    debug.write(str(contents))
    for key, value in varlist.items():          #iterate through every key, value pair in the varlist
        s = '{' + key + '}'                     #format the key to match strings in the .docx
        s = re.escape(s)                        #Escape special characters for regex substitution

        #might have to program in handling of multiple entries, since it's just
        #the headers and footers we are looking through, we will ignore this for now
        if type(value) is dict:                 #check if value is a dict
            continue                            #TODO skip for now

        debug = 'key=' + key + 'value=' + value + '\n\n'
        debug.write(debug)
        debug = '\n\nkey=' + key + str(type(s)) + '\nvalue=' + value + str(type(value)) + '\ncontents=' + contents + str(type(contents)) + '\n'
        debug.write(debug)
        contents = re.sub(s, value, contents)   #Substitute variables in xml files and update contents
    xmlfile.seek(0)         #seek to beginning of file
    xmlfile.write(contents) #overwrite contents
    xmlfile.truncate()      #truncate idk
    xmlfile.close()         #close file

debug.close()
