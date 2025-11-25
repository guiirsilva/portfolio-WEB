import numpy as np

matriz = np.array([
    [2, 5, 3],
    [1, -2, -1],
    [3, 6, 4]
])

det = np.linalg.det(matriz)

print("Matriz:")
print(matriz)

print("\nDeterminante:")
print(det)
