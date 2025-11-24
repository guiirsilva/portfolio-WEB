<?php
session_start();
if (!isset($_SESSION['idTutor'])) {
    header("Location: index.php");
    exit();
}
$idTutor = $_SESSION['idTutor'];

require 'conexaoBD.php';
$conn->set_charset("utf8mb4");

$tutor = getTutorData($conn, $idTutor);
$pets = getPetsData($conn, $idTutor);

$conn->close();

function getTutorData($conn, $idTutor) {
    $sqlTutor = "SELECT nomeTutor, email FROM tutor WHERE idTutor = ?";
    $stmtTutor = $conn->prepare($sqlTutor);
    if ($stmtTutor === false) {
        die('Erro na preparação da consulta: ' . htmlspecialchars($conn->error));
    }
    $stmtTutor->bind_param("i", $idTutor);
    $stmtTutor->execute();
    $resultTutor = $stmtTutor->get_result();
    $tutor = $resultTutor->fetch_assoc();
    $stmtTutor->close();
    return $tutor;
}

function getPetsData($conn, $idTutor) {
    $sql = "SELECT idPet, nomePet, dataNascimentoPet, especie, raca, sexo, microchip, castracao FROM animal WHERE idTutor = ?";
    $stmt = $conn->prepare($sql);
    if ($stmt === false) {
        die('Erro na preparação da consulta: ' . htmlspecialchars($conn->error));
    }
    $stmt->bind_param("i", $idTutor);
    $stmt->execute();
    $result = $stmt->get_result();
    $pets = [];
    while ($row = $result->fetch_assoc()) {
        $pets[] = $row;
    }
    $stmt->close();
    return $pets;
}
?>

<!DOCTYPE html>
<html lang="pt-br">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Perfil do Tutor</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-9ndCyUaIbzAi2FUVXJi0CjmCapSmO7SnpJef0486qhLnuZ2cdeRhO02iuK6FUUVM" crossorigin="anonymous">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.5/font/bootstrap-icons.css">
    <link rel="stylesheet" href="./css/principal.css">
</head>

<body>
    <div class="menu-toggle">
        <div class="bar"></div>
        <div class="bar"></div>
        <div class="bar"></div>
    </div>

    <div class="sidebar">
        <div class="logo">
            <img src="imgs/logo/logo_petcare.png" alt="Logo">
        </div>
        <ul class="nav flex-column">
            <?php
            $menuItems = [
                ["perfil.php", "imgs/icones/user.png", "Perfil"],
                ["alimentacao.php", "imgs/icones/racao.png", "Alimentação"],
                ["higiene.php", "imgs/icones/cachorro-banho.png", "Higiene"],
                ["exercicio.php", "imgs/icones/gato-exercicio.png", "Exercícios"],
                ["vacinas.php", "imgs/icones/vacina.png", "Vacinas"],
                ["controleparasitario.php", "imgs/icones/controle-parasita.png", "Controle Parasitário"],
                ["index.php", "imgs/icones/sair.png", "Logoff", "logout"]
            ];

            foreach ($menuItems as $item) {
                $class = isset($item[3]) ? $item[3] : "";
                echo "<li class='nav-item'>
                        <a class='nav-link $class' href='$item[0]'>
                            <img src='$item[1]' alt='$item[2]' class='icon-img icon-size'> $item[2]
                        </a>
                    </li>";
            }
            ?>
        </ul>
    </div>

    <div class="content">
        <div class="form-container">
            <h2 class="card-title text-center">Perfil</h2>

            <!-- Seção de Dados do Tutor -->
            <section class="tutor-data">
                <div>
                    <h3>Seus Dados</h3>
                    <p>Nome: <?php echo htmlspecialchars($tutor['nomeTutor']); ?></p>
                    <p>Email: <?php echo htmlspecialchars($tutor['email']); ?></p>
                    <p>Senha: ********</p>
                </div>
                <div class="buttons">
                    <button class="btn btn-primary" id="update-tutor" data-id="<?php echo $_SESSION['idTutor']; ?>">
                        <i class="bi bi-pencil"></i> Atualizar Dados
                    </button>
                    <button class="btn btn-danger" id="delete-account">
                        <i class="bi bi-trash"></i> Excluir Conta
                    </button>
                </div>
            </section>

            <!-- Seção de Pets -->
            <section class="pet-list">
                <h3>Seus Pets</h3>
                <table class="table table-striped">
                    <thead>
                        <tr>
                            <th>Nome</th>
                            <th>Nascimento</th>
                            <th>Espécie</th>
                            <th>Raça</th>
                            <th>Sexo</th>
                            <th>Microchip</th>
                            <th>Castrado</th>
                            <th>Ações</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($pets as $pet): ?>
                            <tr>
                                <td><?php echo htmlspecialchars($pet['nomePet']); ?></td>
                                <td><?php echo htmlspecialchars(date('d/m/Y', strtotime($pet['dataNascimentoPet']))); ?></td>
                                <td><?php echo htmlspecialchars($pet['especie']); ?></td>
                                <td><?php echo htmlspecialchars($pet['raca']); ?></td>
                                <td><?php echo $pet['sexo'] ? 'Macho' : 'Fêmea'; ?></td>
                                <td><?php echo $pet['microchip'] ? 'Sim' : 'Não'; ?></td>
                                <td><?php echo $pet['castracao'] ? 'Sim' : 'Não'; ?></td>
                                <td class="action-buttons">
                                    <button class="btn btn-secondary btn-sm update-pet" data-id="<?php echo $pet['idPet']; ?>">
                                        <i class="bi bi-pencil"></i> Atualizar
                                    </button>
                                    <button class="btn btn-danger btn-sm delete-pet" data-id="<?php echo $pet['idPet']; ?>">
                                        <i class="bi bi-trash"></i> Excluir
                                    </button>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            </section>

            <!-- Seção de Ações -->
            <section class="actions">
                <div class="buttons">
                    <button class="btn btn-success" id="cadastrar-pet">
                        <i class="bi bi-plus-circle"></i> Cadastrar Pet
                    </button>
                    <button class="btn btn-primary" id="historico-btn">
                        <i class="bi bi-clock-history"></i> Histórico
                    </button>
                </div>
            </section>
        </div> <!-- Close form-container -->
    </div> <!-- Close content -->

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js" integrity="sha384-geWF76RCwLtnZ8qwWowPQNguL3RmwHVBC9FhGdlKrxdiJJigb/j/68SIy3Te4Bkz" crossorigin="anonymous"></script>
    <script src="JS/perfil.js"></script>
</body>

</html>