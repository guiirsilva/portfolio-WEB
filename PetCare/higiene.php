<?php
require_once 'session_check.php';
require_once 'conexaobd.php'; // Inclui a conexão ao banco de dados

$mensagemSucesso = $mensagemErro = "";

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $idTutor = $_SESSION['idTutor']; // Obtém o id do tutor da sessão
    $idPet = $_POST['idPet'];
    $categorias = isset($_POST['categoria']) ? $_POST['categoria'] : [];

    // Verifica se o campo "Outros" foi preenchido
    if (in_array('outros', $categorias)) {
        $outros = $_POST['outraCategoria'];
        $categorias = array_diff($categorias, ['outros']); // Remove 'outros' da lista
        $categorias[] = "outros ($outros)"; // Adiciona 'outros (valor digitado)'
    }

    $categoria = implode(',', $categorias); // Converte o array em uma string separada por vírgulas
    $dataHigiene = $_POST['dataHigiene'];
    $anotacoes = $_POST['anotacoes'];
    $dataCadastro = date('Y-m-d H:i:s'); // Obtém a data e hora atuais

    $stmt = $conn->prepare("INSERT INTO higiene (idPet, idTutor, categoria, dataHigiene, anotacoes, dataCadastro) VALUES (?, ?, ?, ?, ?, ?)");

    if ($stmt === false) {
        die("Erro na preparação da consulta: " . $conn->error);
    }

    $stmt->bind_param("iissss", $idPet, $idTutor, $categoria, $dataHigiene, $anotacoes, $dataCadastro);

    if ($stmt->execute()) {
        $mensagemSucesso = "<div class='alert alert-success'>Dados de higiene registrados com sucesso.</div>";
    } else {
        $mensagemErro = "<div class='alert alert-danger'>Erro: " . $stmt->error . "</div>";
    }

    $stmt->close();
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
    <title>Higiene</title>
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
            <h2 class="card-title text-center">Higiene</h2>

            <?= $mensagemSucesso ?>
            <?= $mensagemErro ?>

            <form method="POST" action="higiene.php">
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
                    <label>Categoria:</label>
                    <?php
                    $categorias = [
                        "banho" => "Banho",
                        "tosa" => "Tosa",
                        "dentes" => "Dentes",
                        "orelhas" => "Orelhas",
                        "outros" => "Outros"
                    ];

                    foreach ($categorias as $value => $label) {
                        echo "<div class='form-check'>
                                <input class='form-check-input' type='checkbox' name='categoria[]' id='$value' value='$value'>
                                <label class='form-check-label' for='$value'>$label</label>
                              </div>";
                    }
                    ?>
                </div>

                <div class="form-group" id="outraCategoria" style="display:none;">
                    <label for="outraCategoriaInput">Especifique:</label>
                    <input type="text" class="form-control" id="outraCategoriaInput" name="outraCategoria">
                </div>

                <div class="form-group">
                    <label for="dataHigiene">Data:</label>
                    <input type="date" class="form-control" id="dataHigiene" name="dataHigiene" required>
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
    <script>
        document.getElementById('outros').addEventListener('change', function() {
            var outraCategoria = document.getElementById('outraCategoria');
            if (this.checked) {
                outraCategoria.style.display = 'block';
            } else {
                outraCategoria.style.display = 'none';
            }
        });
    </script>
</body>

</html>