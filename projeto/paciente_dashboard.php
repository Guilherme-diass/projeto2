<?php
session_start();

// Verificar se o usuário está logado e se é um paciente
if (!isset($_SESSION['user_id']) || $_SESSION['user_type'] !== 'paciente') {
    header("Location: login.php");
    exit();
}

include 'db_connection.php';

// Obtém o ID do paciente a partir da sessão
$paciente_id = $_SESSION['user_id'];

// Consulta para obter as informações do paciente
$sql = "SELECT nome, email FROM Paciente WHERE ID_paciente = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("i", $paciente_id);
$stmt->execute();
$result = $stmt->get_result();
$paciente = $result->fetch_assoc();

// Consulta para obter as consultas do paciente
$sql_consultas = "SELECT Consulta.ID_consulta, Consulta.horario, Consulta.dia, Consulta.local, psicologo.nome AS psicologo_nome 
                  FROM Consulta 
                  JOIN psicologo ON Consulta.fk_psicologo_ID_psicologo = psicologo.ID_psicologo
                  WHERE Consulta.fk_Paciente_ID_paciente = ?";
$stmt_consultas = $conn->prepare($sql_consultas);
$stmt_consultas->bind_param("i", $paciente_id);
$stmt_consultas->execute();
$result_consultas = $stmt_consultas->get_result();
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard do Paciente</title>
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
        <h2>Bem-vindo, <?php echo htmlspecialchars($paciente['nome']); ?></h2>
        <p>Email: <?php echo htmlspecialchars($paciente['email']); ?></p>

        <h3>Suas Consultas</h3>
        <table border="1">
            <tr>
                <th>Data</th>
                <th>Hora</th>
                <th>Local</th>
                <th>Psicólogo</th>
                <th>Ações</th>
            </tr>
            <?php while ($consulta = $result_consultas->fetch_assoc()): ?>
            <tr>
                <td><?php echo htmlspecialchars($consulta['dia']); ?></td>
                <td><?php echo htmlspecialchars($consulta['horario']); ?></td>
                <td><?php echo htmlspecialchars($consulta['local']); ?></td>
                <td><?php echo htmlspecialchars($consulta['psicologo_nome']); ?></td>
                <td>
                    <form action="cancelar_consulta.php" method="post" style="display:inline;">
                        <input type="hidden" name="cancelar_consulta_id" value="<?php echo $consulta['ID_consulta']; ?>">
                        <button type="submit">Cancelar</button>
                    </form>
                    <form action="editar_consulta_paciente.php" method="post" style="display:inline;">
                        <input type="hidden" name="editar_consulta_id" value="<?php echo $consulta['ID_consulta']; ?>">
                        <button type="submit">Editar</button>
                    </form>
                </td>
            </tr>
            <?php endwhile; ?>
        </table>

        <h3>Agendar Consulta</h3>
        <form action="agendar_consulta.php" method="post">
            <button type="submit">Agendar Consulta</button>
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
$stmt_consultas->close();
$conn->close();
?>
