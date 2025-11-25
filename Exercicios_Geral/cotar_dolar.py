from datetime import datetime, timedelta
import requests

def formatar_data(data_str):
    dt = datetime.strptime(data_str, "%m%d%Y")
    return dt.strftime("%m-%d-%Y")

def dia_anterior(data_str):
    dt = datetime.strptime(data_str, "%m%d%Y") - timedelta(days=1)
    return dt.strftime("%m%d%Y")

def cotar(data_str):
    data_ptax = formatar_data(data_str)

    url = (
        "https://olinda.bcb.gov.br/olinda/servico/PTAX/versao/v1/odata/"
        f"CotacaoDolarDia(dataCotacao=@dataCotacao)?@dataCotacao='{data_ptax}'&$format=json"
    )

    res = requests.get(url).json()

    if res.get("value"):
        return float(res["value"][0]["cotacaoVenda"])

    return cotar(dia_anterior(data_str))

resultados = [cotar(i) for i in ["09022024", "09012024", "08312024", "08302024", "08292024"]]
print(resultados)
