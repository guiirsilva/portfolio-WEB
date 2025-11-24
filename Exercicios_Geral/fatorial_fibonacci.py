# cria a função fatorial
def fatorial(n):
    if n == 0:
        return 1
    return n * fatorial(n - 1)

# cria a função fibonacci
def fibonacci(n):
    if n <= 1:
        return n
    return fibonacci(n - 1) + fibonacci(n - 2)

# cria a função soma lista
def soma_lista(lista):
    if not lista:
        return 0
    return lista[0] + soma_lista(lista[1:])

# faz o teste de cada função
print("Fatorial(5):", fatorial(5))
print("Fibonacci(8):", fibonacci(8))
print("Soma da lista [1,2,3,4,5]:", soma_lista([1,2,3,4,5]))