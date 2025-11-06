<?php
include 'db_connect.php';

$message = '';
$message_type = '';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    
    $nome = $_POST['nome'];
    $especie = $_POST['especie'];
    $raca = $_POST['raca'];
    $idade = $_POST['idade'];
    $sexo = $_POST['sexo'];
    $id_cliente = $_POST['id_cliente'];
    $status = $_POST['status'];
    $foto_url = null;

    if (isset($_FILES['foto']) && $_FILES['foto']['error'] == UPLOAD_ERR_OK) {
        $upload_dir = 'uploads/animais/';

        $file_name = time() . '_' . basename($_FILES['foto']['name']);
        $target_file = $upload_dir . $file_name;

        if (move_uploaded_file($_FILES['foto']['tmp_name'], $target_file)) {
            $foto_url = $target_file;
        } else {
            $message = "Erro ao mover o arquivo de foto.";
            $message_type = 'message-error';
        }
    }

    if (empty($message)) {
        $sql = "INSERT INTO animais (nome, especie, raca, idade, sexo, id_cliente, foto_url, status) 
                VALUES (?, ?, ?, ?, ?, ?, ?, ?)";
        
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("sssisiss", $nome, $especie, $raca, $idade, $sexo, $id_cliente, $foto_url, $status);

        if ($stmt->execute()) {
            $_SESSION['message'] = "Animal cadastrado com sucesso!";
            header("Location: admin_animais.php");
            exit;
        } else {
            $message = "Erro ao cadastrar animal: " . $stmt->error;
            $message_type = 'message-error';
        }
        $stmt->close();
    }
}

$clientes_result = $conn->query("SELECT id_cliente, nome FROM clientes ORDER BY nome");

?>
<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <title>Novo Animal</title>
    <link rel="stylesheet" href="assets/css/style.css">
</head>
<body>
    <div class="sidebar">
        <h2 class="sidebar-title">
            <img src="assets/img/vet.png" alt="Logo Pata & Vida" class="sidebar-logo" style="width:50px; height:50px;">
            <span>Pata & Vida</span>
        </h2>
        <nav>
            <a href="admin_clientes.php">Clientes</a>
            <a href="admin_animais.php" class="active">Animais</a>
            <a href="#">Veterinários</a>
            <a href="#">Consultas</a>
        </nav>
    </div>

    <div class="main-content">
        <div class="container">
            <h2>Adicionar Novo Animal</h2>

            <?php
            if (!empty($message)) {
                echo "<div class='message $message_type'>$message</div>";
            }
            ?>

            <form action="novo_animal.php" method="POST" enctype="multipart/form-data">
                <div class="form-group">
                    <label for="nome">Nome:</label>
                    <input type="text" id="nome" name="nome" required>
                </div>
                
                <div class="form-group">
                    <label for="id_cliente">Dono (Cliente):</label>
                    <select id="id_cliente" name="id_cliente">
                        <option value="">Selecione o dono</option>
                        <?php while($cliente = $clientes_result->fetch_assoc()): ?>
                            <option value="<?php echo $cliente['id_cliente']; ?>">
                                <?php echo htmlspecialchars($cliente['nome']); ?>
                            </option>
                        <?php endwhile; ?>
                    </select>
                </div>

                <div class="form-group">
                    <label for="especie">Espécie:</label>
                    <input type="text" id="especie" name="especie" placeholder="Ex: Cachorro, Gato">
                </div>
                <div class="form-group">
                    <label for="raca">Raça:</label>
                    <input type="text" id="raca" name="raca" placeholder="Ex: Poodle, Siamês">
                </div>
                <div class="form-group">
                    <label for="idade">Idade:</label>
                    <input type="number" id="idade" name="idade">
                </div>
                
                <div class="form-group">
                    <label for="sexo">Sexo:</label>
                    <select id="sexo" name="sexo">
                        <option value="I">Indefinido</option>
                        <option value="M">Macho</option>
                        <option value="F">Fêmea</option>
                    </select>
                </div>

                <div class="form-group">
                    <label for="status">Status:</label>
                    <select id="status" name="status" required>
                        <option value="Liberado">Liberado</option>
                        <option value="Em Tratamento">Em Tratamento</option>
                        <option value="Internado">Internado</option>
                    </select>
                </div>

                <div class="form-group">
                    <label for="foto">Foto:</label>
                    <input type="file" id="foto" name="foto" accept="image/*">
                </div>

                <div class="form-group">
                    <button type="submit" class="btn btn-submit">Salvar Animal</button>
                </div>
            </form>
            <a href="admin_animais.php" class="btn-back">Voltar para a lista</a>
        </div>
    </div>
    <script src="assets/js/masks.js"></script>
</body>
</html>
<?php $conn->close(); ?>