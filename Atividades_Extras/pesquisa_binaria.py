def pesquisa_binaria(lista, valor):
    inicio = 0                                # pega o primeiro índice
    fim = len(lista) - 1                      # pega o último índice

    while inicio <= fim:
        meio = (inicio + fim) // 2            # calculamos para descobrir o item que está no meio da lista
        if lista[meio] == valor:
            return meio                       # se o item for igual o que procuramos, retornamos o índice dele
        elif lista[meio] < valor:
            inicio = meio + 1                 # se o valor for MENOR que o que queremos, ele descarta a primeira metade e olha na segunda metade
        else:
            fim = meio - 1                    # se o valor for MAIOR, ele olha a primeira metade e descarta a primeira
    return -1                                 # se o loop terminar sem retornar nada, é porque o valor não está na lista

numeros = [1, 3, 5, 7, 9, 11]                 # a lista tem que estar ordenada pra pesquisa binária funcionar
resultado = pesquisa_binaria(numeros, 7)

if resultado != -1:
    print(f"Posição do valor: {resultado}")   # ele imprime o índice onde o valor foi encontrado
else:
    print("O valor não foi encontrado")
