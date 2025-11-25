import numpy as np

v1 = np.array(list(map(float, input("Digite os valores do vetor 1 separados por espaço: ").split())))
v2 = np.array(list(map(float, input("Digite os valores do vetor 2 separados por espaço: ").split())))

produto_escalar = np.dot(v1, v2)

norma_v1 = np.linalg.norm(v1)
norma_v2 = np.linalg.norm(v2)

print("\nProduto escalar:", produto_escalar)
print("Norma do vetor 1:", norma_v1)
print("Norma do vetor 2:", norma_v2)
