<?php
include '../config/db.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $id = $_POST['id'];
    $name = $_POST['name'];
    $price = $_POST['price'];
    $stock = $_POST['stock'];

    // Update data produk di database
    $stmt = $conn->prepare("UPDATE products SET name = ?, price = ?, stock = ? WHERE id = ?");
    $stmt->bind_param('sdsi', $name, $price, $stock, $id);

    if ($stmt->execute()) {
        // Redirect dengan parameter success untuk menunjukkan sukses
        header('Location: daftar_produk.php?success=1');
        exit();
    } else {
        echo "Error: " . $stmt->error;
    }
}
?>