<?php
require_once 'session_check.php';
require_once 'conexaobd.php'; // Inclui a conexão ao banco de dados

$mensagemSucesso = $mensagemErro = "";

function registrarControleParasitario($conn, $idTutor, $idPet, $nomeMedicacao, $dataAdministrada, $categoria, $frequenciaUso, $anotacoes) {
    $dataCadastro = date('Y-m-d H:i:s'); // Obtém a data e hora atuais
    $stmt = $conn->prepare("INSERT INTO controleparasitario (idPet, idTutor, nomeMedicacao, dataAdministrada, categoria, frequenciaUso, anotacoes, dataCadastro) VALUES (?, ?, ?, ?, ?, ?, ?, ?)");

    if ($stmt === false) {
        die("Erro na preparação da consulta: " . $conn->error);
    }

    $stmt->bind_param("iissssss", $idPet, $idTutor, $nomeMedicacao, $dataAdministrada, $categoria, $frequenciaUso, $anotacoes, $dataCadastro);

    if ($stmt->execute()) {
        return "<div class='alert alert-success'>Dados do controle parasitário registrados com sucesso.</div>";
    } else {
        return "<div class='alert alert-danger'>Erro: " . $stmt->error . "</div>";
    }
}

if ($_SERVER["REQUEST_METHOD"] === "POST") {
    $idTutor = $_SESSION['idTutor'];
    $idPet = $_POST['idPet'];
    $nomeMedicacao = $_POST['nomeMedicacao'];
    $dataAdministrada = $_POST['dataAdministrada'];
    $categoria = $_POST['categoria'];
    $frequenciaUso = $_POST['frequenciaUso'];
    $anotacoes = $_POST['anotacoes'];

    $mensagemSucesso = registrarControleParasitario($conn, $idTutor, $idPet, $nomeMedicacao, $dataAdministrada, $categoria, $frequenciaUso, $anotacoes);
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
    <title>Controle Parasitário</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css">
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
            <h2 class="card-title text-center">Controle Parasitário</h2>

            <?= $mensagemSucesso ?>
            <?= $mensagemErro ?>

            <form method="POST" action="controleparasitario.php">
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
                    <label for="nomeMedicacao">Nome da Medicação:</label>
                    <input type="text" class="form-control" id="nomeMedicacao" name="nomeMedicacao" required>
                </div>
                <div class="form-group">
                    <label for="dataAdministrada">Data Administrada:</label>
                    <input type="date" class="form-control" id="dataAdministrada" name="dataAdministrada" required>
                </div>
                <div class="form-group">
                    <label>Categoria:</label>
                    <div class="form-check">
                        <input class="form-check-input" type="radio" name="categoria" id="interno" value="interno" checked>
                        <label class="form-check-label" for="interno">Uso Interno</label>
                    </div>
                    <div class="form-check">
                        <input class="form-check-input" type="radio" name="categoria" id="externo" value="externo">
                        <label class="form-check-label" for="externo">Uso Externo</label>
                    </div>
                </div>
                <div class="form-group">
                    <label>Frequência de Uso:</label>
                    <div class="form-check">
                        <input class="form-check-input" type="radio" name="frequenciaUso" id="continuo" value="continuo" checked>
                        <label class="form-check-label" for="continuo">Contínuo</label>
                    </div>
                    <div class="form-check">
                        <input class="form-check-input" type="radio" name="frequenciaUso" id="naoContinuo" value="naoContinuo">
                        <label class="form-check-label" for="naoContinuo">Não Contínuo</label>
                    </div>
                </div>
                <div class="form-group">
                    <label for="anotacoes">Anotações:</label>
                    <textarea class="form-control" id="anotacoes" name="anotacoes"></textarea>
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