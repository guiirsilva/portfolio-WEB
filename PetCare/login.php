<?php
session_start();
require_once 'conexaobd.php'; // Conexão com o banco de dados

$email = '';
$senha = '';
$error_message = '';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {

    $email = $_POST['email'];
    $senha = $_POST['senha'];

    $sql = "SELECT idTutor FROM tutor WHERE email = ? AND senha = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("ss", $email, $senha);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows > 0) {
        $row = $result->fetch_assoc();
        $_SESSION['idTutor'] = $row['idTutor'];
        header("Location: perfil.php");
        exit();
    } else {
        $error_message = "E-mail ou senha inválidos!";
    }

    $stmt->close();
    $conn->close();
}
?>