# -*- coding: iso-8859-15 -*-

import pandas as pd
import yang_test as yt

metadata = pd.read_csv('agoraVai.csv', encoding ="utf8")


################Entradas para o programa
num = 2
assunto = "Descontos Indevidos"
juiz = 'MAGNUS AUGUSTO COSTA DELGADO'
assunto = []
#juiz = []


##########Filtrar dados de acordo com os parametros de entrada

rows = metadata[['cod','assunto','procedencia','juiz']]
if juiz:
    rows = rows.loc[metadata['juiz'] == juiz]
if assunto:
    rows = rows.loc[metadata['assunto'] == assunto]
    
proc1 = rows.loc[metadata['procedencia']=='P']

proc2 = rows.loc[metadata['procedencia']=='I']

proc1 = proc1.reset_index()
proc2 = proc2.reset_index()




############Procurar pela quantidade requisitada de documentos com jurisprudencia para os tipos P e I

print("\nProcedencia P\n\n")

j = 0
for i in range(0,len(proc1)):
    test = yt.get_paragraph('../sentenca/'+str(proc1.loc[i,'cod'])+'.pdf','jurisprudência')
    if test!='not':
	flag = True
        print ("Codigo: "+str(proc1.loc[i,'cod']))
        print ("Juiz: "+str(proc1.loc[i,'juiz']))
        if assunto:
            print ("Assunto: "+assunto)
        print (test)
        print ("\n")
        j = j+1
        
    if j==num:
        break
        
print("\nProcedencia I\n\n")        

j = 0
for i in range(0,len(proc2)):
    test = yt.get_paragraph('../sentenca/'+str(proc2.loc[i,'cod'])+'.pdf','jurisprudência')
    if test!='not':
        print ("Codigo: "+str(proc2.loc[i,'cod']))
        print ("Juiz: "+str(proc2.loc[i,'juiz']))
        if assunto:
            print ("Assunto: "+assunto)
        print (test)
        print ("\n")
        
        j = j+1
    if j==num:
        break
