import numpy as np

A = np.random.randint(1, 11, (2, 2))
B = np.random.randint(1, 11, (2, 2))

print("Matriz A:")
print(A)

print("\nMatriz B:")
print(B)

# Soma
print("\nSoma (A + B):")
print(A + B)

# Multiplicação matricial
print("\nMultiplicação matricial (A @ B):")
print(A @ B)

# Transpostas
print("\nTransposta de A:")
print(A.T)

print("\nTransposta de B:")
print(B.T)
