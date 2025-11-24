<?php
session_start();
if (!isset($_SESSION['idTutor'])) {
    header("Location: index.php");
    exit();
}
$idTutor = $_SESSION['idTutor'];
?>

<!DOCTYPE html>
<html lang="pt-br">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Cadastro de Pet</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" integrity="sha384-9ndCyUaIbzAi2FUVXJi0CjmCapSmO7SnpJef0486qhLnuZ2cdeRhO02iuK6FUUVM" crossorigin="anonymous">
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
            <h2 class="card-title text-center">Cadastro do Pet</h2>

            <?php
            $nomePet = $dataNascimentoPet = $especie = $raca = $sexo = $microchip = $castracao = "";
            if ($_SERVER["REQUEST_METHOD"] === "POST") {
                require_once 'conexaobd.php';
                $conn->set_charset("utf8mb4");

                $nomePet = $_POST['nomePet'];
                $dataNascimentoPet = $_POST['dataNascimentoPet'];
                $especie = $_POST['especie'];
                $raca = $_POST['raca'] === "Outros" ? $_POST['hiddenOutraRaca'] : $_POST['raca'];
                $sexo = ($_POST['sexo'] === "true") ? 1 : 0;
                $microchip = ($_POST['microchip'] === "true") ? 1 : 0;
                $castracao = ($_POST['castracao'] === "true") ? 1 : 0;

                $dataNascimento = new DateTime($dataNascimentoPet);
                $hoje = new DateTime();
                $dataMaxima = (new DateTime())->modify('-25 years');

                if ($dataNascimento > $hoje || $dataNascimento < $dataMaxima) {
                    die("Data de nascimento inválida. O animal deve ter no máximo 25 anos.");
                }

                $stmt = $conn->prepare("INSERT INTO animal (nomePet, dataNascimentoPet, especie, raca, sexo, microchip, castracao, idTutor) VALUES (?, ?, ?, ?, ?, ?, ?, ?)");
                if (!$stmt) {
                    die("Erro na preparação da consulta: " . $conn->error);
                }

                $stmt->bind_param("ssssiiii", $nomePet, $dataNascimentoPet, $especie, $raca, $sexo, $microchip, $castracao, $idTutor);

                if ($stmt->execute()) {
                    echo "<div class='alert alert-success'>Novo registro criado com sucesso. Aguarde...</div>";
                    echo "<script>setTimeout(() => window.location.href = 'perfil.php', 1000);</script>";
                } else {
                    echo "<div class='alert alert-danger'>Erro: " . $stmt->error . "</div>";
                }

                $stmt->close();
                $conn->close();
            }
            ?>

            <form id="cadastroPetForm" action="" method="post">
                <input type="hidden" name="idTutor" value="<?= htmlspecialchars($idTutor) ?>">
                <input type="hidden" id="hiddenOutraRaca" name="hiddenOutraRaca" value="">
                <div class="form-group">
                    <label for="nome">Nome:</label>
                    <input type="text" id="nome" name="nomePet" class="form-control" value="<?= htmlspecialchars($nomePet) ?>" required>
                </div>
                <div class="form-group">
                    <label for="dataNascimentoPet">Data de Nascimento:</label>
                    <input type="date" id="dataNascimentoPet" name="dataNascimentoPet" class="form-control" value="<?= htmlspecialchars($dataNascimentoPet) ?>" required>
                </div>
                <div class="form-group">
                    <label for="especie">Espécie:</label>
                    <select id="especie" name="especie" class="form-control" required>
                        <option value="Cão" <?= $especie === "Cão" ? "selected" : "" ?>>Cão</option>
                        <option value="Gato" <?= $especie === "Gato" ? "selected" : "" ?>>Gato</option>
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
                        <option value="true" <?= $sexo ? "selected" : "" ?>>Macho</option>
                        <option value="false" <?= !$sexo ? "selected" : "" ?>>Fêmea</option>
                    </select>
                </div>
                <div class="form-group">
                    <label for="microchip">Microchip:</label>
                    <select id="microchip" name="microchip" class="form-control">
                        <option value="true" <?= $microchip ? "selected" : "" ?>>Sim</option>
                        <option value="false" <?= !$microchip ? "selected" : "" ?>>Não</option>
                    </select>
                </div>
                <div class="form-group">
                    <label for="castracao">Castrado:</label>
                    <select id="castracao" name="castracao" class="form-control">
                        <option value="true" <?= $castracao ? "selected" : "" ?>>Sim</option>
                        <option value="false" <?= !$castracao ? "selected" : "" ?>>Não</option>
                    </select>
                </div>
                <div class="d-flex justify-content-center">
                    <button type="submit" class="btn btn-success mx-2">
                        <i class="bi bi-check-circle"></i> Cadastrar Pet
                    </button>
                    <a href="perfil.php" class="btn btn-secondary mx-2">
                        <i class="bi bi-arrow-left"></i> Voltar
                    </a>
                </div>
            </form>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js" integrity="sha384-geWF76RCwLtnZ8qwWowPQNguL3RmwHVBC9FhGdlKrxdiJJigb/j/68SIy3Te4Bkz" crossorigin="anonymous"></script>
    <script src="JS/script.js"></script>
</body>

</html>