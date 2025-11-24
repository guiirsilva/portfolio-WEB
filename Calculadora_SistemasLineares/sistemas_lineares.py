def ler_sistema():
    while True:
        try:
            n = int(input("Digite o tamanho do sistema (máximo 10): "))
            if 1 <= n <= 10:
                break
            else:
                print("Tamanho inválido. Digite um valor entre 1 e 10.")
        except ValueError:
            print("Entrada inválida. Digite um número inteiro.")

    matriz = []
    print("\nDigite os coeficientes e termos independentes:")
    for i in range(n):
        linha = []
        for j in range(n):
            a = float(input(f"A[{i+1}][{j+1}]: "))
            linha.append(a)
        b = float(input(f"B[{i+1}]: "))
        linha.append(b)
        matriz.append(linha)

    return matriz, n 

def imprimir_sistema(matriz, n, titulo):
    print(f"\n{titulo}:")
    for i in range(n):
        linha = " + ".join([f"{matriz[i][j]:.2f}*x{j+1}" for j in range(n)])
        linha += f" = {matriz[i][n]:.2f}" 
        print(linha)

def escalonar(matriz, n):
    for i in range(n):
        if matriz[i][i] == 0:
            for k in range(i + 1, n):
                if matriz[k][i] != 0:
                    matriz[i], matriz[k] = matriz[k], matriz[i]
                    break

        if matriz[i][i] == 0:
            continue

        for j in range(i + 1, n):
            fator = matriz[j][i] / matriz[i][i]
            for k in range(i, n + 1):
                matriz[j][k] -= fator * matriz[i][k]
    return matriz 

def resolver(matriz, n):
    x = [0] * n  
    for i in range(n - 1, -1, -1):
        if matriz[i][i] == 0:
            if abs(matriz[i][n]) > 1e-6:
                return None
            else:
                return "infinita"
        soma = sum(matriz[i][j] * x[j] for j in range(i + 1, n))
        x[i] = (matriz[i][n] - soma) / matriz[i][i] 
    return x 

def main():
    matriz, n = ler_sistema() 
    imprimir_sistema(matriz, n, "Sistema original") 

    matriz_escalonada = [linha[:] for linha in matriz]
    matriz_escalonada = escalonar(matriz_escalonada, n)
    imprimir_sistema(matriz_escalonada, n, "Sistema escalonado")

    solucao = resolver(matriz_escalonada, n)
    if solucao is None:
        print("\n O sistema é impossível (sem solução).")
    elif solucao == "infinita":
        print("\n O sistema possui infinitas soluções.")
    else:
        print("\n Solução do sistema:")
        for i in range(n):
            print(f"x{i+1} = {solucao[i]:.4f}")

if __name__ == "__main__":
    main()