<?php
session_start();

if (!isset($_SESSION['user_id']) || $_SESSION['user_type'] !== 'paciente') {
    header("Location: login.php");
    exit();
}

include 'db_connection.php';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Verificar se os campos necessários foram enviados
    if (isset($_POST['psicologo'], $_POST['data'], $_POST['hora'], $_POST['local'])) {
        $paciente_id = $_SESSION['user_id'];
        $psicologo_id = $_POST['psicologo'];
        $data = $_POST['data'];
        $hora = $_POST['hora'];
        $local = $_POST['local'];

        if ($psicologo_id != '') {
            // Verificar se um psicólogo foi selecionado
            $sql = "INSERT INTO Consulta (fk_Paciente_ID_paciente, fk_psicologo_ID_psicologo, horario, dia, local) 
                    VALUES (?, ?, ?, ?, ?)";
            $stmt = $conn->prepare($sql);
            $stmt->bind_param("iisss", $paciente_id, $psicologo_id, $hora, $data, $local);
            
            if ($stmt->execute()) {
                // Consulta agendada com sucesso
                header("Location: paciente_dashboard.php");
                exit();
            } else {
                echo "Erro ao agendar a consulta: " . $conn->error;
            }

            $stmt->close();
        } else {
            echo "Por favor, selecione um psicólogo.";
        }
    } else {
        echo "Por favor, preencha todos os campos.";
    }
}

// Consulta para obter todos os psicólogos
$sql_psicologos = "SELECT ID_psicologo, nome FROM psicologo";
$result_psicologos = $conn->query($sql_psicologos);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Agendar Consulta</title>
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
        label {
            display: block;
            margin-bottom: 5px;
        }
        select, input {
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
        button:hover {
            background-color: #0056b3;
        }
    </style>
</head>
<body>
    <div class="container">
        <h2>Agendar Consulta</h2>
        <form action="" method="post">
            <label for="psicologo">Selecione um psicólogo:</label>
            <select id="psicologo" name="psicologo" required>
                <option value="">Selecione</option>
                <?php
                while ($psicologo = $result_psicologos->fetch_assoc()) {
                    echo "<option value='{$psicologo['ID_psicologo']}'>{$psicologo['nome']}</option>";
                }
                ?>
            </select><br><br>

            <label for="data">Data:</label>
            <input type="date" id="data" name="data" required><br><br>

            <label for="hora">Hora:</label>
            <input type="time" id="hora" name="hora" required><br><br>

            <label for="local">Local:</label>
            <input type="text" id="local" name="local" required><br><br>

            <button type="submit">Agendar Consulta</button>
        </form>
        <a href="paciente_dashboard.php"><button>Voltar</button></a>
    </div>    
</body>
</html>

<?php
$conn->close();
?>
