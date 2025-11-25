import numpy as np
import pandas as pd

dados = pd.read_csv("dados.csv")

x = dados["x"].values
y = dados["y"].values

n = len(x)
a = (n*np.sum(x*y) - np.sum(x)*np.sum(y)) / (n*np.sum(x**2) - (np.sum(x))**2)
b = (np.sum(y) - a*np.sum(x)) / n

print("Coeficiente angular (a):", a)
print("Coeficiente linear (b):", b)
