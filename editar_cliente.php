<?php
include 'db_connect.php';

$cliente = null;
$id = $_GET['id'] ?? null;

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $id_cliente = $_POST['id_cliente'];
    $nome = $_POST['nome'];
    $cpf = $_POST['cpf'];
    $telefone = $_POST['telefone'];
    $email = $_POST['email'];
    $endereco = $_POST['endereco'];

    $sql = "UPDATE clientes SET nome=?, cpf=?, telefone=?, email=?, endereco=? WHERE id_cliente=?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("sssssi", $nome, $cpf, $telefone, $email, $endereco, $id_cliente);

    if ($stmt->execute()) {
        $_SESSION['message'] = "Cliente atualizado com sucesso!";
        header("Location: admin_clientes.php");
        exit;
    } else {
        echo "Erro ao atualizar: " . $stmt->error;
    }
    $stmt->close();
}
if ($id) {
    $sql = "SELECT * FROM clientes WHERE id_cliente = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("i", $id);
    $stmt->execute();
    $result = $stmt->get_result();
    if ($result->num_rows === 1) {
        $cliente = $result->fetch_assoc();
    } else {
        $_SESSION['message'] = "Cliente não encontrado.";
        header("Location: admin_clientes.php");
        exit;
    }
    $stmt->close();
} else if ($_SERVER["REQUEST_METHOD"] != "POST") {
    header("Location: admin_clientes.php");
    exit;
}
$conn->close();
?>

<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <title>Editar Cliente</title>
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
            <h2>Editar Cliente: <?php echo htmlspecialchars($cliente['nome']); ?></h2>

            <form action="editar_cliente.php" method="POST">
            <input type="hidden" name="id_cliente" value="<?php echo $cliente['id_cliente']; ?>">
            
            <div class="form-group">
                <label for="nome">Nome:</label>
                <input type="text" id="nome" name="nome" value="<?php echo htmlspecialchars($cliente['nome']); ?>" required>
            </div>
            <div class="form-group">
                <label for="cpf">CPF (somente números):</label>
                <input type="text" id="cpf" name="cpf" value="<?php echo htmlspecialchars($cliente['cpf']); ?>" required">
            </div>
            <div class="form-group">
                <label for="telefone">Telefone (com DDD, somente números):</label>
                <input type="tel" id="telefone" name="telefone" value="<?php echo htmlspecialchars($cliente['telefone']); ?>" required">
            </div>
            <div class="form-group">
                <label for="email">Email:</label>
                <input type="email" id="email" name="email" value="<?php echo htmlspecialchars($cliente['email']); ?>" required>
            </div>
            <div class="form-group">
                <label for="endereco">Endereço:</label>
                <input type="text" id="endereco" name="endereco" value="<?php echo htmlspecialchars($cliente['endereco']); ?>" required>
            </div>
            <div class="form-group">
                <button type="submit" class="btn btn-submit">Atualizar Cliente</button>
            </div>
        </form>
            <a href="admin_clientes.php" class="btn-back">Voltar para a lista</a>
        </div>
    </div>
    <script src="assets/js/masks.js"></script>

    </body>
</html>