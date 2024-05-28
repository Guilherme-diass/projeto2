<?php
session_start();

// Verificar se o usuário está logado e se é um aluno
if (!isset($_SESSION['user_id']) || $_SESSION['user_type'] !== 'aluno') {
    header("Location: login.php");
    exit();
}

include 'db_connection.php';

// Obtém o ID do aluno a partir da sessão
$aluno_id = $_SESSION['user_id'];

// Consulta para obter as informações do aluno
$sql = "SELECT nome, email FROM Aluno WHERE ID_aluno = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("i", $aluno_id);
$stmt->execute();
$result = $stmt->get_result();
$aluno = $result->fetch_assoc();

// Consulta para obter as mentorias do aluno
$sql_mentorias = "SELECT Mentoria.ID_mentoria, Mentoria.horario, Mentoria.dia, Mentoria.local, psicologo.nome AS psicologo_nome 
                  FROM Mentoria 
                  JOIN psicologo ON Mentoria.fk_psicologo_ID_psicologo = psicologo.ID_psicologo
                  WHERE Mentoria.fk_Aluno_ID_aluno = ?";
$stmt_mentorias = $conn->prepare($sql_mentorias);
$stmt_mentorias->bind_param("i", $aluno_id);
$stmt_mentorias->execute();
$result_mentorias = $stmt_mentorias->get_result();
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard do Aluno</title>
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
        }
        table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 20px;
        }
        table, th, td {
            border: 1px solid #ccc;
        }
        th, td {
            padding: 8px;
            text-align: left;
        }
        th {
            background-color: #f2f2f2;
        }
        form {
            display: inline;
        }
        button {
            padding: 5px 10px;
            background-color: #007BFF;
            color: #fff;
            border: none;
            border-radius: 4px;
            cursor: pointer;
        }
        button:hover {
            background-color: #0056b3;
        }
        a {
            text-decoration: none;
            color: #007BFF;
        }
        a:hover {
            text-decoration: underline;
        }
    </style>
</head>
<body>
    <div class="container">
        <h2>Bem-vindo, <?php echo htmlspecialchars($aluno['nome']); ?></h2>
        <p>Email: <?php echo htmlspecialchars($aluno['email']); ?></p>

        <h3>Suas Mentorias</h3>
        <table border="1">
            <tr>
                <th>Data</th>
                <th>Hora</th>
                <th>Local</th>
                <th>Psicólogo</th>
                <th>Ações</th>
            </tr>
            <?php while ($mentoria = $result_mentorias->fetch_assoc()): ?>
            <tr>
                <td><?php echo htmlspecialchars($mentoria['dia']); ?></td>
                <td><?php echo htmlspecialchars($mentoria['horario']); ?></td>
                <td><?php echo htmlspecialchars($mentoria['local']); ?></td>
                <td><?php echo htmlspecialchars($mentoria['psicologo_nome']); ?></td>
                <td>
                    <form action="cancelar_mentoria.php" method="post" style="display:inline;">
                        <input type="hidden" name="cancelar_mentoria_id" value="<?php echo $mentoria['ID_mentoria']; ?>">
                        <button type="submit">Cancelar</button>
                    </form>
                    <form action="editar_mentoria.php" method="post" style="display:inline;">
                        <input type="hidden" name="editar_mentoria_id" value="<?php echo $mentoria['ID_mentoria']; ?>">
                        <button type="submit">Editar</button>
                    </form>
                </td>
            </tr>
            <?php endwhile; ?>
        </table>

        <h3>Agendar Mentoria</h3>
        <form action="agendar_mentoria.php" method="post">
            <button type="submit">Agendar Mentoria</button>
        </form>

        <h3>Psicólogos Cadastrados</h3>
        <form action="lista_psicologos.php" method="post">
            <button type="submit">Ver Psicólogos</button>
        </form>

        <p><a href="logout.php">Logout</a></p>
    </div>    
</body>
</html>

<?php
// Fechar as declarações e a conexão
$stmt->close();
$stmt_mentorias->close();
$conn->close();
?>
