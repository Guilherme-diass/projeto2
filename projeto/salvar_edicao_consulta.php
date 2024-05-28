<?php
session_start();

// Verificar se o usuário está logado e se é um paciente
if (!isset($_SESSION['user_id']) || $_SESSION['user_type'] !== 'paciente') {
    header("Location: login.php");
    exit();
}

include 'db_connection.php';

if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['consulta_id'])) {
    $consulta_id = $_POST['consulta_id'];
    $dia = $_POST['dia'];
    $horario = $_POST['horario'];
    $local = $_POST['local'];

    // Query para atualizar a consulta
    $sql_editar_consulta = $conn->prepare("UPDATE Consulta SET dia = ?, horario = ?, local = ? WHERE ID_consulta = ?");
    $sql_editar_consulta->bind_param("sssi", $dia, $horario, $local, $consulta_id);
    if ($sql_editar_consulta->execute()) {
        header("Location: paciente_dashboard.php");
        exit();
    } else {
        echo "Erro ao editar a consulta: " . $conn->error;
    }

    $sql_editar_consulta->close();
}

$conn->close();
?>
