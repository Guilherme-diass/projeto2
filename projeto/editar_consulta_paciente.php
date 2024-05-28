<?php
session_start();

// Verificar se o usuário está logado e se é um paciente
if (!isset($_SESSION['user_id']) || $_SESSION['user_type'] !== 'paciente') {
    header("Location: login.php");
    exit();
}

include 'db_connection.php';

if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['editar_consulta_id'])) {
    $consulta_id = $_POST['editar_consulta_id'];

    // Consulta para obter os dados da consulta
    $sql = $conn->prepare("SELECT dia, horario, local FROM Consulta WHERE ID_consulta = ?");
    $sql->bind_param("i", $consulta_id);
    $sql->execute();
    $result = $sql->get_result();
    $consulta = $result->fetch_assoc();
    $sql->close();
} else {
    header("Location: paciente_dashboard.php");
    exit();
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Editar Consulta</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            background-color: #f0f0f0;
            margin: 0;
            padding: 0;
        }
        .container {
            max-width: 600px;
            margin: 20px auto;
            padding: 20px;
            background-color: #fff;
            border-radius: 8px;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
        }
        h2 {
            margin-top: 0;
        }
        form {
            margin-top: 20px;
        }
        label {
            display: block;
            margin-bottom: 5px;
        }
        input[type="date"],
        input[type="time"],
        input[type="text"] {
            width: 100%;
            padding: 8px;
            margin-bottom: 10px;
            border: 1px solid #ccc;
            border-radius: 4px;
        }
        button {
            padding: 8px 20px;
            background-color: #007BFF;
            color: #fff;
            border: none;
            border-radius: 4px;
            cursor: pointer;
        }
        button[type="submit"] {
            margin-right: 10px;
        }
        button:hover {
            background-color: #0056b3;
        }
    </style>
</head>
<body>
    <div class="container">
        <h2>Editar Consulta</h2>
        <form action="salvar_edicao_consulta.php" method="post">
            <input type="hidden" name="consulta_id" value="<?php echo $consulta_id; ?>">
            <label for="dia">Data:</label>
            <input type="date" id="dia" name="dia" value="<?php echo htmlspecialchars($consulta['dia']); ?>" required><br>
            <label for="horario">Hora:</label>
            <input type="time" id="horario" name="horario" value="<?php echo htmlspecialchars($consulta['horario']); ?>" required><br>
            <label for="local">Local:</label>
            <input type="text" id="local" name="local" value="<?php echo htmlspecialchars($consulta['local']); ?>" required><br>
            <button type="submit">Salvar</button>
        </form>
        <form action="paciente_dashboard.php" method="post">
            <button type="submit">Cancelar</button>
        </form>
    </div>
</body>
</html>

<?php
$conn->close();
?>
