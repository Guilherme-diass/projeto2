<?php
session_start();

// Verificar se o usuário está logado e se é um aluno
if (!isset($_SESSION['user_id']) || $_SESSION['user_type'] !== 'aluno') {
    header("Location: login.php");
    exit();
}

include 'db_connection.php';

if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['mentoria_id'])) {
    $mentoria_id = $_POST['mentoria_id'];
    $dia = $_POST['dia'];
    $horario = $_POST['horario'];
    $local = $_POST['local'];

    // Query para atualizar a mentoria
    $sql_editar_mentoria = $conn->prepare("UPDATE Mentoria SET dia = ?, horario = ?, local = ? WHERE ID_mentoria = ?");
    $sql_editar_mentoria->bind_param("sssi", $dia, $horario, $local, $mentoria_id);
    if ($sql_editar_mentoria->execute()) {
        header("Location: aluno_dashboard.php");
        exit();
    } else {
        echo "Erro ao editar a mentoria: " . $conn->error;
    }

    $sql_editar_mentoria->close();
}

$conn->close();
?>
