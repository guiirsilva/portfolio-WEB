import requests

def consulta_cep(cep):
  url = f"https://viacep.com.br/ws/{cep}/json/"
  res = requests.get(url)
  res = res.json()
  return (res['logradouro'], res['uf'])

lista_cep = ["13186642",
             "13178574",
             "13188020",
             "13184321"]
