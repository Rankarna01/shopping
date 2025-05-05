<?php
include '../config/db.php';

// Tambahkan ke keranjang
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['product_id'], $_POST['quantity'])) {
    $product_id = $_POST['product_id'];
    $quantity = $_POST['quantity'];
    $user_id = 1; // Ganti dengan id pengguna yang sesuai

    // Ambil stok dari produk
    $sql = "SELECT stock FROM products WHERE id = $product_id";
    $result = $conn->query($sql);
    $product = $result->fetch_assoc();

    if ($product['stock'] >= $quantity) {
        // Kurangi stok produk
        $new_stock = $product['stock'] - $quantity;
        $conn->query("UPDATE products SET stock = $new_stock WHERE id = $product_id");

        // Masukkan ke tabel checkouts
        $conn->query("INSERT INTO checkouts (product_id, user_id, quantity, status) VALUES ($product_id, $user_id, $quantity, 'unpaid')");
    } else {
        echo "Stok tidak mencukupi.";
    }
}

// Hapus dari keranjang dan kembalikan stok
if (isset($_GET['action']) && $_GET['action'] === 'delete' && isset($_GET['checkout_id'])) {
    $checkout_id = $_GET['checkout_id'];

    // Ambil informasi produk yang dihapus dari keranjang
    $sql = "SELECT product_id, quantity FROM checkouts WHERE id = $checkout_id";
    $result = $conn->query($sql);
    $item = $result->fetch_assoc();

    // Perbarui stok produk
    if ($item) {
        $product_id = $item['product_id'];
        $quantity = $item['quantity'];

        // Ambil stok saat ini dari produk
        $sql = "SELECT stock FROM products WHERE id = $product_id";
        $result = $conn->query($sql);
        $product = $result->fetch_assoc();

        // Kembalikan stok produk
        $new_stock = $product['stock'] + $quantity;
        $conn->query("UPDATE products SET stock = $new_stock WHERE id = $product_id");

        // Hapus item dari keranjang
        $conn->query("DELETE FROM checkouts WHERE id = $checkout_id");
    }
}

// Bayar dan ubah status menjadi paid
if (isset($_GET['action']) && $_GET['action'] === 'pay') {
    $conn->query("UPDATE checkouts SET status = 'paid' WHERE status = 'unpaid'");
    header("Location: payment.php"); // Arahkan ke halaman payment.php setelah bayar
    exit;
}

// Ambil data keranjang hanya untuk status unpaid
$sql = "SELECT c.id AS checkout_id, p.id AS product_id, p.name, p.price, c.quantity, c.status, p.image 
        FROM checkouts c 
        JOIN products p ON c.product_id = p.id 
        WHERE c.status = 'unpaid'";
$result = $conn->query($sql);

$cart_items = [];
if ($result->num_rows > 0) {
    while ($row = $result->fetch_assoc()) {
        $cart_items[] = $row;
    }
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Checkout</title>
    <link href="https://cdn.jsdelivr.net/npm/tailwindcss@2.2.19/dist/tailwind.min.css" rel="stylesheet">
</head>

<body class="bg-gray-100 text-gray-800">
    <section class="container mx-auto px-4 py-8">
        <h1 class="text-2xl font-bold mb-6">Keranjang Belanja</h1>

        <?php if (!empty($cart_items)): ?>
            <!-- Tambahkan scroll pada layar kecil -->
            <div class="overflow-x-auto bg-white rounded-lg shadow-md">
                <table class="min-w-full table-auto">
                    <thead>
                        <tr class="bg-gray-100">
                            <th class="py-2 px-4 border-b text-left">Gambar Produk</th>
                            <th class="py-2 px-4 border-b text-left">Nama Produk</th>
                            <th class="py-2 px-4 border-b text-left">Harga</th>
                            <th class="py-2 px-4 border-b text-left">Jumlah</th>
                            <th class="py-2 px-4 border-b text-left">Status</th>
                            <th class="py-2 px-4 border-b text-left">Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($cart_items as $item): ?>
                            <tr>
                                <td class="py-2 px-4 border-b">
                                    <?php if ($item['image']): ?>
                                        <img src="../uploads/<?= $item['image']; ?>" alt="<?= $item['name']; ?>"
                                            class="w-16 h-16 object-cover rounded">
                                    <?php else: ?>
                                        <img src="../uploads/default.png" alt="No Image"
                                            class="w-16 h-16 object-cover rounded">
                                    <?php endif; ?>
                                </td>
                                <td class="py-2 px-4 border-b"><?= $item['name']; ?></td>
                                <td class="py-2 px-4 border-b">Rp <?= number_format($item['price']); ?></td>
                                <td class="py-2 px-4 border-b"><?= $item['quantity']; ?></td>
                                <td class="py-2 px-4 border-b"><?= ucfirst($item['status']); ?></td>
                                <td class="py-2 px-4 border-b">
                                    <a href="?action=delete&checkout_id=<?= $item['checkout_id']; ?>"
                                        class="text-red-500 hover:underline">Hapus</a>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            </div>

            <!-- Tombol responsif -->
            <div class="flex flex-col md:flex-row md:space-x-3 mt-4">
                <form action="?action=pay" method="GET"
                    onsubmit="window.location.href='payment.php'; return false;"
                    class="w-full mb-2 md:mb-0 md:w-auto">
                    <button type="submit"
                        class="w-full bg-green-500 text-white px-4 py-2 rounded-lg hover:bg-green-600">Bayar</button>
                </form>

                <a href="../pages/Shopping.php" class="w-full md:w-auto">
                    <button type="button"
                        class="w-full bg-gray-500 text-white px-4 py-2 rounded-lg hover:bg-gray-600">Kembali</button>
                </a>
            </div>
        <?php else: ?>
            <p class="text-gray-600">Keranjang Anda kosong.</p>
        <?php endif; ?>
    </section>
</body>

</html>
