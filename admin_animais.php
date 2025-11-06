<?php
// admin_animais.php
include 'db_connect.php';

// SQL para buscar animais E o nome do dono (JOIN)
$sql = "SELECT a.*, c.nome as nome_cliente 
        FROM animais a 
        LEFT JOIN clientes c ON a.id_cliente = c.id_cliente 
        ORDER BY a.nome";
        
$result = $conn->query($sql);
?>

<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Animais - Pata & Vida</title>
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
            
            <div class="toolbar">
                <h1>Painel de Animais</h1>
                <a href="novo_animal.php" class="btn btn-create">Adicionar Novo Animal</a>
            </div>

            <?php
            if (isset($_SESSION['message'])) {
                echo "<div class='message'>" . $_SESSION['message'] . "</div>";
                unset($_SESSION['message']);
            }
            ?>

            <div class="animal-grid">
                <?php if ($result && $result->num_rows > 0): ?>
                    <?php while($animal = $result->fetch_assoc()): ?>
                        
                        <a href="editar_animal.php?id=<?php echo $animal['id_animal']; ?>" class="animal-card">
                            
                            <?php
                            $foto = !empty($animal['foto_url']) ? $animal['foto_url'] : 'assets/images/vet.png';
                            $status_class = strtolower(str_replace(' ', '-', $animal['status']));
                            ?>

                            <div class="animal-card-image">
                                <img src="<?php echo $foto; ?>" alt="Foto de <?php echo htmlspecialchars($animal['nome']); ?>">
                            </div>
                            
                            <div class="animal-card-info">
                                <h3><?php echo htmlspecialchars($animal['nome']); ?></h3>
                                <p>Dono: <?php echo htmlspecialchars($animal['nome_cliente'] ?? 'Sem dono'); ?></p>
                                
                                <span class="status-badge <?php echo $status_class; ?>">
                                    <?php echo $animal['status']; ?>
                                </span>
                            </div>
                        </a>
                        
                    <?php endwhile; ?>
                <?php else: ?>
                    <p>Nenhum animal cadastrado ainda.</p>
                <?php endif; ?>
            </div> </div>
    </div>
</body>
</html>
<?php $conn->close(); ?>