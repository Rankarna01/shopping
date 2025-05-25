<?php

include '../config/db.php';

// Ambil data penjualan beserta nama dan alamat user
$sql = "SELECT c.id AS checkout_id, p.name, p.price, c.quantity, c.status, p.image, c.tanggal,
               u.username, u.alamat
        FROM checkouts c
        JOIN products p ON c.product_id = p.id
        JOIN users u ON c.user_id = u.id
        ORDER BY c.id DESC";
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

// Ubah status dari unpaid menjadi paid dan update tanggal
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['checkout_id'])) {
    $checkout_id = $_POST['checkout_id'];
    $conn->query("UPDATE checkouts SET status = 'paid', tanggal = NOW() WHERE id = $checkout_id");
    header("Location: data_penjualan.php");
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

    <section class="container mx-auto max-w-screen-md px-4 py-8">
    <h1 class="text-3xl font-semibold mb-6 text-center">Data Penjualan</h1>

    <!-- Tabel Unpaid -->
    <h2 class="text-xl font-bold mb-4">Unpaid</h2>
    <?php if (!empty($unpaid_items)): ?>
    <div class="overflow-x-auto mb-8">
        <table class="w-full bg-white rounded-lg shadow-lg">
            <thead class="bg-gray-700 text-gray-300">
                <tr>
                    <th class="py-3 px-4 text-left">Nama Produk</th>
                    <th class="py-3 px-4 text-left">Harga</th>
                    <th class="py-3 px-4 text-left">Jumlah</th>
                    <th class="py-3 px-4 text-left">Status</th>
                    <th class="py-3 px-4 text-left">Tanggal</th>
                    <th class="py-3 px-4 text-left">Nama</th>
                    <th class="py-3 px-4 text-left">Alamat</th>
                    <th class="py-3 px-4 text-left">Gambar</th>
                    <th class="py-3 px-4 text-left">Aksi</th>
                </tr>
            </thead>
            <tbody class="text-gray-800">
                <?php foreach ($unpaid_items as $item): ?>
                <tr class="border-t border-gray-300 hover:bg-gray-100">
                    <td class="py-3 px-4"><?= $item['name']; ?></td>
                    <td class="py-3 px-4">Rp <?= number_format($item['price']); ?></td>
                    <td class="py-3 px-4"><?= $item['quantity']; ?></td>
                    <td class="py-3 px-4 text-red-500"><?= ucfirst($item['status']); ?></td>
                    <td class="py-3 px-4">
                        <?= $item['tanggal'] ? date('d-m-Y H:i', strtotime($item['tanggal'])) : '-' ?>
                    </td>
                    <td class="py-3 px-4"><?= htmlspecialchars($item['username']); ?></td>
                    <td class="py-3 px-4"><?= htmlspecialchars($item['alamat']); ?></td>
                    <td class="py-3 px-4">
                        <img src="../uploads/<?= $item['image']; ?>" alt="<?= $item['name']; ?>"
                            class="w-16 h-16 object-cover rounded-md">
                    </td>
                    <td class="py-3 px-4">
                        <form method="POST" action="data_penjualan.php">
                            <input type="hidden" name="checkout_id" value="<?= $item['checkout_id']; ?>">
                            <button type="submit"
                                class="bg-green-500 text-white px-4 py-2 rounded hover:bg-green-600 text-sm">
                                Paid
                            </button>
                        </form>
                    </td>
                </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    </div>
    <?php else: ?>
    <p class="text-center text-gray-400 mb-8">Tidak ada transaksi unpaid.</p>
    <?php endif; ?>

    <!-- Tabel Paid -->
    <h2 class="text-xl font-bold mb-4">Paid</h2>
    <?php if (!empty($paid_items)): ?>
    <div class="overflow-x-auto">
        <table class="w-full bg-white rounded-lg shadow-lg">
            <thead class="bg-gray-700 text-gray-300">
                <tr>
                    <th class="py-3 px-4 text-left">Nama Produk</th>
                    <th class="py-3 px-4 text-left">Harga</th>
                    <th class="py-3 px-4 text-left">Jumlah</th>
                    <th class="py-3 px-4 text-left">Status</th>
                    <th class="py-3 px-4 text-left">Tanggal</th>
                    <th class="py-3 px-4 text-left">Nama</th>
                    <th class="py-3 px-4 text-left">Alamat</th>
                    <th class="py-3 px-4 text-left">Gambar</th>
                </tr>
            </thead>
            <tbody class="text-gray-800">
                <?php foreach ($paid_items as $item): ?>
                <tr class="border-t border-gray-300 hover:bg-gray-100">
                    <td class="py-3 px-4"><?= $item['name']; ?></td>
                    <td class="py-3 px-4">Rp <?= number_format($item['price']); ?></td>
                    <td class="py-3 px-4"><?= $item['quantity']; ?></td>
                    <td class="py-3 px-4 text-green-500"><?= ucfirst($item['status']); ?></td>
                    <td class="py-3 px-4">
                        <?= $item['tanggal'] ? date('d-m-Y H:i', strtotime($item['tanggal'])) : '-' ?>
                    </td>
                    <td class="py-3 px-4"><?= htmlspecialchars($item['username']); ?></td>
                    <td class="py-3 px-4"><?= htmlspecialchars($item['alamat']); ?></td>
                    <td class="py-3 px-4">
                        <img src="../uploads/<?= $item['image']; ?>" alt="<?= $item['name']; ?>"
                            class="w-16 h-16 object-cover rounded-md">
                    </td>
                </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    </div>
    <?php else: ?>
    <p class="text-center text-gray-400">Tidak ada transaksi paid.</p>
    <?php endif; ?>
</section>

</body>

</html>