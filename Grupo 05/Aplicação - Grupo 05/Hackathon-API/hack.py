#!bin/python
# -*- coding: utf-8 -*-import os


######################################################
# OBS: Para instalar as dependências, use:
#      pip install pdfminer.six
######################################################

from io import StringIO
from pdfminer.converter import TextConverter
from pdfminer.layout import LAParams
from pdfminer.pdfinterp import PDFResourceManager, PDFPageInterpreter
from pdfminer.pdfpage import PDFPage
from re import sub
from sys import argv, exit
import getopt, os
import glob
from flask import Flask, jsonify, request
from flask_restful import Api, Resource
from flask_cors import CORS

j = open('lista2.txt', 'w')

def convertPdfToText(path):
    rsrcmgr = PDFResourceManager()
    retstr = StringIO()
    codec = 'utf-8'
    laparams = LAParams()
    device = TextConverter(rsrcmgr, retstr, codec=codec, laparams=laparams)
    fp = open(path, 'rb')
    interpreter = PDFPageInterpreter(rsrcmgr, device)
    password = ""
    maxpages = 0
    caching = True
    pagenos=set()

    for page in PDFPage.get_pages(fp, pagenos, maxpages=maxpages, password=password,caching=caching, check_extractable=True):
        interpreter.process_page(page)

    text = retstr.getvalue()

    fp.close()
    device.close()
    retstr.close()
    return text

def parseText( text ):
    parsed = sub( r'[ \t\r\f\v]+', ' ', text )      # remove múltiplos caracteres não imprimíveis
    parsed = sub( r'\n[ \t\r\f\v]', '', parsed )    # remove espaços no início da linha
    parsed = sub( r'\n+([a-z])', '\1', parsed )     # remove novas linhas dentro de um parágrafo
    parsed = sub( r'\n{2,}', '\n', parsed )         # remove múltiplas novas linhas
    return parsed

def usage():
    print( "Script para converter um PDF em um texto (ou arquivo de texto)")
    print( "Uso:\n\t" + os.path.basename(argv[0]) + " [-o] [-h] ARQUIVO" )
    print( "Argumentos opcionais:" )
    print( "\t-o, --output\tEspecifica que o texto será enviado para STDOUT" )
    print( "\t-h, --help\tMostra essa mensagem e sai" )

try:
    opts, args = getopt.getopt(argv[1:], "ho", ["help", "output"])
except getopt.GetoptError as err:
    # print help information and exit:
    print( err )  # will print something like "option -a not recognized"
    exit("Use --help para entender o uso")

outputOnly = False
helper = False

# check if an option was used
for i in range( len( opts ) ):
    if '-h' in opts[i] or '--help' in opts[i]:
        helper = True

    if '-o' in opts[i] or '--output' in opts[i]:
        outputOnly = True

# if helper or len( args ) is 0:
#         usage()
#         exit( 0 )

# pdfFile = args[0]
# fullDoc= [""]
# os.chdir("pro")
# arqvos=""
# aux = ""
# for file in glob.glob("*.pdf"):
#     print(file)
#     fullDoc += parseText( convertPdfToText( file ) )
#
# j.close()
# texto = arq.readlines()
# tx=[]
# for linha in texto :
#     valores = linha.split('\n')
#     tx+=valores
#
# print(fullDoc)

# for aux2 in tx:
#     if(aux2 != " "):
#         fullDoc += parseText( convertPdfToText( aux2 ) )

app = Flask(__name__)

@app.after_request
def after_request(response):
  response.headers.add('Access-Control-Allow-Origin', 'http://localhost:4200')
  response.headers.add('Access-Control-Allow-Headers', 'Content-Type,Authorization')
  response.headers.add('Access-Control-Allow-Methods', 'GET,PUT,POST,DELETE,OPTIONS')
  response.headers.add('Access-Control-Allow-Credentials', 'true')
  return response

