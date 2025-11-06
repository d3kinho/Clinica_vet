<?php
include 'db_connect.php';

if (isset($_GET['id'])) {
    $id = $_GET['id'];

    $stmt_select = $conn->prepare("SELECT foto_url FROM animais WHERE id_animal = ?");
    $stmt_select->bind_param("i", $id);
    $stmt_select->execute();
    $result = $stmt_select->get_result();
    
    if ($result->num_rows === 1) {
        $animal = $result->fetch_assoc();
        $foto_url = $animal['foto_url'];

        $stmt_delete = $conn->prepare("DELETE FROM animais WHERE id_animal = ?");
        $stmt_delete->bind_param("i", $id);

        if ($stmt_delete->execute()) {
            if (!empty($foto_url) && file_exists($foto_url)) {
                unlink($foto_url);
            }
            $_SESSION['message'] = "Animal excluído com sucesso!";
        } else {
            $_SESSION['message'] = "Erro ao excluir animal: " . $stmt_delete->error;
        }
        $stmt_delete->close();
    } else {
        $_SESSION['message'] = "Animal não encontrado.";
    }
    $stmt_select->close();
}

$conn->close();
header("Location: admin_animais.php");
exit;
?>