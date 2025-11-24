<?php
require_once 'session_check.php';
require_once 'conexaobd.php';
$conn->set_charset("utf8mb4");

$pet = null;

// Função para preparar e executar consultas de forma segura
function prepareAndExecuteQuery($conn, $sql, $params, $types)
{
    $stmt = $conn->prepare($sql);
    if (!$stmt) {
        die("Erro na preparação da consulta: " . $conn->error . " - SQL: " . $sql);
    }
    $stmt->bind_param($types, ...$params);
    $stmt->execute();
    return $stmt;
}

// Obtém os dados do pet se o método for GET
if ($_SERVER["REQUEST_METHOD"] === "GET" && isset($_GET['id'])) {
    $petId = $_GET['id'];
    $idTutor = $_SESSION['idTutor'];

    $sql = "SELECT nomePet, dataNascimentoPet, especie, raca, sexo, microchip, castracao FROM animal WHERE idPet = ? AND idTutor = ?";
    $stmt = prepareAndExecuteQuery($conn, $sql, [$petId, $idTutor], "ii");
    $result = $stmt->get_result();
    $pet = $result->fetch_assoc();
    $stmt->close();
}

// Atualiza os dados do pet se o método for POST
if ($_SERVER["REQUEST_METHOD"] === "POST") {
    $idPet = htmlspecialchars($_POST['idPet']);
    $nomePet = htmlspecialchars($_POST['nomePet']);
    $dataNascimentoPet = htmlspecialchars($_POST['dataNascimentoPet']);
    $especie = htmlspecialchars($_POST['especie']);
    $raca = htmlspecialchars($_POST['raca']);
    $sexo = htmlspecialchars($_POST['sexo']);
    $microchip = htmlspecialchars($_POST['microchip']);
    $castracao = htmlspecialchars($_POST['castracao']);
    $idTutor = $_SESSION['idTutor'];

    $sql = "UPDATE animal SET nomePet = ?, dataNascimentoPet = ?, especie = ?, raca = ?, sexo = ?, microchip = ?, castracao = ? WHERE idPet = ? AND idTutor = ?";
    $stmt = prepareAndExecuteQuery($conn, $sql, [$nomePet, $dataNascimentoPet, $especie, $raca, $sexo, $microchip, $castracao, $idPet, $idTutor], "ssssiiiii");

    if ($stmt->execute()) {
        header("Location: perfil.php?id=" . $idPet);
        exit();
    } else {
        echo "Erro ao atualizar os dados do pet: " . $stmt->error;
    }
    $stmt->close();
}
?>

<!DOCTYPE html>
<html lang="pt-br">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Atualizar Cadastro de Pet</title>
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
            <h2 class="card-title text-center">Atualizar Cadastro do Pet</h2>
            <?php if ($pet): ?>
                <form method="post" action="<?= htmlspecialchars($_SERVER["PHP_SELF"]); ?>">
                    <input type="hidden" name="idPet" value="<?= htmlspecialchars($petId); ?>">
                    <div class="form-group">
                        <label for="nome">Nome:</label>
                        <input type="text" id="nome" name="nomePet" class="form-control" value="<?= htmlspecialchars($pet['nomePet']); ?>" required>
                    </div>
                    <div class="form-group">
                        <label for="dataNascimentoPet">Data de Nascimento:</label>
                        <input type="date" id="dataNascimentoPet" name="dataNascimentoPet" class="form-control" value="<?= htmlspecialchars($pet['dataNascimentoPet']); ?>" required>
                    </div>
                    <div class="form-group">
                        <label for="especie">Espécie:</label>
                        <select id="especie" name="especie" class="form-control" required>
                            <option value="Cão" <?= $pet['especie'] === 'Cão' ? 'selected' : ''; ?>>Cão</option>
                            <option value="Gato" <?= $pet['especie'] === 'Gato' ? 'selected' : ''; ?>>Gato</option>
                        </select>
                    </div>
                    <div class="form-group">
                        <label for="raca">Raça:</label>
                        <select id="raca" name="raca" class="form-control" required></select>
                        <input type="text" id="outraRaca" name="outraRaca" class="form-control mt-2" placeholder="Especifique a raça" style="display: none;">
                    </div>
                    <div class="form-group">
                        <label for="sexo">Sexo:</label>
                        <select id="sexo" name="sexo" class="form-control">
                            <option value="1" <?= $pet['sexo'] ? 'selected' : ''; ?>>Macho</option>
                            <option value="0" <?= !$pet['sexo'] ? 'selected' : ''; ?>>Fêmea</option>
                        </select>
                    </div>
                    <div class="form-group">
                        <label for="microchip">Microchip:</label>
                        <select id="microchip" name="microchip" class="form-control">
                            <option value="1" <?= $pet['microchip'] ? 'selected' : ''; ?>>Sim</option>
                            <option value="0" <?= !$pet['microchip'] ? 'selected' : ''; ?>>Não</option>
                        </select>
                    </div>
                    <div class="form-group">
                        <label for="castracao">Castrado:</label>
                        <select id="castracao" name="castracao" class="form-control">
                            <option value="1" <?= $pet['castracao'] ? 'selected' : ''; ?>>Sim</option>
                            <option value="0" <?= !$pet['castracao'] ? 'selected' : ''; ?>>Não</option>
                        </select>
                    </div>
                    <div class="d-flex justify-content-center">
                        <button type="submit" class="btn btn-success mx-2"><i class="bi bi-check-circle"></i> Atualizar Pet</button>
                        <a href="perfil.php" class="btn btn-secondary mx-2"><i class="bi bi-arrow-left"></i> Voltar</a>
                    </div>
                </form>
            <?php else: ?>
                <div class="alert alert-danger">Pet não encontrado ou você não tem permissão para atualizar este pet.</div>
            <?php endif; ?>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"
        integrity="sha384-geWF76RCwLtnZ8qwWowPQNguL3RmwHVBC9FhGdlKrxdiJJigb/j/68SIy3Te4Bkz"
        crossorigin="anonymous"></script>

    <script>
        document.querySelector('.menu-toggle').addEventListener('click', function() {
            document.querySelector('.sidebar').classList.toggle('active');
        });
    </script>
    <script src="JS/script.js"></script>
    <script>
        // Atualiza as opções de raça com base na espécie selecionada
        document.addEventListener("DOMContentLoaded", function() {
            const especie = document.getElementById("especie").value;
            updateRacas(especie);
        });
    </script>
</body>

</html>