@app.route('/')
def index():
    pdfFile="pro/0809647-68.2016.4.05.8400 Sentença.pdf"
    fullDoc = parseText( convertPdfToText(pdfFile) )

    if outputOnly:
        output = open( sub( r'pdf$', 'txt', pdfFile ), "w" )
        output.write( fullDoc )
        arq = open(sub( r'pdf$', 'txt', pdfFile ), 'r')
        print(output)
        fullText=""
        i=""
        fullText = arq
        aux3=0
        for n in fullText:
            i = n.split()
            for i2 in i:
                if i2 == 'EXCELENTÍSSIMO(A)':
                   print("confirmado sua entrada no processo")
                   return ("confirmado sua entrada no processo")
                   break
                if i2 == 'defiro' or i2=='procedente':
                    print("O juiz concordou com o pedido da parte autora.")
                    return ("O juiz concordou com o pedido da parte autora.")
                    break
                if i2 == 'indefiro' or i2=='improcedente':
                    print("O juiz não concordou com o pedido da parte autora.")
                    return ("O juiz não concordou com o pedido da parte autora.")
                    break
                # if i2 =='processo' or i2=='partes':
                #     print("Aguarde! seu processo esta com juiz.")
                #     return ("Aguarde! seu processo esta com juiz.")
                #     break
                if i2 =='Extinto' or i2=='extinção':
                    print("O processo chegou ao fim sem que os pedidos da parte autora fossem analisados")
                    return ("O processo chegou ao fim sem que os pedidos da parte autora fossem analisados")


                # if i2 == 'para' and aux3 ==1:
                #     print("confirmado sua entrada no processo")
                # else:
                #     aux3=0

        output.close()
        # texto = fullDoc.readlines()
        # tx=[]
        # count2=0
        # for linha in texto :
        #     valores = linha.split()
        #     count2+=1
        #     tx+=valores
        #
        #     # break
        # # print(tx)
        #
        # count=0
        #
        # for n in tx :
        #     if n == 'ação':
        #     	print(n)
        #
        #
        # arq.close()
    else:
        # for n in fullDoc :
        #     if n == 'EXCELENTÍSSIMO(A)':
        #         print("achoou")

        output = open( sub( r'pdf$', 'txt', pdfFile ), "w" )
        output.write( fullDoc )
        arq = open(sub( r'pdf$', 'txt', pdfFile ), 'r')
        print(output)
        fullText=""
        i=""
        fullText = arq
        aux3=0
        for n in fullText:
            i = n.split()
            for i2 in i:
                if i2 == 'EXCELENTÍSSIMO(A)':
                   print("confirmado sua entrada no processo")
                   return ("confirmado sua entrada no processo")
                   break
                if i2 == 'defiro' or i2=='procedente':
                    print("O juiz concordou com o pedido da parte autora.")
                    return ("O juiz concordou com o pedido da parte autora.")
                    break
                if i2 == 'indefiro' or i2=='improcedente':
                    print("O juiz não concordou com o pedido da parte autora.")
                    return ("O juiz não concordou com o pedido da parte autora.")
                    break
                # if i2 =='processo' or i2=='partes':
                #     print("Aguarde! seu processo esta com juiz.")
                #     return ("Aguarde! seu processo esta com juiz.")
                #     break
                if i2 =='Extinto' or i2=='extinção':
                    print("O processo chegou ao fim sem que os pedidos da parte autora fossem analisados")
                    return ("O processo chegou ao fim sem que os pedidos da parte autora fossem analisados")


                # if i2 == 'para' and aux3 ==1:
                #     print("confirmado sua entrada no processo")
                # else:
                #     aux3=0

        output.close()
        j.close()


@app.route('/Mostrar')
def get():

    return 'Seja Bem-Vindo a CIDJUS API'

# @app.route()
# # def get():
# #     # print(self.numeroPeticao)
#


if __name__ == "__main__":
    app.run(host='0.0.0.0', port=8080, debug=True)
