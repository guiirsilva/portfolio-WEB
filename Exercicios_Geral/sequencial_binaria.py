import time
import random

# cria a função pesquisa sequencial
def pesquisa_sequencial(lista, valor):
    comparacoes = 0
    for i, v in enumerate(lista):
        comparacoes += 1
        if v == valor:
            return i, comparacoes
    return -1, comparacoes

# cria a função pesquisa binária
def pesquisa_binaria(lista, valor):
    inicio, fim = 0, len(lista) - 1
    comparacoes = 0
    while inicio <= fim:
        meio = (inicio + fim) // 2
        comparacoes += 1
        if lista[meio] == valor:
            return meio, comparacoes
        elif lista[meio] < valor:
            inicio = meio + 1
        else:
            fim = meio - 1
    return -1, comparacoes

# cria uma lista aleatória e escolhe um valor aleatório
lista = sorted(random.sample(range(1000), 100))
valor = random.choice(lista)

# calcula o tempo que demorou a função pesquisa sequencial
inicio = time.time()
_, comp_seq = pesquisa_sequencial(lista, valor)
tempo_seq = time.time() - inicio

# calcula o tempo que demorou a função pesquisa binária
inicio = time.time()
_, comp_bin = pesquisa_binaria(lista, valor)
tempo_bin = time.time() - inicio

# printa o valor, o número de comparações e o tempo que levou cada função
print("Pesquisa Sequencial -> Valor:", valor, "Comparações:", comp_seq, "Tempo:", tempo_seq)
print("Pesquisa Binária -> Valor", valor, "Comparações:", comp_bin, "Tempo:", tempo_bin)
