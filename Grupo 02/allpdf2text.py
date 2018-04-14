# coding: utf-8
import os
for file in os.listdir(os.getcwd()):
    if file.endswith(".pdf"):
    	os.system('python3 pdftotext.py ' + file)
