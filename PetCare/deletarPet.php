<?php
require_once 'session_check.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    require 'conexaoBD.php';

    // Recebe os dados da requisição
    $data = json_decode(file_get_contents('php://input'), true);
    $petId = $data['idPet'] ?? null;
    $idTutor = $_SESSION['idTutor'];

    // Verifica se os dados foram recebidos corretamente
    if (is_null($petId) || !$idTutor) {
        echo json_encode(['status' => 'error', 'message' => 'Dados inválidos.']);
        exit();
    }

    // Prepara e executa a consulta para excluir o pet
    $sql = "DELETE FROM animal WHERE idPet = ? AND idTutor = ?";
    $stmt = $conn->prepare($sql);

    if (!$stmt) {
        echo json_encode(['status' => 'error', 'message' => 'Erro na preparação da consulta: ' . htmlspecialchars($conn->error)]);
        exit();
    }

    $stmt->bind_param("ii", $petId, $idTutor);
    $stmt->execute();

    // Retorna o status da operação
    if ($stmt->affected_rows > 0) {
        echo json_encode(['status' => 'success']);
    } else {
        echo json_encode(['status' => 'error', 'message' => 'Falha ao excluir o pet.']);
    }

    $stmt->close();
    $conn->close();
} else {
    echo json_encode(['status' => 'error', 'message' => 'Método inválido.']);
}
