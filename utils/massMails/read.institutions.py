
# -*- coding: UTF-8 -*-

#https://www.coursera.org/learn/python-databases/lecture/lvqrI/unicode-characters-and-strings

import re

if __name__ == '__main__':


    def getValueFromPattern(pattern, line):
        mMatcher = pattern.search(line)
        value = ""
        if mMatcher:
            value = mMatcher.group(1)
        return value

    def getDefaultIfEmpty(value):
        if value == "":
            return ""
        else:
            return value


    #"bibcode":"LUVOG","labelDE":"Vogelwarte, Sempach","name":"Schweizerische Vogelwarte Bibliothek","mail":"bibliothek@vogelwarte.ch"}

    #{"bibcode": "HFHS", "labelDE": "FHS St. Gallen", "name": "FHS St.Gallen, Bibliothek"}
    #{"bibcode": "HFHGE", "labelDE": "FHS St. Gallen - Gesundheit"}


    #nameWithMail = re.compile("\"name\":\"(.*?)\",\"mail\":\"(.*?)\"}",re.DOTALL | re.IGNORECASE | re.MULTILINE)
    #nameWithoutMail = re.compile("\"name\":\"(.*?)\"}",re.DOTALL | re.IGNORECASE | re.MULTILINE)

    #nameWithMail = re.compile("\"name\":\"(.*?)\",\"mail\":\"(.*?)\"}",re.DOTALL | re.IGNORECASE | re.MULTILINE)
    #nameWithoutMail = re.compile("\"name\":\"(.*?)\"}",re.DOTALL | re.IGNORECASE | re.MULTILINE)

    #withMail = re.compile("\"mail\":\"(.*?)\".*",re.DOTALL | re.IGNORECASE | re.MULTILINE)
    #withoutMail = re.compile("\"name\":\"(.*?)\"}",re.DOTALL | re.IGNORECASE | re.MULTILINE)

    #bibCode = re.compile("\"bibcode\":\"(.*?)\"", re.DOTALL | re.IGNORECASE | re.MULTILINE)


    pBibCode = re.compile("\"bibcode\":\"(.*?)\",\"labelDE\"",re.DOTALL | re.IGNORECASE | re.MULTILINE)
    pLabelDE = re.compile("labelDE\":\"(.*?)\"",re.DOTALL | re.IGNORECASE | re.MULTILINE)
    pName = re.compile("name\":\"(.*?)\"",re.DOTALL | re.IGNORECASE | re.MULTILINE)
    pMail = re.compile("mail\":\"(.*?)\"}",re.DOTALL | re.IGNORECASE | re.MULTILINE)
    #
    #"bibcode":
    out = open("institutions.bibcode.empty.csv","w")
    for line in open("/usr/local/swissbib/mfWorkflows/mails.for.silvia.json", "r"):

        bibcode = getValueFromPattern(pBibCode, line)
        if bibcode == "SGSKR":
            print("jj")
        labelDE = getValueFromPattern(pLabelDE, line)
        mail = getValueFromPattern(pMail, line)
        name = getValueFromPattern(pName, line)

        all = getDefaultIfEmpty(bibcode) + "###" + getDefaultIfEmpty(labelDE) + "###" + getDefaultIfEmpty(mail) + "###" + getDefaultIfEmpty(name)
        out.write(all + "\n")
        out.flush()



    out.close()