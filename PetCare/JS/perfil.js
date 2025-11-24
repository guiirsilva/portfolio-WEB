// Função para redirecionar navegação
const setupRedirects = (selector, redirectTo) => {
  document.querySelectorAll(selector).forEach((element) => {
    element.addEventListener("click", function (event) {
      event.preventDefault();
      window.location.href = redirectTo(this);
    });
  });
};

// Função para excluir um pet
const deletePet = (button) => {
  const idPet = button.getAttribute('data-id');
  if (confirm('Tem certeza que deseja excluir este pet?')) {
    fetch('deletarPet.php', {
      method: 'POST',
      headers: { 'Content-Type': 'application/json' },
      credentials: 'same-origin',
      body: JSON.stringify({ idPet: idPet })
    })
      .then(response => response.json())
      .then(data => {
        if (data.status === 'success') {
          alert('Pet excluído com sucesso.');
          location.reload(); // Recarrega a página para atualizar a lista de pets
        } else {
          alert('Falha ao excluir o pet: ' + data.message);
        }
      })
      .catch(error => {
        console.error('Erro:', error);
        alert('Ocorreu um erro ao tentar excluir o pet.');
      });
  }
};

// Função para excluir a conta
const deleteAccount = () => {
  if (confirm('Tem certeza que deseja excluir sua conta? Todos os seus dados, incluindo os pets, serão excluídos permanentemente.')) {
    fetch('deletarTutor.php', {
      method: 'POST',
      headers: { 'Content-Type': 'application/json' }
    })
      .then(response => response.json())
      .then(data => {
        if (data.status === 'success') {
          alert('Conta excluída com sucesso.');
          window.location.href = 'index.php'; // Redireciona para a página inicial
        } else {
          alert('Falha ao excluir a conta: ' + data.message);
        }
      })
      .catch(error => {
        console.error('Erro:', error);
        alert('Ocorreu um erro ao tentar excluir a conta.');
      });
  }
};

// Inicializa eventos
const initEvents = () => {
  document.querySelector('.menu-toggle').addEventListener('click', () => {
    document.querySelector('.sidebar').classList.toggle('active');
  });

  document.getElementById('update-tutor').addEventListener('click', () => {
    window.location.href = 'atualizar.php';
  });

  document.querySelectorAll('.update-pet').forEach(button => {
    button.addEventListener('click', function () {
      const idPet = this.getAttribute('data-id');
      window.location.href = 'atualizarpet.php?id=' + idPet;
    });
  });

  document.getElementById('delete-account').addEventListener('click', deleteAccount);

  document.querySelectorAll('.delete-pet').forEach(button => {
    button.addEventListener('click', function () {
      deletePet(this);
    });
  });

  setupRedirects('.logout', () => 'index.php');
  setupRedirects('#cadastrar-pet', () => 'cadastroPet.php');
  setupRedirects('#historico-btn', () => 'historico.php');
};

// Inicializa a aplicação
document.addEventListener('DOMContentLoaded', initEvents);