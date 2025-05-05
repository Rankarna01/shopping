<?php
include '../config/db.php';

if (isset($_GET['id'])) {
    $id = $_GET['id'];

    // Hapus data produk dari database
    $stmt = $conn->prepare("DELETE FROM products WHERE id = ?");
    $stmt->bind_param('i', $id);

    if ($stmt->execute()) {
        // Redirect dengan parameter success untuk menunjukkan sukses
        header('Location: daftar_produk.php?deleted=1');
        exit();
    } else {
        echo "Error: " . $stmt->error;
    }
} else {
    header('Location: daftar_produk.php');
    exit();
}
?>