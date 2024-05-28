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

// Verifica se o ID da consulta a ser cancelada foi enviado por POST
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['cancelar_consulta'])) {
    $consulta_id = $_POST['cancelar_consulta'];

    // Query para cancelar a consulta
    $sql_cancelar_consulta = "DELETE FROM Consulta WHERE ID_consulta = $consulta_id";
    if ($conn->query($sql_cancelar_consulta) === TRUE) {
        // Consulta cancelada com sucesso
        header("Location: psicologo_dashboard.php");
        exit();
    } else {
        echo "Erro ao cancelar a consulta: " . $conn->error;
    }
}

// Obtém o ID do psicólogo logado
$user_id = $_SESSION['user_id'];

// Consulta para obter as consultas marcadas com o psicólogo
$sql = "SELECT Consulta.ID_consulta, Consulta.horario, Consulta.dia, Consulta.local, Paciente.nome AS paciente_nome
        FROM Consulta
        JOIN Paciente ON Consulta.fk_Paciente_ID_paciente = Paciente.ID_paciente
        WHERE Consulta.fk_psicologo_ID_psicologo = $user_id";
$result = $conn->query($sql);

$conn->close();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Consultas Marcadas</title>
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
    <h1>Consultas Marcadas</h1>
    <table border="1">
        <tr>
            <th>Data</th>
            <th>Hora</th>
            <th>Local</th>
            <th>Paciente</th>
            <th>Cancelar</th>
        </tr>
        <?php while ($consulta = $result->fetch_assoc()): ?>
        <tr>
            <td><?php echo htmlspecialchars($consulta['dia']); ?></td>
            <td><?php echo htmlspecialchars($consulta['horario']); ?></td>
            <td><?php echo htmlspecialchars($consulta['local']); ?></td>
            <td><?php echo htmlspecialchars($consulta['paciente_nome']); ?></td>
            <td>
                <form action="" method="post">
                    <input type="hidden" name="cancelar_consulta" value="<?php echo $consulta['ID_consulta']; ?>">
                    <button type="submit">Cancelar</button>
                </form>
            </td>
        </tr>
        <?php endwhile; ?>
    </table>
    
    <form action="psicologo_dashboard.php" method="post">
        <button type="submit">Voltar</button>
    </form>
</body>
</html>
