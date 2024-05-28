<?php
session_start();

// Verificar se o usuário está logado e se é um paciente
if (!isset($_SESSION['user_id']) || $_SESSION['user_type'] !== 'paciente') {
    header("Location: login.php");
    exit();
}

include 'db_connection.php';

if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['cancelar_consulta_id'])) {
    $consulta_id = $_POST['cancelar_consulta_id'];

    // Query para cancelar a consulta
    $sql_cancelar_consulta = $conn->prepare("DELETE FROM Consulta WHERE ID_consulta = ?");
    $sql_cancelar_consulta->bind_param("i", $consulta_id);
    if ($sql_cancelar_consulta->execute()) {
        header("Location: paciente_dashboard.php");
        exit();
    } else {
        echo "Erro ao cancelar a consulta: " . $conn->error;
    }

    $sql_cancelar_consulta->close();
}

$conn->close();
?>
