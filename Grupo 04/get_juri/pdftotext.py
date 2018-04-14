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

def main(pdfFile):
    # try:
    #     opts, args = getopt.getopt(pdfFile[1:], "ho", ["help", "output"])
    # except getopt.GetoptError as err:
    #     # print help information and exit:
    #     print( err )  # will print something like "option -a not recognized"
    #     exit("Use --help para entender o uso")
    opts = '-o'

    outputOnly = False
    helper = False

    # check if an option was used
    for i in range( len( opts ) ):
        if '-h' in opts[i] or '--help' in opts[i]:
            helper = True

        if '-o' in opts[i] or '--output' in opts[i]:
            outputOnly = True

    if helper or len( args ) is 0:
            usage()
            exit( 0 )

    #  = args[0]

    fullDoc = parseText( convertPdfToText( pdfFile ) )

    if outputOnly:
        print( fullDoc )
        return(fullDoc)
    else:
        output = open( sub( r'pdf$', 'txt', pdfFile ), "w" )
        output.write( fullDoc )
        output.close()

   