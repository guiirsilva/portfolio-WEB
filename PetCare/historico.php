<?php
require_once 'session_check.php';
require_once 'conexaobd.php';

$idTutor = $_SESSION['idTutor'];
$mensagemSucesso = $mensagemErro = "";

// Função para buscar os dados do tutor
function buscarDadosTutor($conn, $idTutor) {
    $sqlTutor = "SELECT nomeTutor, email FROM tutor WHERE idTutor = ?";
    $stmtTutor = $conn->prepare($sqlTutor);
    $stmtTutor->bind_param("i", $idTutor);
    $stmtTutor->execute();
    $resultTutor = $stmtTutor->get_result();
    $tutor = $resultTutor->fetch_assoc();
    $stmtTutor->close();
    return $tutor;
}

// Função para buscar os dados do histórico do pet
function buscarDadosHistorico($conn, $idPet) {
    $historico = [];

    // Consulta para buscar dados de alimentação
    $sqlAlimentacao = "SELECT * FROM alimentacao WHERE idPet = ?";
    $stmtAlimentacao = $conn->prepare($sqlAlimentacao);
    $stmtAlimentacao->bind_param("i", $idPet);
    $stmtAlimentacao->execute();
    $resultAlimentacao = $stmtAlimentacao->get_result();
    $historico['alimentacao'] = $resultAlimentacao->fetch_all(MYSQLI_ASSOC);

    // Consulta para buscar dados de controle parasitário
    $sqlControleParasitario = "SELECT * FROM controleparasitario WHERE idPet = ?";
    $stmtControleParasitario = $conn->prepare($sqlControleParasitario);
    $stmtControleParasitario->bind_param("i", $idPet);
    $stmtControleParasitario->execute();
    $resultControleParasitario = $stmtControleParasitario->get_result();
    $historico['controleparasitario'] = $resultControleParasitario->fetch_all(MYSQLI_ASSOC);

    // Consulta para buscar dados de exercícios
    $sqlExercicios = "SELECT * FROM exercicios WHERE idPet = ?";
    $stmtExercicios = $conn->prepare($sqlExercicios);
    $stmtExercicios->bind_param("i", $idPet);
    $stmtExercicios->execute();
    $resultExercicios = $stmtExercicios->get_result();
    $historico['exercicios'] = $resultExercicios->fetch_all(MYSQLI_ASSOC);

    // Consulta para buscar dados de higiene
    $sqlHigiene = "SELECT * FROM higiene WHERE idPet = ?";
    $stmtHigiene = $conn->prepare($sqlHigiene);
    $stmtHigiene->bind_param("i", $idPet);
    $stmtHigiene->execute();
    $resultHigiene = $stmtHigiene->get_result();
    $historico['higiene'] = $resultHigiene->fetch_all(MYSQLI_ASSOC);

    // Consulta para buscar dados de vacinas
    $sqlVacinas = "SELECT * FROM vacinas WHERE idPet = ?";
    $stmtVacinas = $conn->prepare($sqlVacinas);
    $stmtVacinas->bind_param("i", $idPet);
    $stmtVacinas->execute();
    $resultVacinas = $stmtVacinas->get_result();
    $historico['vacinas'] = $resultVacinas->fetch_all(MYSQLI_ASSOC);

    // Consulta para buscar dados do pet
    $sqlPet = "SELECT nomePet, dataNascimentoPet, especie, raca, sexo, microchip, castracao FROM animal WHERE idPet = ?";
    $stmtPet = $conn->prepare($sqlPet);
    $stmtPet->bind_param("i", $idPet);
    $stmtPet->execute();
    $resultPet = $stmtPet->get_result();
    $historico['pet'] = $resultPet->fetch_assoc();

    return $historico;
}

// Verifica se o formulário foi submetido para selecionar o pet
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['idPet'])) {
    $idPet = $_POST['idPet'];
    $tutor = buscarDadosTutor($conn, $idTutor);
    $historico = buscarDadosHistorico($conn, $idPet);
}

// Consulta para buscar os pets do tutor
$sqlPets = "SELECT idPet, nomePet FROM animal WHERE idTutor = ?";
$stmtPets = $conn->prepare($sqlPets);
$stmtPets->bind_param("i", $idTutor);
$stmtPets->execute();
$resultPets = $stmtPets->get_result();
?>

<!DOCTYPE html>
<html lang="pt-br">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Histórico</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.5/font/bootstrap-icons.css">
    <link rel="stylesheet" href="./CSS/principal.css">
    <script src="https://cdnjs.cloudflare.com/ajax/libs/html2pdf.js/0.9.3/html2pdf.bundle.min.js"></script>
</head>

