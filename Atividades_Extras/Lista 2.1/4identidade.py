def identidade(n=4):
    return [[1 if i == j else 0 for j in range(n)] for i in range(n)]

mat = identidade(4)

print("Matriz identidade 4x4:")
for linha in mat:
    print(" ".join(f"{x:1d}" for x in linha))
