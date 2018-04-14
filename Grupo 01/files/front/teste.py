import pandas as pd
import numpy as np
import os, sys


dirs = os.listdir("./")
cidades = []

for file in dirs:
	
	cidades.append(pd.read_csv(file, delimiter = ";"))

for i in cidades:
	print i