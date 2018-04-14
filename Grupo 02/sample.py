# coding: utf-8

def getIndex(word, array):
	for pair in array:
		if pair[0] == word:
			return pair[1]
	return -1

with open('Metadados.csv','r') as reading:
	data = reading.readlines()

types = []
heading = []

for li in range(1,len(data)):
	line = data[li]
	t = line.split(',')[1]
	index = getIndex(t, heading)
	if index == -1:
		l = len(heading)
		heading.append((t, l))
		index = l
		types.append([])

	types[index].append(line)

for ty in range(len(types)):
	types[ty].insert(0, data[0])
	with open("results/result%d.csv" % ty,'w') as exiting:
		for xx in types[ty]:
			exiting.write(xx)
