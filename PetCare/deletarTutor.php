<?php
require_once 'session_check.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    require 'conexaoBD.php';

    $idTutor = $_SESSION['idTutor'];

    if (!$idTutor) {
        respondWithError('Dados inválidos.');
    }

    $conn->begin_transaction();

    try {
        deletePets($conn, $idTutor);
        deleteTutor($conn, $idTutor);

        $conn->commit();
        session_destroy();
        respondWithSuccess();
    } catch (Exception $e) {
        $conn->rollback();
        respondWithError($e->getMessage());
    }

    $conn->close();
} else {
    respondWithError('Método inválido.');
}

function deletePets($conn, $idTutor)
{
    $sql = "DELETE FROM animal WHERE idTutor = ?";
    $stmt = prepareStatement($conn, $sql, $idTutor);
    $stmt->execute();
    $stmt->close();
}

function deleteTutor($conn, $idTutor)
{
    $sql = "DELETE FROM tutor WHERE idTutor = ?";
    $stmt = prepareStatement($conn, $sql, $idTutor);
    $stmt->execute();
    $stmt->close();
}

function prepareStatement($conn, $sql, $idTutor)
{
    $stmt = $conn->prepare($sql);
    if ($stmt === false) {
        throw new Exception('Erro na preparação da consulta: ' . htmlspecialchars($conn->error));
    }
    $stmt->bind_param("i", $idTutor);
    return $stmt;
}

function respondWithError($message)
{
    echo json_encode(['status' => 'error', 'message' => $message]);
    exit();
}

function respondWithSuccess()
{
    echo json_encode(['status' => 'success']);
    exit();
}
