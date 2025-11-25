with open("numeros.txt", "r") as f:
    numeros = [float(linha.strip()) for linha in f]

soma = sum(numeros)
media = soma / len(numeros)

with open("resumo.txt", "w") as f:
    f.write(f"Soma: {soma}\n")
    f.write(f"Média: {media}\n")

print("Arquivo resumo.txt criado com sucesso!")
