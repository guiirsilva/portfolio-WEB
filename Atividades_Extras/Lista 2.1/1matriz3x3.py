matriz = []

print("Digite os valores para a matriz 3x3:")

for i in range(3):
    linha = []
    for j in range(3):
        valor = int(input(f"Valor para posição [{i}][{j}]: "))
        linha.append(valor)
    matriz.append(linha)

print("\nMatriz 3x3:")
for linha in matriz:
    print(" ".join(f"{num:3d}" for num in linha))
