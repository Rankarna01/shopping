<?php
require __DIR__ . '/../vendor/autoload.php';

use Mike42\Escpos\Printer;
use Mike42\Escpos\PrintConnectors\FilePrintConnector;

// GANTI DENGAN COM5 sesuai Device Manager kamu
$connector = new FilePrintConnector("COM5:");
$printer = new Printer($connector);

// === Pastikan $conn dan $userId sudah ada ===
include 'koneksi.php'; // atau config DB kamu
session_start();
$userId = $_SESSION['user_id']; // atau sesuai implementasi login kamu

// Ambil data checkout
$sql = "SELECT c.id AS checkout_id, p.name, p.price, c.quantity, c.status, p.stock, p.image
        FROM checkouts c
        JOIN products p ON c.product_id = p.id
        WHERE c.user_id = ? AND c.status = 'unpaid'";
$stmt = $conn->prepare($sql);
$stmt->bind_param("i", $userId);
$stmt->execute();
$result = $stmt->get_result();

$cart_items = [];
$total_price = 0;

while ($row = $result->fetch_assoc()) {
    $cart_items[] = $row;
    $total_price += $row['price'] * $row['quantity'];
}

// Cek POST cetak
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['print_struk'])) {
    $buyer_name = $_POST['buyer_name'];
    $buyer_email = $_POST['buyer_email'];
    $payment_amount = (int) $_POST['payment_amount'];

    if (empty($buyer_name) || empty($buyer_email) || empty($payment_amount)) {
        header("Location: payment.php?error=1");
        exit;
    }

    $change = $payment_amount - $total_price;

    try {
        // Mulai cetak
        $printer->setJustification(Printer::JUSTIFY_CENTER);
        $printer->text("TOKO BUNASYA\n");
        $printer->text("Invoice: INV" . rand(1000, 9999) . "\n");
        $printer->text("Tanggal: " . date('d-m-Y H:i') . "\n\n");

        $printer->setJustification(Printer::JUSTIFY_LEFT);
        $printer->text("Nama   : $buyer_name\n");
        $printer->text("Alamat : $buyer_email\n\n");

        $printer->text("----------------------------------------\n");
        $printer->text("Produk           Qty   Harga   Subtotal\n");
        $printer->text("----------------------------------------\n");

        foreach ($cart_items as $item) {
            $name = substr($item['name'], 0, 15);
            $qty = $item['quantity'];
            $price = number_format($item['price'], 0, ',', '.');
            $subtotal = number_format($item['price'] * $qty, 0, ',', '.');

            $printer->text(sprintf("%-15s %3s Rp%-8s\n", $name, $qty, $subtotal));
        }

        $printer->text("----------------------------------------\n");
        $printer->setJustification(Printer::JUSTIFY_RIGHT);
        $printer->text("Total     : Rp " . number_format($total_price, 0, ',', '.') . "\n");
        $printer->text("Bayar     : Rp " . number_format($payment_amount, 0, ',', '.') . "\n");
        $printer->text("Kembalian : Rp " . number_format($change, 0, ',', '.') . "\n");

        $printer->feed(2);
        $printer->setJustification(Printer::JUSTIFY_CENTER);
        $printer->text("Terima kasih atas pembelian Anda!\n");
        $printer->feed(3);
        $printer->cut();
        $printer->close();

        header("Location: payment.php?success=1");
        exit;
    } catch (Exception $e) {
        echo "Terjadi kesalahan saat mencetak: " . $e->getMessage();
    }
}
?>

<!-- Tombol Cetak -->
<form method="POST">
    <input type="hidden" name="buyer_name" value="Nama Pembeli">
    <input type="hidden" name="buyer_email" value="Alamat Email">
    <input type="hidden" name="payment_amount" value="50000">
    <button type="submit" name="print_struk">🖨 Cetak Struk Thermal</button>
</form>
