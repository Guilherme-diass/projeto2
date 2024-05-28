<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            background-color: #f0f0f0;
            margin: 0;
            padding: 0;
            display: flex;
            justify-content: center;
            align-items: center;
            height: 100vh;
        }
        form {
            background-color: #fff;
            padding: 20px;
            border-radius: 8px;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
            width: 300px;
        }
        h2 {
            margin-top: 0;
        }
        label {
            display: block;
            margin: 10px 0 5px;
        }
        input[type="email"],
        input[type="password"],
        select {
            width: 100%;
            padding: 8px;
            margin-bottom: 10px;
            border: 1px solid #ccc;
            border-radius: 4px;
        }
        button {
            width: 100%;
            padding: 10px;
            background-color: #007BFF;
            color: #fff;
            border: none;
            border-radius: 4px;
            cursor: pointer;
            font-size: 16px;
        }
        button:hover {
            background-color: #0056b3;
        }
        p {
            margin-top: 10px;
        }
        p a {
            text-decoration: none;
            color: #007BFF;
        }
        p a button {
            width: auto;
            padding: 5px 10px;
            background-color: #28a745;
        }
        p a button:hover {
            background-color: #218838;
        }
    </style>
</head>
<body>
    <div class="container">
        <form action="login.php" method="post">
            <h2>Login</h2>
            <label for="email">Email:</label>
            <input type="email" id="email" name="email" required><br><br>

            <label for="password">Password:</label>
            <input type="password" id="password" name="password" required><br><br>

            <label for="user_type">Eu sou :</label>
            <select id="user_type" name="user_type" required>
                <option value="psicologo">Psicologo</option>
                <option value="paciente">Paciente</option>
                <option value="aluno">Aluno</option>
            </select><br><br>

            <button type="submit">Login</button>
        </form>
        <p>Não tem uma conta? <a href="cadastro.html"><button>Cadastre-se</button></a></p>
    </div>
</body>
</html>




<?php
session_start();
include 'db_connection.php';

// Verificar se os dados do formulário foram enviados
if (isset($_POST['email'], $_POST['password'], $_POST['user_type'])) {
    // Obtendo dados do formulário
    $email = $_POST['email'];
    $password = $_POST['password'];
    $user_type = $_POST['user_type'];

    // Proteção contra SQL Injection
    $email = $conn->real_escape_string($email);

    // Definir a consulta SQL de acordo com o tipo de usuário
    if ($user_type == 'psicologo') {
        $sql = "SELECT * FROM psicologo WHERE email=?";
    } elseif ($user_type == 'paciente') {
        $sql = "SELECT * FROM paciente WHERE email=?";
    } else { // aluno
        $sql = "SELECT * FROM aluno WHERE email=?";
    }

    // Usando prepared statement para evitar injeção de SQL
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("s", $email);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows > 0) {
        $user = $result->fetch_assoc();
        if (password_verify($password, $user['senha'])) {
            // Login bem-sucedido
            $_SESSION['user_id'] = $user_type == 'psicologo' ? $user['ID_psicologo'] : ($user_type == 'paciente' ? $user['ID_paciente'] : $user['ID_aluno']);
            $_SESSION['user_name'] = $user['nome'];
            $_SESSION['user_type'] = $user_type;

            // Redirecionar para a página de dashboard
            $dashboard = $user_type . "_dashboard.php";
            header("Location: $dashboard");
            exit();
        } else {
            echo "Invalid password.";
        }
    } else {
        echo "No user found with this email.";
    }

    // Fechar o statement
    $stmt->close();
} else {
    echo "";
}

// Fechar a conexão com o banco de dados
$conn->close();
?>


