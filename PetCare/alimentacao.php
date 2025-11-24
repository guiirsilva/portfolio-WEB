<?php
require_once 'session_check.php';
require_once 'conexaobd.php'; // Inclui a conexão ao banco de dados

$mensagemSucesso = $mensagemErro = "";

function registrarAlimentacao($conn, $idTutor, $idPet, $marcaRacao, $qtdDiaria, $horariosRefeicoes, $anotacoes) {
    $dataCadastro = date('Y-m-d H:i:s'); // Obtém a data e hora atuais
    $stmt = $conn->prepare("INSERT INTO alimentacao (idPet, marcaRacao, qtdDiaria, horariosRefeicoes, anotacoes, idTutor, dataCadastro) VALUES (?, ?, ?, ?, ?, ?, ?)");

    if (!$stmt) {
        die("Erro na preparação da consulta: " . $conn->error);
    }

    $stmt->bind_param("isissis", $idPet, $marcaRacao, $qtdDiaria, $horariosRefeicoes, $anotacoes, $idTutor, $dataCadastro);

    if ($stmt->execute()) {
        return "<div class='alert alert-success'>Dados de alimentação registrados com sucesso.</div>";
    } else {
        return "<div class='alert alert-danger'>Erro: " . $stmt->error . "</div>";
    }
}

if ($_SERVER["REQUEST_METHOD"] === "POST") {
    $idTutor = $_SESSION['idTutor'];
    $idPet = $_POST['idPet'];
    $marcaRacao = $_POST['racao'];
    $qtdDiaria = $_POST['qtdDiaria'];
    $horariosRefeicoes = $_POST['horariosAlimentacao'];
    $anotacoes = $_POST['anotacoesAlimentacao'];

    $mensagemSucesso = registrarAlimentacao($conn, $idTutor, $idPet, $marcaRacao, $qtdDiaria, $horariosRefeicoes, $anotacoes);
}

$idTutor = $_SESSION['idTutor'];
$result = $conn->query("SELECT idPet, nomePet FROM animal WHERE idTutor = $idTutor");

$conn->close();
?>

<!DOCTYPE html>
<html lang="pt-br">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Alimentação</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.5/font/bootstrap-icons.css">
    <link rel="stylesheet" href="./CSS/principal.css">
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
            <h2 class="card-title text-center">Alimentação</h2>

            <?= $mensagemSucesso ?>
            <?= $mensagemErro ?>

            <form method="POST" action="alimentacao.php">
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
                    <label for="racao">Marca da ração:</label>
                    <input type="text" class="form-control" id="racao" name="racao" required>
                </div>
                <div class="form-group">
                    <label for="qtdDiaria">Quantidade diária (gramas):</label>
                    <input type="number" class="form-control" id="qtdDiaria" name="qtdDiaria" required>
                </div>
                <div class="form-group">
                    <label for="horariosAlimentacao">Horários das refeições:</label>
                    <input type="text" class="form-control" id="horariosAlimentacao" name="horariosAlimentacao" placeholder="Ex: 8h, 12h, 18h" required>
                </div>
                <div class="form-group">
                    <label for="anotacoesAlimentacao">Anotações:</label>
                    <textarea class="form-control" id="anotacoesAlimentacao" name="anotacoesAlimentacao" rows="3" maxlength="250"></textarea>
                </div>
                <button type="submit" class="btn btn-success btn-block btn-center">
                    <i class="bi bi-check-circle"></i> Registrar
                </button>
            </form>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script src="JS/script.js"></script>
</body>

</html>