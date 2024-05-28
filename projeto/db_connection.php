<?php
$servername = "localhost";
$username = "root";
$password = "PUC@1234";
$dbname = "kali";

// Criar conexão
$conn = new mysqli($servername, $username, $password, $dbname);

// Verificar conexão
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}
?>
