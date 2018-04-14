
import operator
import re
import sys
import os
import csv

import pandas as pd

from ast import literal_eval

def get_paragraph(file_name, regular_exp='jurisprud'):

	os.system('pdf2txt.py -o text.txt '+str(file_name))

	juri_file = open('text.txt','r')
	juri_file = juri_file.read()

	juri_file = repr(juri_file)

	juri_file = juri_file.replace("\\n\\x0c","")

	juri_file = literal_eval(juri_file)

	paragraphs = juri_file.split(("\n\n"))				
	#print (juri_file)

	#print (paragraphs)

	for paragraph in paragraphs:
		match = re.search(regular_exp,paragraph)
		if match:
			#print (match.group(0))
			return paragraph

	return 'not'


#get_paragraph('0000010-86.2017.4.05.8402.pdf')


