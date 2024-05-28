<?php
session_start();

// Verificar se o usuário está logado e se é um aluno
if (!isset($_SESSION['user_id']) || $_SESSION['user_type'] !== 'aluno') {
    header("Location: login.php");
    exit();
}

include 'db_connection.php';

if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['cancelar_mentoria_id'])) {
    $mentoria_id = $_POST['cancelar_mentoria_id'];

    // Query para cancelar a mentoria
    $sql_cancelar_mentoria = $conn->prepare("DELETE FROM Mentoria WHERE ID_mentoria = ?");
    $sql_cancelar_mentoria->bind_param("i", $mentoria_id);
    if ($sql_cancelar_mentoria->execute()) {
        header("Location: aluno_dashboard.php");
        exit();
    } else {
        echo "Erro ao cancelar a mentoria: " . $conn->error;
    }

    $sql_cancelar_mentoria->close();
}

$conn->close();
?>
