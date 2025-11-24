<?php
require_once 'session_check.php';
require_once 'conexaobd.php'; // Inclui o arquivo de conexão ao banco de dados

$mensagemSucesso = $mensagemErro = "";

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $idTutor = $_SESSION['idTutor']; // Obtém o id do tutor da sessão
    $idPet = $_POST['idPet'];
    $nomeVacina = $_POST['nomeVacina'];
    $dataAdministrada = $_POST['dataAdministrada'];
    $laboratorio = $_POST['laboratorio'];
    $lote = $_POST['lote'];
    $anotacoes = $_POST['anotacoes'];
    $dataCadastro = date('Y-m-d H:i:s'); // Obtém a data e hora atuais

    $stmt = $conn->prepare("INSERT INTO vacinas (idPet, idTutor, nomeVacina, dataAdministrada, laboratorio, lote, anotacoes, dataCadastro) VALUES (?, ?, ?, ?, ?, ?, ?, ?)");

    if (!$stmt) {
        die("Erro na preparação da consulta: " . $conn->error);
    }

    $stmt->bind_param("iissssss", $idPet, $idTutor, $nomeVacina, $dataAdministrada, $laboratorio, $lote, $anotacoes, $dataCadastro);

    if ($stmt->execute()) {
        $mensagemSucesso = "<div class='alert alert-success'>Dados de vacina registrados com sucesso.</div>";
    } else {
        $mensagemErro = "<div class='alert alert-danger'>Erro: " . $stmt->error . "</div>";
    }

    $stmt->close();
}

$idTutor = $_SESSION['idTutor'];
$result = $conn->query("SELECT idPet, nomePet FROM animal WHERE idTutor = $idTutor");

$conn->close();
?>

<!DOCTYPE html>
<html lang="pt-br">

<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>Vacinas</title>
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
            <h2 class="card-title text-center">Vacinas</h2>

            <?= $mensagemSucesso ?>
            <?= $mensagemErro ?>

            <form method="POST" action="vacinas.php">
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
                    <label for="nomeVacina">Nome da vacina:</label>
                    <input type="text" class="form-control" id="nomeVacina" name="nomeVacina" required>
                </div>
                <div class="form-group">
                    <label for="dataAdministrada">Data administrada:</label>
                    <input type="date" class="form-control" id="dataAdministrada" name="dataAdministrada" required>
                </div>
                <div class="form-group">
                    <label for="laboratorio">Laboratório:</label>
                    <input type="text" class="form-control" id="laboratorio" name="laboratorio" required>
                </div>
                <div class="form-group">
                    <label for="lote">Lote:</label>
                    <input type="text" class="form-control" id="lote" name="lote" required>
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