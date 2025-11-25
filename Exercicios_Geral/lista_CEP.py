import requests

def consulta_cep(cep):
    url = f"https://viacep.com.br/ws/{cep}/json/"
    res = requests.get(url).json()

    if "erro" in res:
        return None

    logradouro = res.get("logradouro", "")
    uf = res.get("uf", "")

    return (logradouro, uf)

lista_cep = [
    "13186642",
    "13178574",
    "13188020",
    "13184321",
    "20720293"]

resultado = [
    dados[0]              
    for cep in lista_cep
    for dados in [consulta_cep(cep)]
    if dados and dados[1] == "SP"
]

print(resultado)
