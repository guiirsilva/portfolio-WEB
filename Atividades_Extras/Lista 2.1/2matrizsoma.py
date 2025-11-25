matriz = []

print("Digite os valores para a matriz 3x3:")

for i in range(3):
    linha = []
    for j in range(3):
        valor = int(input(f"Valor [{i}][{j}]: "))
        linha.append(valor)
    matriz.append(linha)

soma = 0
for linha in matriz:
    for num in linha:
        soma += num

print(f"\nA soma de todos os elementos é: {soma}")
