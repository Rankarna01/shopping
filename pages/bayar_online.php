<?php
session_start();
require '../config/db.php'; // Pastikan file ini berisi koneksi $conn

// Ambil user_id dari session (sudah login)
$userId = $_SESSION['user_id'] ?? null;
if (!$userId) {
    die('Anda harus login terlebih dahulu.');
}

// Query untuk mengambil data checkout barang yang sudah dipilih oleh pengguna
$sql = "SELECT c.id AS checkout_id, p.name, p.price, c.quantity
        FROM checkouts c
        JOIN products p ON c.product_id = p.id
        WHERE c.user_id = ? AND c.status = 'unpaid'"; // Mengambil hanya yang belum dibayar
$stmt = $conn->prepare($sql);
$stmt->bind_param("i", $userId);
$stmt->execute();
$result = $stmt->get_result();

// Hitung total harga dan buat item untuk transaksi
$items = [];
$total_price = 0;
while ($row = $result->fetch_assoc()) {
    $item_total = $row['price'] * $row['quantity'];
    $total_price += $item_total;

    $items[] = [
        'id' => $row['checkout_id'],
        'price' => $row['price'],
        'quantity' => $row['quantity'],
        'name' => $row['name']
    ];
}

// Jika tidak ada barang untuk dibayar
if ($total_price <= 0) {
    die('Tidak ada barang untuk dibayar.');
}

// Kirim data checkout ke halaman HTML
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Checkout</title>
</head>
<body>
    <h1>Daftar Pembelian Anda</h1>
    <table>
        <thead>
            <tr>
                <th>Nama Produk</th>
                <th>Harga</th>
                <th>Jumlah</th>
                <th>Total</th>
            </tr>
        </thead>
        <tbody>
            <?php foreach ($items as $item): ?>
                <tr>
                    <td><?= htmlspecialchars($item['name']) ?></td>
                    <td>Rp <?= number_format($item['price'], 0, ',', '.') ?></td>
                    <td><?= $item['quantity'] ?></td>
                    <td>Rp <?= number_format($item['price'] * $item['quantity'], 0, ',', '.') ?></td>
                </tr>
            <?php endforeach; ?>
        </tbody>
    </table>
    <p><strong>Total Pembayaran: Rp <?= number_format($total_price, 0, ',', '.') ?></strong></p>

    <!-- Tombol bayar -->
    <form action="get_midtrans.php" method="POST">
        <input type="hidden" name="total_price" value="<?= $total_price ?>">
        <button type="submit">Bayar Sekarang</button>
    </form>
</body>
</html>
