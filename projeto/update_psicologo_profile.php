<?php
session_start();

// Verifica se o usuário está logado e se é um psicólogo
if (!isset($_SESSION['user_id']) || $_SESSION['user_type'] !== 'psicologo') {
    header("Location: index.html");
    exit();
}

// Inclui a conexão com o banco de dados
include 'db_connection.php';

// Obtém os dados do formulário
$user_id = $_SESSION['user_id'];

$updates = [];

if (!empty($_POST['nome'])) {
    $nome = $conn->real_escape_string($_POST['nome']);
    $updates[] = "nome='$nome'";
}

if (!empty($_POST['email'])) {
    $email = $conn->real_escape_string($_POST['email']);
    $updates[] = "email='$email'";
}

if (!empty($_POST['descricao'])) {
    $descricao = $conn->real_escape_string($_POST['descricao']);
    $updates[] = "descricao='$descricao'";
}


if (!empty($_POST['telefone'])) {
    $telefone = $conn->real_escape_string($_POST['telefone']);
    $updates[] = "telefone='$telefone'";
}

// Verifica se uma nova foto de perfil foi enviada
if (!empty($_FILES['foto_perfil']['name'])) {
    $target_dir = "uploads/";
    $target_file = $target_dir . basename($_FILES["foto_perfil"]["name"]);
    if (move_uploaded_file($_FILES["foto_perfil"]["tmp_name"], $target_file)) {
        $foto_perfil = $conn->real_escape_string(basename($_FILES["foto_perfil"]["name"]));
        $updates[] = "foto_perfil='$foto_perfil'";
    } else {
        echo "Erro ao fazer upload da foto de perfil.";
        exit();
    }
}

if (!empty($updates)) {
    $sql = "UPDATE psicologo SET " . implode(", ", $updates) . " WHERE ID_psicologo=$user_id";

    if ($conn->query($sql) === TRUE) {
        echo "Perfil atualizado com sucesso!";
    } else {
        echo "Erro ao atualizar o perfil: " . $conn->error;
    }
} else {
    echo "Nenhuma alteração foi feita.";
}

$conn->close();

// Redirecionar de volta para o dashboard
header("Location: psicologo_dashboard.php");
exit();
?>
