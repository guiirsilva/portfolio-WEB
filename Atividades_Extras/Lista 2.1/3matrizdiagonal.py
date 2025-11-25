def diagonal_principal(matriz):
    diagonal = []
    soma = 0

    for i in range(3):
        diagonal.append(matriz[i][i])
        soma += matriz[i][i]

    return diagonal, soma

matriz = [
    [1, 2, 3],
    [4, 5, 6],
    [7, 8, 9]
]

diag, total = diagonal_principal(matriz)
print("Diagonal principal:", diag)
print("Soma da diagonal:", total)
