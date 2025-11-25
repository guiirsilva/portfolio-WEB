import customtkinter as ctk

ctk.set_appearance_mode("dark")

descricoes_atividade = {
    "Sedentário": "Nenhuma atividade física regular",
    "Leve": "Exercício leve 1–3 vezes/semana",
    "Moderado": "Exercício moderado 3–5 vezes/semana",
    "Intenso": "Exercício intenso 6–7 vezes/semana",
    "Muito intenso": "Treino duplo ou trabalho físico pesado"
}

def tmb():
    try:
        sexo = campo_sexo.get()
        altura = float(campo_altura.get())
        peso = float(campo_peso.get())
        idade = int(campo_idade.get())
        atividade = campo_frequencia.get()

        if sexo == "Homem":
            tmb_calc = 88.362 + (13.397 * peso) + (4.799 * altura) - (5.677 * idade)
        elif sexo == "Mulher":
            tmb_calc = 447.593 + (9.247 * peso) + (3.098 * altura) - (4.330 * idade)
        else:
            label_resultado.configure(text="Selecione o sexo corretamente!")
            return

        fatores = {
            "Sedentário": 1.2,
            "Leve": 1.375,
            "Moderado": 1.55,
            "Intenso": 1.725,
            "Muito intenso": 1.9
        }

        gt = tmb_calc * fatores.get(atividade, 1)

        calorias_emagrecer = gt - 300
        calorias_ganhar = gt + 300

        label_resultado.configure(text=f"O seu Gasto Energético Total(GET) é de {gt:.2f} kcal/dia")
        label_resultem.configure(text=f"Recomendado: ~ {calorias_emagrecer:2f} kcal/dia")
        label_resultpe.configure(text=f"Recomendado: ~ {calorias_ganhar:.2f} kcal/dia")

        campo_altura.delete(0, ctk.END)
        campo_peso.delete(0, ctk.END)
        campo_idade.delete(0, ctk.END)
        campo_frequencia.set("Sedentário")
        label_descricao.configure(text='')

        mostrar_pagina(pagina2)

    except ValueError:
        label_erro.configure(text="Por favor, preencha todos os campos!", text_color="red")

app = ctk.CTk()
app.title("Calculadora de GET")
app.geometry("300x500")

def mostrar_pagina(pagina):
    for frame in [pagina1, pagina2]:
        frame.pack_forget()
    pagina.pack(fill="both", expand=True)

pagina1 = ctk.CTkFrame(app)

label = ctk.CTkLabel(pagina1, text='Calculadora de Gasto Energetico Total' \
'', font=('Arial', 12, 'bold'))
label.pack(pady=(10, 0))

label_sexo = ctk.CTkLabel(pagina1, text="Sexo:")
label_sexo.pack(pady=(10, 0))
opcoes_sexo = ["Homem", "Mulher"]
campo_sexo = ctk.CTkOptionMenu(pagina1, values=opcoes_sexo)
campo_sexo.pack(pady=1)

label_altura = ctk.CTkLabel(pagina1, text="Altura (cm):")
label_altura.pack(pady=(10, 0))
campo_altura = ctk.CTkEntry(pagina1, placeholder_text="Digite sua altura")
campo_altura.pack(pady=1)

label_peso = ctk.CTkLabel(pagina1, text="Peso (kg):")
label_peso.pack(pady=(10, 0))
campo_peso = ctk.CTkEntry(pagina1, placeholder_text="Digite seu peso")
campo_peso.pack(pady=1)

label_idade = ctk.CTkLabel(pagina1, text="Idade:")
label_idade.pack(pady=(10, 0))
campo_idade = ctk.CTkEntry(pagina1, placeholder_text="Digite sua idade")
campo_idade.pack(pady=1)

label_frequencia = ctk.CTkLabel(pagina1, text="Nível de atividade física diária:")
label_frequencia.pack(pady=(10, 0))
opcoes_frequencia = list(descricoes_atividade.keys())
campo_frequencia = ctk.CTkOptionMenu(pagina1, values=opcoes_frequencia,
                                     command=lambda escolha: label_descricao.configure(
                                         text=descricoes_atividade[escolha]))
campo_frequencia.pack(pady=1)

label_descricao = ctk.CTkLabel(pagina1, text="", font=("Arial", 10),  text_color="gray")
label_descricao.pack()

botao_calcular = ctk.CTkButton(pagina1, text="Calcular", command=tmb)
botao_calcular.pack(pady=(20, 0))

label_erro = ctk.CTkLabel(pagina1, text='', font=("Arial", 12), text_color='white')
label_erro.pack(pady=4)

pagina2 = ctk.CTkFrame(app)

label_titulo = ctk.CTkLabel(pagina2, text='Resultado', font=("Arial", 16, "bold"))
label_titulo.pack(pady=10)

label_resultado = ctk.CTkLabel(pagina2, text='', wraplength=250, font=("Arial", 13))
label_resultado.pack(pady=20)

label_dicaem = ctk.CTkLabel(
    pagina2, 
    text=(
        "-> Se o seu objetivo é emagrecer, procure consumir "
        "menos calorias do que o valor indicado, criando um déficit "
        "calórico de forma saudável e gradual."
    ),
    wraplength=250,
    justify="left",
    font=("Arial", 13),
    
    )
label_dicaem.pack(pady=3)
label_resultem = ctk.CTkLabel(
    pagina2, 
    text="", 
    wraplength=250,
    justify="center",
    font=("Arial", 13), 
    )
label_resultem.pack(pady=1)

label_dicape = ctk.CTkLabel(
    pagina2, 
    text=(
        "-> Se deseja ganhar peso ou massa muscular, é necessário"
        "ingerir mais calorias do que o gasto diário, priorizando alimentos"
        "ricos em proteínas, carboidratos de qualidade e gorduras boas."
    ),
    wraplength=250,
    justify="left",
    font=("Arial", 13),
    )
label_dicape.pack(pady=3)
label_resultpe = ctk.CTkLabel(
    pagina2, 
    text="", 
    wraplength=250,
    justify="center",
    font=("Arial", 13), 
    )
label_resultpe.pack(pady=1)

label_recomendacao = ctk.CTkLabel(
    pagina2,
    text=(
        "Lembre-se: esses valores são apenas uma estimativa. "
        "Procure sempre a orientação de um nutricionista para um plano "
        "personalizado."
        ),
    wraplength=250,
    justify="left",
    font=("Arial", 13),
    )
label_recomendacao.pack(pady=3)

botao_voltar = ctk.CTkButton(pagina2, text="Voltar", command=lambda: [mostrar_pagina(pagina1), label_erro.configure(text="")])
botao_voltar.pack(pady=20)

mostrar_pagina(pagina1)

app.mainloop()