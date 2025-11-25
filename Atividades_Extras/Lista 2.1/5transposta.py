matriz = []
print("Digite os valores da matriz 2x3:")

for i in range(2):
    linha = []
    for j in range(3):
        valor = int(input(f"Valor [{i}][{j}]: "))
        linha.append(valor)
    matriz.append(linha)

transposta = []
for j in range(3):
    nova_linha = []
    for i in range(2):
        nova_linha.append(matriz[i][j])
    transposta.append(nova_linha)

print("\nMatriz original (2x3):")
for linha in matriz:
    print(linha)

print("\nMatriz transposta (3x2):")
for linha in transposta:
    print(linha)
