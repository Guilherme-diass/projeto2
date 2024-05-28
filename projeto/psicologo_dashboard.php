<?php
session_start();

// Verifica se o usuário está logado e se é um psicólogo
if (!isset($_SESSION['user_id']) || $_SESSION['user_type'] !== 'psicologo') {
    // Redirecionar para a página de login se não estiver logado ou não for psicólogo
    header("Location: index.html");
    exit();
}

// Inclui a conexão com o banco de dados
include 'db_connection.php';

// Obtém os dados do psicólogo logado
$user_id = $_SESSION['user_id'];
$sql = "SELECT * FROM psicologo WHERE ID_psicologo = $user_id"; // Usando 'ID_psicologo' como o nome correto da coluna
$result = $conn->query($sql);

if ($result->num_rows > 0) {
    $row = $result->fetch_assoc();
    $nome = $row['nome'];
    $email = $row['email'];
    $descricao = isset($row['descricao']) ? $row['descricao'] : '';
    $telefone = isset($row['telefone']) ? $row['telefone'] : '';
    $foto_perfil = isset($row['foto_perfil']) ? $row['foto_perfil'] : ''; // Supondo que exista uma coluna para a foto de perfil
} else {
    echo "Erro ao carregar os dados do perfil.";
    exit();
}

$conn->close();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Psicólogo Dashboard</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            background-color: #f0f0f0;
            margin: 0;
            padding: 0;
        }
        .container {
            max-width: 800px;
            margin: 20px auto;
            padding: 20px;
            background-color: #fff;
            border-radius: 8px;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
        }
        h1 {
            margin-top: 0;
        }
        form {
            margin-top: 20px;
        }
        label {
            display: block;
            margin-bottom: 5px;
        }
        input[type="text"],
        input[type="email"],
        textarea,
        button[type="submit"],
        input[type="file"] {
            width: 100%;
            padding: 8px;
            margin-bottom: 10px;
            border: 1px solid #ccc;
            border-radius: 4px;
        }
        button[type="submit"] {
            background-color: #007BFF;
            color: #fff;
            border: none;
            border-radius: 4px;
            cursor: pointer;
        }
        button[type="submit"]:hover {
            background-color: #0056b3;
        }
        img {
            max-width: 100%;
            height: auto;
            margin-bottom: 10px;
        }
        a {
            color: #007BFF;
            text-decoration: none;
        }
        a:hover {
            text-decoration: underline;
        }
    </style>
</head>
<body>
    <div class="container">
        <h1>Bem-vindo, <?php echo $_SESSION['user_name']; ?></h1>
        <form action="update_psicologo_profile.php" method="post" enctype="multipart/form-data">
            <label for="nome">Nome:</label>
            <input type="text" id="nome" name="nome" value="<?php echo htmlspecialchars($nome); ?>"><br>

            <label for="email">Email:</label>
            <input type="email" id="email" name="email" value="<?php echo htmlspecialchars($email); ?>"><br>

            <label for="descricao">Descrição:</label>
            <textarea id="descricao" name="descricao"><?php echo htmlspecialchars($descricao); ?></textarea><br>

            <label for="telefone">Telefone:</label>
            <input type="text" id="telefone" name="telefone" value="<?php echo htmlspecialchars($telefone); ?>"><br>

            <label for="foto_perfil">Foto de Perfil:</label>
            <input type="file" id="foto_perfil" name="foto_perfil"><br>
            <?php if ($foto_perfil): ?>
                <img src="uploads/<?php echo htmlspecialchars($foto_perfil); ?>" alt="Foto de Perfil" width="100"><br>
            <?php endif; ?>

            <button type="submit">Atualizar Perfil</button>
        </form>
        <h3>Mentorias</h3>
        <form action="mentorias_psicologo.php" method="post">
        <button type="submit">Ver mentorias</button>
        </form>
        <h3>Consultas</h3>
        <form action="consultas_psicologo.php" method="post">
        <button type="submit">Ver consultas</button>
        </form>
        <br>
        <a href="logout.php">Logout</a><br>
    </div>
</body>
</html>
