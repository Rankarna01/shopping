<?php
include '../config/db.php';

// Ambil data penjualan
$sql = "SELECT c.id AS checkout_id, p.name, p.price, c.quantity, c.status, p.image
        FROM checkouts c
        JOIN products p ON c.product_id = p.id
        ORDER BY c.id DESC"; // Mengurutkan berdasarkan ID checkout terbaru
$result = $conn->query($sql);

$penjualan_items = [];
if ($result->num_rows > 0) {
    while ($row = $result->fetch_assoc()) {
        $penjualan_items[] = $row;
    }
}

// Fungsi untuk memisahkan data berdasarkan status
function filterByStatus($items, $status) {
    return array_filter($items, fn($item) => $item['status'] === $status);
}

$unpaid_items = filterByStatus($penjualan_items, 'unpaid');
$paid_items = filterByStatus($penjualan_items, 'paid');

// Ubah status dari unpaid menjadi paid
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['checkout_id'])) {
    $checkout_id = $_POST['checkout_id'];
    $conn->query("UPDATE checkouts SET status = 'paid' WHERE id = $checkout_id");
    header("Location: data_penjualan.php"); // Refresh halaman setelah perubahan
    exit;
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <link href="https://cdn.jsdelivr.net/npm/tailwindcss@2.2.19/dist/tailwind.min.css" rel="stylesheet">
    <title>Data Penjualan</title>
</head>

<body class="bg-gray-100 text-gray-800">
    <?php include('../components/sidebar.php'); ?>

    <section class="container mx-auto px-4 py-8">
        <h1 class="text-3xl font-semibold mb-6 text-center">Data Penjualan</h1>

        <!-- Tabel Unpaid -->
        <h2 class="text-xl font-bold mb-4">Unpaid</h2>
        <?php if (!empty($unpaid_items)): ?>
        <table class="w-full bg-white rounded-lg shadow-lg overflow-hidden mb-8">
            <thead class="bg-gray-700 text-gray-300">
                <tr>
                    <th class="py-3 px-6 text-left">Nama Produk</th>
                    <th class="py-3 px-6 text-left">Harga</th>
                    <th class="py-3 px-6 text-left">Jumlah</th>
                    <th class="py-3 px-6 text-left">Status</th>
                    <th class="py-3 px-6 text-left">Gambar</th>
                    <th class="py-3 px-6 text-left">Aksi</th>
                </tr>
            </thead>
            <tbody class="text-gray-800">
                <?php foreach ($unpaid_items as $item): ?>
                <tr class="border-t border-gray-300 hover:bg-gray-200">
                    <td class="py-3 px-6"><?= $item['name']; ?></td>
                    <td class="py-3 px-6">Rp <?= number_format($item['price']); ?></td>
                    <td class="py-3 px-6"><?= $item['quantity']; ?></td>
                    <td class="py-3 px-6 text-red-500"><?= ucfirst($item['status']); ?></td>
                    <td class="py-3 px-6">
                        <img src="../uploads/<?= $item['image']; ?>" alt="<?= $item['name']; ?>"
                            class="w-16 h-16 object-cover rounded-md">
                    </td>
                    <td class="py-3 px-6">
                        <form method="POST" action="data_penjualan.php">
                            <input type="hidden" name="checkout_id" value="<?= $item['checkout_id']; ?>">
                            <button type="submit"
                                class="bg-green-500 text-white px-4 py-2 rounded-lg hover:bg-green-600">
                                Paid</button>
                        </form>
                    </td>
                </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
        <?php else: ?>
        <p class="text-center text-gray-400">Tidak ada transaksi unpaid.</p>
        <?php endif; ?>

        <!-- Tabel Paid -->
        <h2 class="text-xl font-bold mb-4">Paid</h2>
        <?php if (!empty($paid_items)): ?>
        <table class="w-full bg-white rounded-lg shadow-lg overflow-hidden">
            <thead class="bg-gray-700 text-gray-300">
                <tr>
                    <th class="py-3 px-6 text-left">Nama Produk</th>
                    <th class="py-3 px-6 text-left">Harga</th>
                    <th class="py-3 px-6 text-left">Jumlah</th>
                    <th class="py-3 px-6 text-left">Status</th>
                    <th class="py-3 px-6 text-left">Gambar</th>
                </tr>
            </thead>
            <tbody class="text-gray-800">
                <?php foreach ($paid_items as $item): ?>
                <tr class="border-t border-gray-300 hover:bg-gray-200">
                    <td class="py-3 px-6"><?= $item['name']; ?></td>
                    <td class="py-3 px-6">Rp <?= number_format($item['price']); ?></td>
                    <td class="py-3 px-6"><?= $item['quantity']; ?></td>
                    <td class="py-3 px-6 text-green-500"><?= ucfirst($item['status']); ?></td>
                    <td class="py-3 px-6">
                        <img src="../uploads/<?= $item['image']; ?>" alt="<?= $item['name']; ?>"
                            class="w-16 h-16 object-cover rounded-md">
                    </td>
                </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
        <?php else: ?>
        <p class="text-center text-gray-400">Tidak ada transaksi paid.</p>
        <?php endif; ?>
    </section>
</body>

</html>
