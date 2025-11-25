with open("dados.txt", "r", encoding="utf-8") as arquivo:
    linhas = arquivo.readlines()

quant_linhas = len(linhas)
quant_palavras = sum(len(linha.split()) for linha in linhas)

print("Total de linhas:", quant_linhas)
print("Total de palavras:", quant_palavras)
