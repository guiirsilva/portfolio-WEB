import time
import random

# cria a função de ordenação por seleção 
def ordenacao_selecao(lista):
    for i in range(len(lista)):
        menor = i
        for j in range(i+1, len(lista)):
            if lista[j] < lista[menor]:
                menor = j
        lista[i], lista[menor] = lista[menor], lista[i]
    return lista

# cria a função de quicksort
def quicksort(lista):
    if len(lista) <= 1:
        return lista
    pivo = lista[len(lista)//2]
    esquerda = [x for x in lista if x < pivo]
    meio = [x for x in lista if x == pivo]
    direita = [x for x in lista if x > pivo]
    return quicksort(esquerda) + meio + quicksort(direita)

# cria uma lista aleatória
lista = random.sample(range(10000), 1000)

# calcula o tempo de execução da função ordenacao_selecao
inicio = time.time()
ordenacao_selecao(lista[:])
tempo_sel = time.time() - inicio

# calcula o tempo de execução da função quicksort
inicio = time.time()
quicksort(lista[:])
tempo_quick = time.time() - inicio

# calcula o tempo de execução com sorted
inicio = time.time()
sorted(lista)
tempo_sorted = time.time() - inicio

# printa o tempo de execução de cada um
print("Seleção:", tempo_sel)
print("Quicksort:", tempo_quick)
print("sorted():", tempo_sorted)