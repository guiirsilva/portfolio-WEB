const btn = document.querySelector(".acordeao-btn");
const conteudo = document.querySelector(".acordeao-conteudo");

btn.addEventListener("click", () => {
  if (conteudo.classList.contains("aberto")) {
    conteudo.style.maxHeight = 0;
    conteudo.classList.remove("aberto");
    btn.textContent = "Abrir bloco";
  } else {
    conteudo.style.maxHeight = conteudo.scrollHeight + "px";
    conteudo.classList.add("aberto");
    btn.textContent = "Fechar bloco";
  }
});
