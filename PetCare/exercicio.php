<?php
require_once 'session_check.php';
require_once 'conexaobd.php'; // Inclui a conexão ao banco de dados

$mensagemSucesso = $mensagemErro = "";

function registrarExercicio($conn, $idTutor, $idPet, $tipoExercicio, $dataExercicio, $qtdVezesDia, $tempoMedio, $observacoes) {
    $dataCadastro = date('Y-m-d H:i:s'); // Obtém a data e hora atuais
    $stmt = $conn->prepare("INSERT INTO exercicios (idPet, idTutor, tipoExercicio, dataExercicio, qtdVezesDia, tempoMedio, observacoes, dataCadastro) VALUES (?, ?, ?, ?, ?, ?, ?, ?)");

    if ($stmt === false) {
        die("Erro na preparação da consulta: " . $conn->error);
    }

    $stmt->bind_param("iissssss", $idPet, $idTutor, $tipoExercicio, $dataExercicio, $qtdVezesDia, $tempoMedio, $observacoes, $dataCadastro);

    if ($stmt->execute()) {
        return "<div class='alert alert-success'>Dados de exercícios registrados com sucesso.</div>";
    } else {
        return "<div class='alert alert-danger'>Erro: " . $stmt->error . "</div>";
    }
}

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $idTutor = $_SESSION['idTutor']; // Obtém o id do tutor da sessão
    $idPet = $_POST['idPet'];
    $tipoExercicio = isset($_POST['tipoExercicio']) ? $_POST['tipoExercicio'] : [];

    // Verifica se o campo "Outros" foi preenchido
    if (in_array('outros', $tipoExercicio)) {
        $outros = $_POST['outraCategoria'];
        $tipoExercicio = array_diff($tipoExercicio, ['outros']); // Remove 'outros' da lista
        $tipoExercicio[] = "outros ($outros)"; // Adiciona 'outros (valor digitado)'
    }

    $tipoExercicio = implode(',', $tipoExercicio); // Converte o array em uma string separada por vírgulas
    $dataExercicio = $_POST['dataExercicio'];
    $qtdVezesDia = $_POST['qtdVezesDia'];
    $tempoMedio = $_POST['tempoMedio'];
    $observacoes = $_POST['observacoes'];

    $mensagemSucesso = registrarExercicio($conn, $idTutor, $idPet, $tipoExercicio, $dataExercicio, $qtdVezesDia, $tempoMedio, $observacoes);
}

$idTutor = $_SESSION['idTutor']; // Supondo que o id do tutor esteja na sessão
$result = $conn->query("SELECT idPet, nomePet FROM animal WHERE idTutor = $idTutor");

$conn->close();
?>

<!DOCTYPE html>
<html lang="pt-br">

<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>Exercícios</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet" />
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.5/font/bootstrap-icons.css" />
    <link rel="stylesheet" href="./CSS/principal.css" />
</head>

<body>
    <div class="menu-toggle">
        <div class="bar"></div>
        <div class="bar"></div>
        <div class="bar"></div>
    </div>

    <?php include 'sidebar.php'; ?>

    <div class="content">
        <div class="form-container">
            <h2 class="card-title text-center">Exercícios</h2>

            <?= $mensagemSucesso ?>
            <?= $mensagemErro ?>

            <form method="POST" action="exercicio.php">
                <div class="form-group">
                    <label for="idPet">Selecione o Pet:</label>
                    <select class="form-control" id="idPet" name="idPet" required>
                        <?php if ($result): ?>
                            <?php if ($result->num_rows > 0): ?>
                                <?php while ($row = $result->fetch_assoc()): ?>
                                    <option value="<?= $row['idPet'] ?>"><?= htmlspecialchars($row['nomePet']) ?></option>
                                <?php endwhile; ?>
                            <?php else: ?>
                                <option value="">Nenhum pet encontrado</option>
                            <?php endif; ?>
                        <?php else: ?>
                            <option value="">Erro na consulta: <?= htmlspecialchars($conn->error) ?></option>
                        <?php endif; ?>
                    </select>
                </div>
                <div class="form-group">
                    <label for="tipoExercicio">Tipo de Exercício:</label>
                    <select class="form-control" id="tipoExercicio" name="tipoExercicio[]" required>
                        <option value="passeio_de_coleira">Passeio de Coleira</option>
                        <option value="brincadeiras">Brincadeiras</option>
                        <option value="corrida">Corrida</option>
                        <option value="natacao">Natação</option>
                        <option value="adestramento">Adestramento</option>
                        <option value="outros">Outros</option>
                    </select>
                </div>

                <div class="form-group" id="outroExercicio" style="display:none;">
                    <label for="outraCategoria">Especifique o Exercício:</label>
                    <input type="text" class="form-control" id="outraCategoria" name="outraCategoria">
                </div>

                <div class="form-group">
                    <label for="dataExercicio">Data do Exercício:</label>
                    <input type="date" class="form-control" id="dataExercicio" name="dataExercicio" required>
                </div>

                <div class="form-group">
                    <label for="qtdVezesDia">Quantas Vezes ao Dia:</label>
                    <input type="number" class="form-control" id="qtdVezesDia" name="qtdVezesDia" required>
                </div>

                <div class="form-group">
                    <label for="tempoMedio">Tempo Médio do Exercício (minutos):</label>
                    <input type="number" class="form-control" id="tempoMedio" name="tempoMedio" required>
                </div>

                <div class="form-group">
                    <label for="observacoes">Observações:</label>
                    <textarea class="form-control" id="observacoes" name="observacoes"></textarea>
                </div>

                <button type="submit" class="btn btn-success btn-block btn-center">
                    <i class="bi bi-check-circle"></i> Registrar
                </button>
            </form>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script src="JS/script.js"></script>
    <script>
        document.getElementById('tipoExercicio').addEventListener('change', function() {
            var outroExercicio = document.getElementById('outroExercicio');
            if (this.value === 'outros') {
                outroExercicio.style.display = 'block';
            } else {
                outroExercicio.style.display = 'none';
            }
        });
    </script>
</body>

</html>