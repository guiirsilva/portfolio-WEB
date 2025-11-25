import pandas as pd

df = pd.read_csv('dados.csv')

media = df['valor'].mean()

df['valor_normalizado'] = df['valor'] / media

df.to_csv('dados_com_media.csv', index=False)

print("Arquivo 'dados_com_media.csv' gerado com sucesso!")
