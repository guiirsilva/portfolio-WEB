import csv

with open('tabela.csv', newline='', encoding='utf-8') as f_in:
    leitor = csv.DictReader(f_in)

    with open('aprovados.csv', 'w', newline='', encoding='utf-8') as f_out:
        campos = ['id', 'nome', 'nota']
        escritor = csv.DictWriter(f_out, fieldnames=campos)
        escritor.writeheader()

        for linha in leitor:
            if float(linha['nota']) >= 7:
                escritor.writerow(linha)

print("Arquivo 'aprovados.csv' gerado com sucesso!")
