import os
text = 'risco de vida'
for file in os.listdir(os.getcwd()):
	if file.endswith(".txt"):
		f = open(file)
		for line in f:
			if(text in line):
				os.system("mkdir output")
				o = open('output/caso1.txt', 'a')
				o.write(text + ' ' + file + '\n')
				o.close()
		f.close()
