<?php
include 'db_connect.php';

if (isset($_GET['id'])) {
    $id = $_GET['id'];

    $sql = "DELETE FROM clientes WHERE id_cliente = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("i", $id);

    if ($stmt->execute()) {
        $_SESSION['message'] = "Cliente excluído com sucesso!";
    } else {
        $_SESSION['message'] = "Erro ao excluir cliente: " . $stmt->error;

    }
    $stmt->close();
}

$conn->close();
header("Location: admin_clientes.php");
exit;
?>