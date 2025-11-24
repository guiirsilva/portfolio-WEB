<?php
$servername = "localhost";
$username = "root";
$password = "usbw";
$dbname = "petcare";

// Cria a conexão
$conn = new mysqli($servername, $username, $password, $dbname);

// Define o conjunto de caracteres para utf8
$conn->set_charset("utf8");

// Verifica a conexão
if ($conn->connect_error) {
    die("Conexão falhou: " . $conn->connect_error);
}
