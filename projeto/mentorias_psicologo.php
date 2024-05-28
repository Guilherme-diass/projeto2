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

// Verifica se o ID da mentoria a ser cancelada foi enviado por POST
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['cancelar_mentoria'])) {
    $mentoria_id = $_POST['cancelar_mentoria'];

    // Query para cancelar a mentoria
    $sql_cancelar_mentoria = "DELETE FROM Mentoria WHERE ID_mentoria = $mentoria_id";
    if ($conn->query($sql_cancelar_mentoria) === TRUE) {
        // Mentoria cancelada com sucesso
        header("Location: psicologo_dashboard.php");
        exit();
    } else {
        echo "Erro ao cancelar a mentoria: " . $conn->error;
    }
}

// Obtém o ID do psicólogo logado
$user_id = $_SESSION['user_id'];

// Consulta para obter as mentorias marcadas com o psicólogo
$sql = "SELECT Mentoria.ID_mentoria, Mentoria.horario, Mentoria.dia, Mentoria.local, Aluno.nome AS aluno_nome
        FROM Mentoria
        JOIN Aluno ON Mentoria.fk_aluno_ID_aluno = Aluno.ID_aluno
        WHERE Mentoria.fk_psicologo_ID_psicologo = $user_id";
$result = $conn->query($sql);

$conn->close();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Mentorias Marcadas</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            background-color: #f0f0f0;
            margin: 0;
            padding: 0;
        }
        h1 {
            text-align: center;
            margin-top: 20px;
        }
        table {
            width: 80%;
            margin: 20px auto;
            border-collapse: collapse;
        }
        th, td {
            padding: 8px;
            text-align: left;
            border-bottom: 1px solid #ddd;
        }
        th {
            background-color: #f2f2f2;
        }
        form {
            text-align: center;
            margin-top: 20px;
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
        <h1>Mentorias Marcadas</h1>
        <table border="1">
            <tr>
                <th>Data</th>
                <th>Hora</th>
                <th>Local</th>
                <th>Aluno</th>
                <th>Cancelar</th>
            </tr>
            <?php while ($mentoria = $result->fetch_assoc()): ?>
            <tr>
                <td><?php echo htmlspecialchars($mentoria['dia']); ?></td>
                <td><?php echo htmlspecialchars($mentoria['horario']); ?></td>
                <td><?php echo htmlspecialchars($mentoria['local']); ?></td>
                <td><?php echo htmlspecialchars($mentoria['aluno_nome']); ?></td>
                <td>
                    <form action="" method="post">
                        <input type="hidden" name="cancelar_mentoria" value="<?php echo $mentoria['ID_mentoria']; ?>">
                        <button type="submit">Cancelar</button>
                    </form>
                </td>
            </tr>
            <?php endwhile; ?>
        </table>
        
        <form action="psicologo_dashboard.php" method="post">
            <button type="submit">Voltar</button>
        </form>
    </div>    
</body>
</html>
