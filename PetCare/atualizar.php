<?php
require_once 'session_check.php';
require_once 'conexaobd.php'; // Conexão com o banco de dados

$idTutor = $_SESSION['idTutor'];

if ($_SERVER["REQUEST_METHOD"] === "POST") {
    $id = htmlspecialchars($_POST['id']);
    $nome = htmlspecialchars($_POST['nome']);
    $email = htmlspecialchars($_POST['email']);
    $senha = htmlspecialchars($_POST['senha']);
    $confirmaSenha = htmlspecialchars($_POST['confirmaSenha']);

    // Verifica se a senha foi preenchida e se as senhas coincidem
    if (!empty($senha) && !empty($confirmaSenha)) {
        if ($senha !== $confirmaSenha) {
            $_SESSION['error_message'] = "As senhas não coincidem.";
            header("Location: atualizar.php");
            exit();
        }
        $sql = "UPDATE tutor SET nomeTutor = ?, email = ?, senha = ? WHERE idTutor = ?";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("sssi", $nome, $email, $senha, $id);
    } else {
        // Atualiza sem alterar a senha
        $sql = "UPDATE tutor SET nomeTutor = ?, email = ? WHERE idTutor = ?";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("ssi", $nome, $email, $id);
    }

    if ($stmt && $stmt->execute()) {
        header("Location: perfil.php?id=" . $id);
        exit();
    } else {
        $_SESSION['error_message'] = "Erro ao atualizar os dados: " . ($stmt ? $stmt->error : $conn->error);
        header("Location: atualizar.php");
        exit();
    }

    $stmt->close();
    $conn->close();
}

// Verifica a conexão
if ($conn->connect_error) {
    die("Falha na conexão: " . $conn->connect_error);
}

// Obtém os dados do tutor
$sqlTutor = "SELECT nomeTutor, email FROM tutor WHERE idTutor = ?";
$stmtTutor = $conn->prepare($sqlTutor);
$stmtTutor->bind_param("i", $idTutor);
$stmtTutor->execute();
$resultTutor = $stmtTutor->get_result();
$tutor = $resultTutor->fetch_assoc();
$stmtTutor->close();
?>

<!DOCTYPE html>
<html lang="pt-br">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Atualizar cadastro</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@4.6.2/dist/css/bootstrap.min.css">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.5/font/bootstrap-icons.css">
    <link rel="stylesheet" href="https://fonts.googleapis.com/css2?family=Roboto:wght@400;700&display=swap">
    <link rel="stylesheet" href="./CSS/index.css">
</head>

<body>
    <div class="container container-centered">
        <div class="row justify-content-center align-items-center vh-100">
            <div class="col-md-6 col-lg-4">
                <div class="card">
                    <div class="card-body">
                        <img src="imgs/logo/logo_petcare.png" alt="Logo Pet Care" class="logo">
                        <h1 class="text-center">Atualizar Cadastro</h1>
                        <?php if (isset($_SESSION['error_message'])): ?>
                            <div class='alert alert-danger text-center'><?= $_SESSION['error_message']; ?></div>
                            <?php unset($_SESSION['error_message']); ?>
                        <?php endif; ?>
                        <form method="post" action="atualizar.php">
                            <input type="hidden" name="id" value="<?= htmlspecialchars($idTutor); ?>">
                            <div class="form-group">
                                <label for="nome">Nome:</label>
                                <input type="text" id="nome" name="nome" class="form-control" value="<?= htmlspecialchars($tutor['nomeTutor']); ?>" required>
                            </div>
                            <div class="form-group">
                                <label for="email">E-mail:</label>
                                <input type="email" id="email" name="email" class="form-control" value="<?= htmlspecialchars($tutor['email']); ?>" required>
                            </div>
                            <div class="form-group">
                                <label for="senha">Senha:</label>
                                <input type="password" id="senha" name="senha" class="form-control">
                            </div>
                            <div class="form-group">
                                <label for="confirmaSenha">Confirme sua senha:</label>
                                <input type="password" id="confirmaSenha" name="confirmaSenha" class="form-control">
                            </div>
                            <div class="d-flex justify-content-center">
                                <button type="submit" class="btn btn-success mx-2">
                                    <i class="bi bi-check-circle"></i> Atualizar
                                </button>
                                <a href="perfil.php" class="btn btn-secondary mx-2">
                                    <i class="bi bi-arrow-left"></i> Voltar
                                </a>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <script src="JS/script.js"></script>
</body>

</html>