# coding: utf-8
# (C) Júlio Barreto
# Extract data from sentences pdfs

from tika import parser
import ujson, re, os

def values(filename):
	pdf_ = parser.from_file(filename)
	pdf = pdf_['content'].split('\n')
	pdf = pdf[32:-4]

	dic = {}
	dic['processo'] = re.findall("PROCESSO Nº: (.*?) - ", pdf[0])[0]

	with open("texts/" + dic['processo'] + '.txt','w') as res:
		res.write(pdf_['content'])

	dic['valores'] = []
	for i in pdf:
		try:
			index = i.index("R$")
		except ValueError:
			index = -1

		if index != -1:
			s = ""
			mark = 0
			boolean = False
			while mark < 2 and len(i) > index:
				if boolean:
					mark += 1
				if i[index] == ",":
					boolean = True
			
				if i[index] != " ":
					s += i[index]

				index += 1
			dic['valores'].append(s)

	return dic

for filename in os.listdir(os.getcwd()):
	if (filename.endswith('.pdf')):
		print(filename)
		dic = values(filename)
		with open("jsons/" + dic['processo'] + '.json','w') as res:
			ujson.dump(dic, res)
