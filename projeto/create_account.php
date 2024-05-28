<?php
include 'db_connection.php';

// Inicializa variáveis
$name = '';
$email = '';
$password = '';
$hashed_password = '';
$user_type = $_POST['user_type'] ?? '';

if ($user_type === 'paciente') {
    // Recupera e sanitiza os dados do paciente
    $name = $conn->real_escape_string($_POST['paciente_name'] ?? '');
    $email = $conn->real_escape_string($_POST['paciente_email'] ?? '');
    $password = $conn->real_escape_string($_POST['paciente_password'] ?? '');
} elseif ($user_type === 'aluno') {
    // Recupera e sanitiza os dados do aluno
    $name = $conn->real_escape_string($_POST['aluno_name'] ?? '');
    $email = $conn->real_escape_string($_POST['aluno_email'] ?? '');
    $password = $conn->real_escape_string($_POST['aluno_password'] ?? '');
} elseif ($user_type === 'psicologo') {
    // Recupera e sanitiza os dados do psicólogo
    $name = $conn->real_escape_string($_POST['psicologo_name'] ?? '');
    $email = $conn->real_escape_string($_POST['psicologo_email'] ?? '');
    $password = $conn->real_escape_string($_POST['psicologo_password'] ?? '');
    $crp = $conn->real_escape_string($_POST['psicologo_crp'] ?? '');
}

// Hash da senha
$hashed_password = password_hash($password, PASSWORD_DEFAULT);

// Inicializa a variável SQL
$sql = "";

if ($user_type === 'psicologo') {
    // Cria a query para inserir o psicólogo
    $sql = "INSERT INTO psicologo (nome, email, senha, crp) VALUES ('$name', '$email', '$hashed_password', '$crp')";
} elseif ($user_type === 'paciente') {
    // Cria a query para inserir o paciente
    $sql = "INSERT INTO Paciente (nome, email, senha) VALUES ('$name', '$email', '$hashed_password')";
} elseif ($user_type === 'aluno') {
    // Cria a query para inserir o aluno
    $sql = "INSERT INTO Aluno (nome, email, senha) VALUES ('$name', '$email', '$hashed_password')";
}

// Executa a query e verifica o resultado
if ($conn->query($sql) === TRUE) {
    // Redireciona para a página de login após o cadastro bem-sucedido
    header("Location: login.php");
    exit();
} else {
    // Exibe uma mensagem de erro se a inserção falhar
    echo "Error: " . $sql . "<br>" . $conn->error;
}

// Fecha a conexão com o banco de dados
$conn->close();
?>
