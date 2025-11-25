import numpy as np

A = np.array([[2, 1],
              [1, -1]])

b = np.array([5, 1])

solucao = np.linalg.solve(A, b)

print("Solução (x, y):")
print(solucao)
