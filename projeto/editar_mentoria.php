<?php
session_start();

// Verificar se o usuário está logado e se é um aluno
if (!isset($_SESSION['user_id']) || $_SESSION['user_type'] !== 'aluno') {
    header("Location: login.php");
    exit();
}

include 'db_connection.php';

if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['editar_mentoria_id'])) {
    $mentoria_id = $_POST['editar_mentoria_id'];

    // Consulta para obter os dados da mentoria
    $sql = $conn->prepare("SELECT dia, horario, local FROM Mentoria WHERE ID_mentoria = ?");
    $sql->bind_param("i", $mentoria_id);
    $sql->execute();
    $result = $sql->get_result();
    $mentoria = $result->fetch_assoc();
    $sql->close();
} else {
    header("Location: aluno_dashboard.php");
    exit();
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Editar Mentoria</title>
</head>
<body>
    <h2>Editar Mentoria</h2>
    <form action="salvar_edicao_mentoria.php" method="post">
        <input type="hidden" name="mentoria_id" value="<?php echo $mentoria_id; ?>">
        <label for="dia">Data:</label>
        <input type="date" id="dia" name="dia" value="<?php echo htmlspecialchars($mentoria['dia']); ?>" required><br>
        <label for="horario">Hora:</label>
        <input type="time" id="horario" name="horario" value="<?php echo htmlspecialchars($mentoria['horario']); ?>" required><br>
        <label for="local">Local:</label>
        <input type="text" id="local" name="local" value="<?php echo htmlspecialchars($mentoria['local']); ?>" required><br>
        <button type="submit">Salvar</button>
    </form>
    <form action="aluno_dashboard.php" method="post">
        <button type="submit">Cancelar</button>
    </form>
</body>
</html>

<?php
$conn->close();
?>
