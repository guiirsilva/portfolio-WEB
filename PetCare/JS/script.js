// Listas de raças de cães e gatos
const racasCao = [
  "Akita", "Basset Hound", "Beagle", "Border Collie", "Boxer",
  "Bulldog Francês", "Cocker Spaniel", "Collie", "Dachshund", "Dálmata",
  "Doberman", "Golden Retriever", "Husky Siberiano", "Labrador Retriever",
  "Lhasa Apso", "Maltês", "Pastor Alemão", "Pastor Belga", "Pinscher",
  "Pit Bull", "Poodle", "Pug", "Rottweiler", "São Bernardo",
  "Shih Tzu", "Schnauzer", "Spitz Alemão (Lulu da Pomerânia)", "SRD",
  "Staffordshire Bull Terrier", "Yorkshire Terrier", "Outros"
];

const racasGato = [
  "Abissínio", "American Shorthair", "Angorá", "Bengal", "Birmanês",
  "British Shorthair", "Burmilla", "Chartreux", "Cornish Rex", "Devon Rex",
  "Exótico", "Havana Brown", "LaPerm", "Maine Coon", "Manx",
  "Munchkin", "Norueguês da Floresta", "Ocicat", "Oriental Shorthair",
  "Persa", "Peterbald", "Ragdoll", "Scottish Fold", "Selkirk Rex",
  "Siamês", "Siberiano", "Singapura", "Somali", "Sphynx", "SRD",
  "Tonquinês", "Outros"
];

// Função para atualizar as opções de raça
const updateRacas = (especie) => {
  const racaSelect = document.getElementById("raca");
  const outraRacaInput = document.getElementById("outraRaca");
  const hiddenOutraRaca = document.getElementById("hiddenOutraRaca");

  racaSelect.innerHTML = ""; // Limpa as opções anteriores

  const racas = especie === "Cão" ? racasCao : especie === "Gato" ? racasGato : [];
  racas.forEach((raca) => {
    const option = new Option(raca, raca);
    racaSelect.add(option);
  });

  racaSelect.onchange = () => {
    outraRacaInput.style.display = racaSelect.value === "Outros" ? "block" : "none";
    if (racaSelect.value === "Outros") outraRacaInput.value = ""; // Limpa o campo
  };

  outraRacaInput.oninput = () => {
    hiddenOutraRaca.value = outraRacaInput.value;
  };

  racaSelect.dispatchEvent(new Event("change")); // Atualiza o estado inicial
};

// Validação de data de nascimento
const validateDataNascimento = (event) => {
  const dataNascimentoPet = new Date(document.getElementById("dataNascimentoPet").value);
  const hoje = new Date();
  const dataMaxima = new Date(hoje.getFullYear() - 25, hoje.getMonth(), hoje.getDate());

  if (isNaN(dataNascimentoPet) || dataNascimentoPet > hoje || dataNascimentoPet < dataMaxima || dataNascimentoPet.getFullYear() < 1900) {
    event.preventDefault();
    alert("Por favor, insira uma data de nascimento válida. O animal deve ter no máximo 25 anos.");
  }
};

// Função para redirecionar navegação
const setupRedirects = (selector, redirectTo) => {
  document.querySelectorAll(selector).forEach((element) => {
    element.addEventListener("click", function (event) {
      event.preventDefault();
      window.location.href = redirectTo(this);
    });
  });
};

// Inicializa eventos
const initEvents = () => {
  document.getElementById("especie").addEventListener("change", function () {
    updateRacas(this.value);
  });

  document.getElementById("cadastroPetForm").addEventListener("submit", validateDataNascimento);

  document.querySelector(".menu-toggle").addEventListener("click", () => {
    document.querySelector(".sidebar").classList.toggle("active");
  });

  setupRedirects(".nav-link", (link) => link.getAttribute("href"));
  setupRedirects(".update-pet", (button) => `perfil.php?id=${button.getAttribute("data-id")}`);
  setupRedirects(".delete-pet", (button) => {
    const petId = button.getAttribute("data-id");
    if (confirm("Tem certeza que deseja excluir este pet?")) {
      fetch("deletarPet.php", {
        method: "POST",
        headers: { "Content-Type": "application/json" },
        body: JSON.stringify({ idPet: petId })
      })
        .then((response) => response.json())
        .then((data) => {
          alert(data.status === "success" ? "Pet excluído com sucesso." : `Falha ao excluir o pet: ${data.message}`);
          if (data.status === "success") location.reload();
        })
        .catch((error) => {
          console.error("Erro:", error);
          alert("Ocorreu um erro ao tentar excluir o pet.");
        });
    }
  });

  document.querySelector(".logout").addEventListener("click", (event) => {
    event.preventDefault();
    window.location.href = "index.php";
  });

  document.getElementById("update-tutor").addEventListener("click", function () {
    const idTutor = this.getAttribute("data-id");
    window.location.href = "atualizar.php?id=" + idTutor;
  });

  document.querySelectorAll(".update-pet").forEach((button) => {
    button.addEventListener("click", function () {
      const idPet = this.getAttribute("data-id");
      window.location.href = "atualizarpet.php?id=" + idPet;
    });
  });

  document.getElementById("delete-account").addEventListener("click", function () {
    if (confirm("Tem certeza que deseja excluir sua conta? Todos os seus dados, incluindo os pets, serão excluídos permanentemente.")) {
      fetch("deletarTutor.php", {
        method: "POST",
        headers: { "Content-Type": "application/json" }
      })
        .then((response) => response.json())
        .then((data) => {
          if (data.status === "success") {
            alert("Conta excluída com sucesso.");
            window.location.href = "index.php";
          } else {
            alert("Falha ao excluir a conta: " + data.message);
          }
        })
        .catch((error) => {
          console.error("Erro:", error);
          alert("Ocorreu um erro ao tentar excluir a conta.");
        });
    }
  });

  document.getElementById("especie").dispatchEvent(new Event("change"));
};

// Inicializa a aplicação
document.addEventListener("DOMContentLoaded", () => {
  initEvents();
  const especieElement = document.getElementById("especie");
  if (especieElement) {
    updateRacas(especieElement.value);
  }
});
