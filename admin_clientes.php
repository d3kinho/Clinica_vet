<?php
// admin_clientes.php
include 'db_connect.php';

// ... (Todo o código PHP de busca continua o mesmo) ...
$search_query = "";
$sql = "SELECT * FROM clientes";
if (isset($_GET['search']) && !empty($_GET['search'])) {
    $search_query = $_GET['search'];
    $sql = "SELECT * FROM clientes WHERE nome LIKE ?";
    $stmt = $conn->prepare($sql);
    $like_query = "%" . $search_query . "%";
    $stmt->bind_param("s", $like_query);
} else {
    $stmt = $conn->prepare($sql);
}
$stmt->execute();
$result = $stmt->get_result();
?>

<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Clientes - Pata & Vida</title>
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
            <h1>Painel de Clientes</h1>

            <?php
            if (isset($_SESSION['message'])) {
                echo "<div class='message'>" . $_SESSION['message'] . "</div>";
                unset($_SESSION['message']);
            }
            ?>

            <div class="toolbar">
                <form action="admin_clientes.php" method="GET">
                    <input type="text" name="search" placeholder="Buscar por nome..." value="<?php echo htmlspecialchars($search_query); ?>">
                    <button type="submit">Buscar</button>
                </form>
                
                <a href="novo_cliente.php" class="btn btn-create">Adicionar Novo Cliente</a>
            </div>

            <table>
            <thead>
                <tr>
                    <th>ID</th>
                    <th>Nome</th>
                    <th>CPF</th>
                    <th>Telefone</th>
                    <th>Email</th>
                    <th>Endereço</th>
                    <th>Cadastrado em</th> <th>Ações</th>
                </tr>
            </thead>
            <tbody>
                <?php if ($result->num_rows > 0): ?>
                    <?php while($row = $result->fetch_assoc()): ?>
                        <tr>
                            <td><?php echo $row['id_cliente']; ?></td>
                            <td><?php echo htmlspecialchars($row['nome']); ?></td>
                            <td><?php echo htmlspecialchars($row['cpf']); ?></td>
                            <td><?php echo htmlspecialchars($row['telefone']); ?></td>
                            <td><?php echo htmlspecialchars($row['email']); ?></td>
                            <td><?php echo htmlspecialchars($row['endereco']); ?></td>
                            
                            <td><?php echo date('d/m/Y', strtotime($row['criado_em'])); ?></td> 
                            
                            <td>
                                <a href="editar_cliente.php?id=<?php echo $row['id_cliente']; ?>" class="btn btn-edit">Editar</a>
                                <a href="excluir_cliente.php?id=<?php echo $row['id_cliente']; ?>" class="btn btn-delete" onclick="return confirm('Tem certeza que deseja excluir este cliente?');">Excluir</a>
                            </td>
                        </tr>
                    <?php endwhile; ?>
                <?php else: ?>
                    <tr>
                        <td colspan="8">Nenhum cliente encontrado.</td>
                    </tr>
                <?php endif; ?>
            </tbody>
        </table>
        </div>
    </div>
    </body>
</html>

<?php
$stmt->close();
$conn->close();
?>