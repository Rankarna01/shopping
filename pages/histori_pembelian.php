<?php
require_once '../config/db.php';

// Cek jika ada user yang login dan mendapatkan user_id
session_start();
if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit();
}

$user_id = $_SESSION['user_id']; // Ambil user_id dari session

// Query untuk mengambil riwayat pembelian berdasarkan user_id
$query = "
    SELECT c.id, c.product_id, c.quantity, c.status, c.created_at, p.name AS product_name, p.price, p.image 
    FROM checkouts c
    INNER JOIN products p ON c.product_id = p.id
    WHERE c.user_id = ? 
    ORDER BY c.created_at DESC
";

$stmt = $conn->prepare($query);
$stmt->bind_param('i', $user_id);
$stmt->execute();
$result = $stmt->get_result();

// Mengecek apakah ada riwayat pembelian
if ($result->num_rows > 0):
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <link href="https://cdn.jsdelivr.net/npm/tailwindcss@2.2.19/dist/tailwind.min.css" rel="stylesheet">
    <title>Histori Pembelian</title>
</head>
<body class="bg-gray-100">

    <div class="container mx-auto px-4 py-8">
        <h1 class="text-3xl font-semibold text-center mb-6">Histori Pembelian</h1>

        <table class="w-full bg-white rounded-lg shadow-lg overflow-hidden mb-8">
            <thead class="bg-gray-700 text-gray-300">
                <tr>
                    <th class="py-3 px-6 text-left">Nama Produk</th>
                    <th class="py-3 px-6 text-left">Harga</th>
                    <th class="py-3 px-6 text-left">Jumlah</th>
                    <th class="py-3 px-6 text-left">Status</th>
                    <th class="py-3 px-6 text-left">Tanggal Pembelian</th>
                </tr>
            </thead>
            <tbody class="text-gray-800">
                <?php while ($row = $result->fetch_assoc()): ?>
                    <tr class="border-t border-gray-300 hover:bg-gray-200">
                        <td class="py-3 px-6"><?= htmlspecialchars($row['product_name']); ?></td>
                        <td class="py-3 px-6">Rp <?= number_format($row['price']); ?></td>
                        <td class="py-3 px-6"><?= $row['quantity']; ?></td>
                        <td class="py-3 px-6">
                            <?php if ($row['status'] == 'paid'): ?>
                                <span class="text-green-500">Lunas</span>
                            <?php else: ?>
                                <span class="text-red-500">Belum Lunas</span>
                            <?php endif; ?>
                        </td>
                        <td class="py-3 px-6"><?= date('d-m-Y H:i', strtotime($row['created_at'])); ?></td>
                    </tr>
                <?php endwhile; ?>
            </tbody>
        </table>
        
    </div>

<?php else: ?>
    <p class="text-center text-gray-400">Tidak ada riwayat pembelian.</p>
<?php endif; ?>

</body>
</html>

<?php
$stmt->close();
$conn->close();
?>
