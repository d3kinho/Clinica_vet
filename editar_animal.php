<?php

include 'db_connect.php';

$message = '';
$message_type = '';
$animal = null;
$id_animal = $_GET['id'] ?? null;

if (!$id_animal) {
    header("Location: admin_animais.php");
    exit;
}

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    
    $id_animal_post = $_POST['id_animal'];
    $nome = $_POST['nome'];
    $especie = $_POST['especie'];
    $raca = $_POST['raca'];
    $idade = $_POST['idade'];
    $sexo = $_POST['sexo'];
    $id_cliente = $_POST['id_cliente'];
    $status = $_POST['status'];
    $foto_atual = $_POST['foto_atual'];
    $foto_url = $foto_atual;

    if (isset($_FILES['foto']) && $_FILES['foto']['error'] == UPLOAD_ERR_OK && !empty($_FILES['foto']['name'])) {
        $upload_dir = 'uploads/animais/';
        $file_name = time() . '_' . basename($_FILES['foto']['name']);
        $target_file = $upload_dir . $file_name;

        if (move_uploaded_file($_FILES['foto']['tmp_name'], $target_file)) {
            $foto_url = $target_file;
            
            if (!empty($foto_atual) && file_exists($foto_atual)) {
                unlink($foto_atual);
            }
        } else {
            $message = "Erro ao mover o novo arquivo de foto.";
            $message_type = 'message-error';
        }
    }

    if (empty($message)) {
        $sql = "UPDATE animais SET nome=?, especie=?, raca=?, idade=?, sexo=?, id_cliente=?, foto_url=?, status=?
                WHERE id_animal = ?";
        
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("sssisissi", $nome, $especie, $raca, $idade, $sexo, $id_cliente, $foto_url, $status, $id_animal_post);

        if ($stmt->execute()) {
            $_SESSION['message'] = "Animal atualizado com sucesso!";
            header("Location: admin_animais.php");
            exit;
        } else {
            $message = "Erro ao atualizar animal: " . $stmt->error;
            $message_type = 'message-error';
        }
        $stmt->close();
    }
}

$stmt_animal = $conn->prepare("SELECT * FROM animais WHERE id_animal = ?");
$stmt_animal->bind_param("i", $id_animal);
$stmt_animal->execute();
$result_animal = $stmt_animal->get_result();
if ($result_animal->num_rows === 1) {
    $animal = $result_animal->fetch_assoc();
} else {
    $_SESSION['message'] = "Animal não encontrado.";
    header("Location: admin_animais.php");
    exit;
}
$stmt_animal->close();

$clientes_result = $conn->query("SELECT id_cliente, nome FROM clientes ORDER BY nome");

?>
<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <title>Editar Animal</title>
    <link rel="stylesheet" href="assets/css/style.css">
</head>
<body>
    <div class="sidebar">
    <h2 class="sidebar-title">
        <img src="assets/img/vet.png" alt="Logo Pata & Vida" class="sidebar-logo">
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
            <h2>Editar Animal: <?php echo htmlspecialchars($animal['nome']); ?></h2>

            <?php
            if (!empty($message)) {
                echo "<div class='message $message_type'>$message</div>";
            }
            ?>

            <?php if(!empty($animal['foto_url'])): ?>
                <div class="form-group">
                    <label>Foto Atual:</label>
                    <img src="<?php echo $animal['foto_url']; ?>" alt="Foto" style="max-width: 150px; height: auto; border-radius: 5px;">
                </div>
            <?php endif; ?>

            <form action="editar_animal.php?id=<?php echo $id_animal; ?>" method="POST" enctype="multipart/form-data">
                <input type="hidden" name="id_animal" value="<?php echo $animal['id_animal']; ?>">
                <input type="hidden" name="foto_atual" value="<?php echo htmlspecialchars($animal['foto_url']); ?>">

                <div class="form-group">
                    <label for="nome">Nome:</label>
                    <input type="text" id="nome" name="nome" value="<?php echo htmlspecialchars($animal['nome']); ?>" required>
                </div>
                
                <div class="form-group">
                    <label for="id_cliente">Dono (Cliente):</label>
                    <select id="id_cliente" name="id_cliente">
                        <option value="">Selecione o dono</option>
                        <?php while($cliente = $clientes_result->fetch_assoc()): ?>
                            <option value="<?php echo $cliente['id_cliente']; ?>" <?php echo ($cliente['id_cliente'] == $animal['id_cliente']) ? 'selected' : ''; ?>>
                                <?php echo htmlspecialchars($cliente['nome']); ?>
                            </option>
                        <?php endwhile; ?>
                    </select>
                </div>

                <div class="form-group">
                    <label for="especie">Espécie:</label>
                    <input type="text" id="especie" name="especie" value="<?php echo htmlspecialchars($animal['especie']); ?>">
                </div>
                <div class="form-group">
                    <label for="raca">Raça:</label>
                    <input type="text" id="raca" name="raca" value="<?php echo htmlspecialchars($animal['raca']); ?>">
                </div>
                <div class="form-group">
                    <label for="idade">Idade:</label>
                    <input type="number" id="idade" name="idade" value="<?php echo htmlspecialchars($animal['idade']); ?>">
                </div>
                
                <div class="form-group">
                    <label for="sexo">Sexo:</label>
                    <select id="sexo" name="sexo">
                        <option value="I" <?php echo ($animal['sexo'] == 'I') ? 'selected' : ''; ?>>Indefinido</option>
                        <option value="M" <?php echo ($animal['sexo'] == 'M') ? 'selected' : ''; ?>>Macho</option>
                        <option value="F" <?php echo ($animal['sexo'] == 'F') ? 'selected' : ''; ?>>Fêmea</option>
                    </select>
                </div>

                <div class="form-group">
                    <label for="status">Status:</label>
                    <select id="status" name="status" required>
                        <option value="Liberado" <?php echo ($animal['status'] == 'Liberado') ? 'selected' : ''; ?>>Liberado</option>
                        <option value="Em Tratamento" <?php echo ($animal['status'] == 'Em Tratamento') ? 'selected' : ''; ?>>Em Tratamento</option>
                        <option value="Internado" <?php echo ($animal['status'] == 'Internado') ? 'selected' : ''; ?>>Internado</option>
                    </select>
                </div>

                <div class="form-group">
                    <label for="foto">Trocar Foto (opcional):</label>
                    <input type="file" id="foto" name="foto" accept="image/*">
                </div>

                <div class="form-group">
                    <button type="submit" class="btn btn-submit">Atualizar Animal</button>
                    <a href="excluir_animal.php?id=<?php echo $animal['id_animal']; ?>" class="btn btn-delete" style="margin-left: 10px;" onclick="return confirm('Tem certeza que deseja excluir este animal? Esta ação não pode ser desfeita.');">Excluir Animal</a>
                </div>
            </form>
            <a href="admin_animais.php" class="btn-back">Voltar para a lista</a>
        </div>
    </div>
    <script src="assets/js/masks.js"></script>
</body>
</html>
<?php $conn->close(); ?>