<body>
    <div class="menu-toggle">
        <div class="bar"></div>
        <div class="bar"></div>
        <div class="bar"></div>
    </div>

    <?php include 'sidebar.php'; ?>

    <div class="content-printable">
        <div class="form-container">
            <h2 class="card-title text-center">Histórico</h2>

            <?php if (!isset($idPet)): ?>
                <form method="POST" action="historico.php">
                    <!-- Seleção do pet -->
                    <div class="form-group">
                        <label for="idPet">Selecione o Pet:</label>
                        <select class="form-control" id="idPet" name="idPet" required>
                            <?php
                            if ($resultPets) {
                                if ($resultPets->num_rows > 0) {
                                    while ($row = $resultPets->fetch_assoc()) {
                                        echo "<option value='" . $row['idPet'] . "'>" . $row['nomePet'] . "</option>";
                                    }
                                } else {
                                    echo "<option value=''>Nenhum pet encontrado</option>";
                                }
                            } else {
                                echo "<option value=''>Erro na consulta: " . $conn->error . "</option>";
                            }
                            ?>
                        </select>
                    </div>
                    <button type="submit" class="btn btn-primary">
                        <i class="bi bi-search"></i> Visualizar Histórico
                    </button>
                </form>
            <?php else: ?>

                <div id="historico-pet">
                    <!-- Cabeçalho com dados do tutor e do pet -->
                    <section class="header_section">
                        <h3>Dados do Tutor</h3>
                        <p>Nome: <?= htmlspecialchars($tutor['nomeTutor']) ?></p>
                        <p>Email: <?= htmlspecialchars($tutor['email']) ?></p>

                        <h3>Dados do Pet</h3>
                        <p>Nome: <?= htmlspecialchars($historico['pet']['nomePet']) ?></p>
                        <p>Nascimento: <?= htmlspecialchars(date('d/m/Y', strtotime($historico['pet']['dataNascimentoPet']))) ?></p>
                        <p>Espécie: <?= htmlspecialchars($historico['pet']['especie']) ?></p>
                        <p>Raça: <?= htmlspecialchars($historico['pet']['raca']) ?></p>
                        <p>Sexo: <?= $historico['pet']['sexo'] ? 'Macho' : 'Fêmea' ?></p>
                        <p>Microchip: <?= $historico['pet']['microchip'] ? 'Sim' : 'Não' ?></p>
                        <p>Castrado: <?= $historico['pet']['castracao'] ? 'Sim' : 'Não' ?></p>
                    </section>

                    <section class="historico-section">
                        <h3>Alimentação</h3>
                        <?php if (!empty($historico['alimentacao'])): ?>
                            <table class="table table-striped">
                                <thead>
                                    <tr>
                                        <th>Marca da Ração</th>
                                        <th>Quantidade Diária (em gramas)</th>
                                        <th>Horários das Refeições</th>
                                        <th>Anotações</th>
                                        <th>Data do Registro</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php foreach ($historico['alimentacao'] as $registro): ?>
                                        <tr>
                                            <td><?= htmlspecialchars($registro['marcaRacao']) ?></td>
                                            <td><?= htmlspecialchars($registro['qtdDiaria']) ?></td>
                                            <td><?= htmlspecialchars($registro['horariosRefeicoes']) ?></td>
                                            <td><?= htmlspecialchars($registro['anotacoes']) ?></td>
                                            <td><?= htmlspecialchars(date('d/m/Y H:i:s', strtotime($registro['dataCadastro']))) ?></td>
                                        </tr>
                                    <?php endforeach; ?>
                                </tbody>
                            </table>
                        <?php else: ?>
                            <p>Nenhum registro de alimentação encontrado.</p>
                        <?php endif; ?>
                    </section>

                    <section class="historico-section">
                        <h3>Controle Parasitário</h3>
                        <?php if (!empty($historico['controleparasitario'])): ?>
                            <table class="table table-striped">
                                <thead>
                                    <tr>
                                        <th>Nome do Medicamento</th>
                                        <th>Data Administrada</th>
                                        <th>Categoria de Uso</th>
                                        <th>Frequência de Uso</th>
                                        <th>Anotações</th>
                                        <th>Data do Registro</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php foreach ($historico['controleparasitario'] as $registro): ?>
                                        <tr>
                                            <td><?= htmlspecialchars($registro['nomeMedicacao']) ?></td>
                                            <td><?= htmlspecialchars(date('d/m/Y', strtotime($registro['dataAdministrada']))) ?></td>
                                            <td><?= htmlspecialchars($registro['categoria']) ?></td>
                                            <td><?= htmlspecialchars($registro['frequenciaUso']) ?></td>
                                            <td><?= htmlspecialchars($registro['anotacoes']) ?></td>
                                            <td><?= htmlspecialchars(date('d/m/Y H:i:s', strtotime($registro['dataCadastro']))) ?></td>
                                        </tr>
                                    <?php endforeach; ?>
                                </tbody>
                            </table>
                        <?php else: ?>
                            <p>Nenhum registro de controle parasitário encontrado.</p>
                        <?php endif; ?>
                    </section>

                    <section class="historico-section">
                        <h3>Exercícios</h3>
                        <?php if (!empty($historico['exercicios'])): ?>
                            <table class="table table-striped">
                                <thead>
                                    <tr>
                                        <th>Tipo de Exercício</th>
                                        <th>Tempo Médio do Exercício (minutos)</th>
                                        <th>Quantas Vezes ao Dia</th>
                                        <th>Anotações</th>
                                        <th>Data do Registro</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php foreach ($historico['exercicios'] as $registro): ?>
                                        <tr>
                                            <td><?= htmlspecialchars($registro['tipoExercicio']) ?></td>
                                            <td><?= htmlspecialchars($registro['tempoMedio']) ?></td>
                                            <td><?= htmlspecialchars($registro['qtdVezesDia']) ?></td>
                                            <td><?= htmlspecialchars($registro['observacoes']) ?></td>
                                            <td><?= htmlspecialchars(date('d/m/Y H:i:s', strtotime($registro['dataCadastro']))) ?></td>
                                        </tr>
                                    <?php endforeach; ?>
                                </tbody>
                            </table>
                        <?php else: ?>
                            <p>Nenhum registro de exercícios encontrado.</p>
                        <?php endif; ?>
                    </section>

                    <section class="historico-section">
                        <h3>Higiene</h3>
                        <?php if (!empty($historico['higiene'])): ?>
                            <table class="table table-striped">
                                <thead>
                                    <tr>
                                        <th>Categoria</th>
                                        <th>Data</th>
                                        <th>Anotações</th>
                                        <th>Data do Registro</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php foreach ($historico['higiene'] as $registro): ?>
                                        <tr>
                                            <td><?= htmlspecialchars($registro['categoria']) ?></td>
                                            <td><?= htmlspecialchars(date('d/m/Y H:i:s', strtotime($registro['dataHigiene']))) ?></td>
                                            <td><?= htmlspecialchars($registro['anotacoes']) ?></td>
                                            <td><?= htmlspecialchars(date('d/m/Y H:i:s', strtotime($registro['dataCadastro']))) ?></td>
                                        </tr>
                                    <?php endforeach; ?>
                                </tbody>
                            </table>
                        <?php else: ?>
                            <p>Nenhum registro de higiene encontrado.</p>
                        <?php endif; ?>
                    </section>

                    <section class="historico-section">
                        <h3>Vacinas</h3>
                        <?php if (!empty($historico['vacinas'])): ?>
                            <table class="table table-striped">
                                <thead>
                                    <tr>
                                        <th>Nome da Vacina</th>
                                        <th>Data Administrada</th>
                                        <th>Laboratório</th>
                                        <th>Lote</th>
                                        <th>Anotações</th>
                                        <th>Data do Registro</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php foreach ($historico['vacinas'] as $registro): ?>
                                        <tr>
                                            <td><?= htmlspecialchars($registro['nomeVacina']) ?></td>
                                            <td><?= htmlspecialchars(date('d/m/Y', strtotime($registro['dataAdministrada']))) ?></td>
                                            <td><?= htmlspecialchars($registro['laboratorio']) ?></td>
                                            <td><?= htmlspecialchars($registro['lote']) ?></td>
                                            <td><?= htmlspecialchars($registro['anotacoes']) ?></td>
                                            <td><?= htmlspecialchars(date('d/m/Y H:i:s', strtotime($registro['dataCadastro']))) ?></td>
                                        </tr>
                                    <?php endforeach; ?>
                                </tbody>
                            </table>
                        <?php else: ?>
                            <p>Nenhum registro de vacinas encontrado.</p>
                        <?php endif; ?>
                    </section>
                </div>
                <!-- Botão de imprimir no final do documento -->
                <button class="btn btn-secondary" id="imprimir-historico">
                    <i class="bi bi-printer"></i> Imprimir Histórico
                </button>
            <?php endif; ?>
        </div>
    </div>

    <script>
        // Função para imprimir o histórico em PDF
        document.getElementById('imprimir-historico').addEventListener('click', function() {
            var element = document.getElementById('historico-pet');
            var opt = {
                margin: 10,
                filename: 'historico_pet.pdf',
                image: {
                    type: 'jpeg',
                    quality: 0.98
                },
                html2canvas: {
                    scale: 2
                },
                jsPDF: {
                    unit: 'mm',
                    format: 'a4',
                    orientation: 'portrait'
                }
            };
            html2pdf().set(opt).from(element).save();
        });
    </script>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script src="./JS/script.js"></script>
</body>

</html>