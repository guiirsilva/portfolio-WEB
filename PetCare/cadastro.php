<?php
session_start();
require_once 'conexaobd.php';
?>

<!DOCTYPE html>
<html lang="pt-br">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Cadastro</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@4.6.2/dist/css/bootstrap.min.css">
    <link rel="stylesheet" href="https://fonts.googleapis.com/css2?family=Roboto:wght@400;700&display=swap">
    <link rel="stylesheet" href="./CSS/index.css">
</head>

<body>

    <?php
    $nomeTutor = $email = $senha = $confirmaSenha = $error_message = '';

    if ($_SERVER["REQUEST_METHOD"] === "POST") {
        $nomeTutor = $_POST['nomeTutor'];
        $email = $_POST['email'];
        $senha = $_POST['senha'];
        $confirmaSenha = $_POST['confirmaSenha'];

        $stmt = $conn->prepare("SELECT idTutor FROM tutor WHERE email = ?");
        $stmt->bind_param("s", $email);
        $stmt->execute();
        $result = $stmt->get_result();

        if ($result && $result->num_rows > 0) {
            $error_message = "E-mail já cadastrado.";
        } elseif ($senha !== $confirmaSenha) {
            $error_message = "As senhas não coincidem.";
        } else {
            $stmt = $conn->prepare("INSERT INTO tutor (nomeTutor, email, senha) VALUES (?, ?, ?)");
            $stmt->bind_param("sss", $nomeTutor, $email, $senha);

            if ($stmt->execute()) {
                $_SESSION['idTutor'] = $stmt->insert_id;
                echo "<div class='alert alert-success'>Novo registro criado com sucesso. Aguarde...</div>";
                echo "<script>
                    setTimeout(() => window.location.href = 'index.php', 2000);
                  </script>";
                $nomeTutor = $email = $senha = $confirmaSenha = '';
            } else {
                $error_message = "Erro: " . $stmt->error;
            }
        }
        $stmt->close();
        $conn->close();
    }
    ?>

    <div class="container container-centered">
        <div class="row justify-content-center align-items-center vh-100">
            <div class="col-md-6 col-lg-4">
                <div class="card">
                    <div class="card-body">
                        <img src="imgs/logo/logo_petcare.png" alt="Logo Pet Care" class="logo">
                        <h1 class="text-center">Cadastro</h1>
                        <?php if ($error_message): ?>
                            <div class="alert alert-danger text-center"><?= htmlspecialchars($error_message) ?></div>
                        <?php endif; ?>
                        <form method="post" action="<?= htmlspecialchars($_SERVER["PHP_SELF"]) ?>">
                            <div class="form-group">
                                <label for="nomeTutor">Nome:</label>
                                <input type="text" id="nomeTutor" name="nomeTutor" class="form-control" value="<?= htmlspecialchars($nomeTutor) ?>" required>
                            </div>
                            <div class="form-group">
                                <label for="email">E-mail:</label>
                                <input type="email" id="email" name="email" class="form-control" value="<?= htmlspecialchars($email) ?>" autocomplete="off" required>
                            </div>
                            <div class="form-group">
                                <label for="senha">Senha:</label>
                                <input type="password" id="senha" name="senha" class="form-control" value="<?= htmlspecialchars($senha) ?>" autocomplete="off" required>
                            </div>
                            <div class="form-group">
                                <label for="confirmaSenha">Confirme sua senha:</label>
                                <input type="password" id="confirmaSenha" name="confirmaSenha" class="form-control" value="<?= htmlspecialchars($confirmaSenha) ?>" required>
                            </div>
                            <button type="submit" class="btn btn-success btn-block">Cadastrar</button>
                            <p class="text-center mt-3">Já tem uma conta? <a href="index.php" class="paginaLogin">Clique aqui!</a></p>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script src="JS/script.js"></script>
</body>

</html>