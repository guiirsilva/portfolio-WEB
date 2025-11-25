import json

with open('config.json', 'r', encoding='utf-8') as f:
    config = json.load(f)

if 'versao' not in config:
    print("Erro: o arquivo JSON não contém o campo 'versao'.")
else:
    print("=== CONFIGURAÇÕES ===")
    print(f"Nome: {config.get('nome', '(não informado)')}")
    print(f"Versão: {config['versao']}")
    print("Parâmetros:")
    
    parametros = config.get('parametros', {})
    for chave, valor in parametros.items():
        print(f"  - {chave}: {valor}")
