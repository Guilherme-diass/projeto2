<?php
session_start();

// Verificar se o usuário está logado e se é um aluno ou paciente
if (!isset($_SESSION['user_id']) || !in_array($_SESSION['user_type'], ['aluno', 'paciente'])) {
    header("Location: login.php");
    exit();
}

include 'db_connection.php';

// Consulta para obter a lista de psicólogos
$sql_psicologos = "SELECT ID_psicologo, nome, email, telefone, descricao, foto_perfil FROM psicologo";
$result_psicologos = $conn->query($sql_psicologos);
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Psicólogos Cadastrados</title>
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
        h2 {
            margin-top: 0;
            margin-bottom: 20px;
        }
        table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 20px;
        }
        table, th, td {
            border: 1px solid #ddd;
            padding: 8px;
        }
        th {
            background-color: #f2f2f2;
        }
        button {
            padding: 8px 20px;
            background-color: #007BFF;
            color: #fff;
            border: none;
            border-radius: 4px;
            cursor: pointer;
        }
        button:hover {
            background-color: #0056b3;
        }
    </style>
    
</head>
<body>
    <div class="container">
        <h2>Psicólogos Cadastrados</h2>
        <table border="1">
            <tr>
                <th>Nome</th>
                <th>Email</th>
                <th>Telefone</th>
                <th>Descrição</th>
                <th>Foto</th>
            </tr>
            <?php while ($psicologo = $result_psicologos->fetch_assoc()): ?>
            <tr>
                <td><?php echo htmlspecialchars($psicologo['nome']); ?></td>
                <td><?php echo htmlspecialchars($psicologo['email']); ?></td>
                <td><?php echo htmlspecialchars($psicologo['telefone']); ?></td>
                <td><?php echo htmlspecialchars($psicologo['descricao']); ?></td>
                <td><img src="uploads/<?php echo htmlspecialchars($psicologo['foto_perfil']); ?>" alt="Foto de Perfil" width="50"></td>
            </tr>
            <?php endwhile; ?>
        </table>

        <form action="<?php echo $_SESSION['user_type'] === 'aluno' ? 'aluno_dashboard.php' : 'paciente_dashboard.php'; ?>" method="post">
            <button type="submit">Voltar</button>
        </form>
    </div>    
</body>
</html>

<?php
$conn->close();
?>
