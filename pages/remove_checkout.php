<?php
include '../config/db.php';

if (isset($_GET['id'])) {
    $checkoutId = $_GET['id'];

    // Ambil data checkout yang akan dihapus
    $sql = "SELECT product_id, quantity FROM checkouts WHERE id = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("i", $checkoutId);
    $stmt->execute();
    $result = $stmt->get_result();
    $checkout = $result->fetch_assoc();

    if ($checkout) {
        $productId = $checkout['product_id'];
        $quantity = $checkout['quantity'];

        // Kembalikan stok ke tabel products
        $updateStockSql = "UPDATE products SET stock = stock + ? WHERE id = ?";
        $updateStockStmt = $conn->prepare($updateStockSql);
        $updateStockStmt->bind_param("ii", $quantity, $productId);
        if ($updateStockStmt->execute()) {
            // Hapus item dari tabel checkouts
            $deleteSql = "DELETE FROM checkouts WHERE id = ?";
            $deleteStmt = $conn->prepare($deleteSql);
            $deleteStmt->bind_param("i", $checkoutId);
            if ($deleteStmt->execute()) {
                header("Location: ../pages/checkout.php");
                exit;
            } else {
                echo "Error: " . $deleteStmt->error;
            }
        } else {
            echo "Error: " . $updateStockStmt->error;
        }
    } else {
        echo "Item not found.";
    }
}
?>