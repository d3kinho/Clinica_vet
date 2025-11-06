<?php
include 'db_connect.php';

$message = '';
$message_type = '';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $nome = $_POST['nome'];
    $cpf = $_POST['cpf'];
    $telefone = $_POST['telefone'];
    $email = $_POST['email'];
    $endereco = $_POST['endereco'];

    if (empty($nome)) {
        $message = "O campo 'Nome' é obrigatório.";
        $message_type = 'message-error';
    } else {
        $sql = "INSERT INTO clientes (nome, cpf, telefone, email, endereco) VALUES (?, ?, ?, ?, ?)";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("sssss", $nome, $cpf, $telefone, $email, $endereco);

        if ($stmt->execute()) {
            $_SESSION['message'] = "Cliente cadastrado com sucesso!";
            header("Location: admin_clientes.php");
            exit;
        } else {
            $message = "Erro ao cadastrar cliente: " . $stmt->error;
            $message_type = 'message-error';
        }
        $stmt->close();
    }
}
$conn->close();
?>

<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <title>Novo Cliente</title>
    <link rel="stylesheet" href="assets/css/style.css">
</head>
<body>

    <div class="sidebar">
        <h2 class="sidebar-title">
    <img src="assets/img/vet.png" alt="Logo Pata & Vida" class="sidebar-logo">
    <span>Pata & Vida</span>
</h2>
        <nav>
            <a href="admin_clientes.php" class="active">Clientes</a>
            <a href="admin_animais.php">Animais</a>
            <a href="#">Veterinários</a>
            <a href="#">Consultas</a>
        </nav>
    </div>
    <div class="main-content">
        <div class="container">
            <h2>Adicionar Novo Cliente</h2>

            <?php
            if (!empty($message)) {
                echo "<div class='message $message_type'>$message</div>";
            }
            ?>

            <form action="novo_cliente.php" method="POST">
            <div class="form-group">
                <label for="nome">Nome:</label>
                <input type="text" id="nome" name="nome" required>
            </div>
            <div class="form-group">
                <label for="cpf">CPF (somente números):</label>
                <input type="text" id="cpf" name="cpf"">
            </div>
            <div class="form-group">
                <label for="telefone">Telefone (com DDD, somente números):</label>
                <input type="tel" id="telefone" name="telefone"">
            </div>
            <div class="form-group">
                <label for="email">Email:</label>
                <input type="email" id="email" name="email" required>
            </div>
            <div class="form-group">
                <label for="endereco">Endereço:</label>
                <input type="text" id="endereco" name="endereco" required>
            </div>
            <div class="form-group">
                <button type="submit" class="btn btn-submit">Salvar Cliente</button>
            </div>
        </form>
            <a href="admin_clientes.php" class="btn-back">Voltar para a lista</a>
        </div>
    </div>
    <script src="assets/js/masks.js"></script>
    </body>
</html>