<?php
require '../config/db.php';
require '../vendor/autoload.php'; // Library Dompdf

use Dompdf\Dompdf;

// Ambil ID user login
$userId = 1; // Idealnya dari $_SESSION

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

// Cetak PDF jika tombol ditekan
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['generate_pdf'])) {
  $buyer_name = $_POST['buyer_name'];
  $buyer_email = $_POST['buyer_email'];
  $payment_amount = $_POST['payment_amount'];

  if (empty($buyer_name) || empty($buyer_email) || empty($payment_amount)) {
    header("Location: payment.php?error=1");
    exit;
  }

  $change = $payment_amount - $total_price;

  // HTML struk kecil
  $html = "
  <style>
    body { font-family: Arial, sans-serif; font-size: 10px; margin: 0; padding: 5px; }
    .title { text-align: center; font-weight: bold; margin-bottom: 5px; }
    table { width: 100%; border-collapse: collapse; }
    th, td { padding: 2px 0; text-align: left; }
    .total { margin-top: 5px; border-top: 1px dashed #000; padding-top: 5px; }
    .footer { text-align: center; margin-top: 10px; font-size: 9px; }
  </style>

  <div class='struk'>
    <div class='title'>
      Toko Bunasya<br>
      Invoice #: INV" . rand(1000, 9999) . "<br>
      Tanggal: " . date('d-m-Y') . "
    </div>

    <p><strong>Nama:</strong> $buyer_name<br>
    <strong>Email:</strong> $buyer_email</p>

    <table>
      <thead>
        <tr><th>Item</th><th>Qty</th><th>Harga</th><th>Sub</th></tr>
      </thead>
      <tbody>";

  foreach ($cart_items as $item) {
    $subtotal = $item['price'] * $item['quantity'];
    $html .= "
        <tr>
          <td>{$item['name']}</td>
          <td>{$item['quantity']}</td>
          <td>Rp " . number_format($item['price'], 0, ',', '.') . "</td>
          <td>Rp " . number_format($subtotal, 0, ',', '.') . "</td>
        </tr>";
  }

  $html .= "
      </tbody>
    </table>

    <div class='total'>
      <p><strong>Total:</strong> Rp " . number_format($total_price, 0, ',', '.') . "</p>
      <p><strong>Bayar:</strong> Rp " . number_format($payment_amount, 0, ',', '.') . "</p>
      <p><strong>Kembalian:</strong> Rp " . number_format($change, 0, ',', '.') . "</p>
    </div>

    <div class='footer'>
      --- Terima Kasih ---
    </div>
  </div>";

  // Cetak PDF ukuran 58mm x panjang fleksibel (misal 200mm)
  $dompdf = new Dompdf();
  $dompdf->loadHtml($html);
  $dompdf->setPaper([0, 0, 164, 500], 'portrait'); // 58mm = 164pt, panjang 500pt = ±176mm
  $dompdf->render();
  $dompdf->stream("Struk_POS_58.pdf", ["Attachment" => false]);
  exit;
}
?>


<!DOCTYPE html>
<html lang="id">

<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Pembayaran</title>
  <script src="https://kit.fontawesome.com/your-fontawesome-kit.js" crossorigin="anonymous"></script>
  <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/tailwindcss@2.2.19/dist/tailwind.min.css">

  <script src="https://app.sandbox.midtrans.com/snap/snap.js"
    data-client-key="SB-Mid-client-27xGdI1bQJYUOXO4"></script>
</head>

<body class="bg-gray-100">
  <div class="container mx-auto p-6">
    <h1 class="text-2xl font-bold mb-6 text-center">🛒 Keranjang Belanja</h1>
    <!-- Tabel Produk -->
    <div class="overflow-x-auto">
      <table class="w-full bg-white shadow-md rounded-lg overflow-hidden">
        <thead>
          <tr class="bg-green-600">
            <th class="px-4 py-2 text-left"> Nama Kue</th>
            <th class="px-4 py-2 text-left"> Harga</th>
            <th class="px-4 py-2 text-left"> Jumlah</th>
          </tr>
        </thead>
        <tbody>
          <?php foreach ($cart_items as $item): ?>
            <tr>
              <td class="border px-4 py-2"><?= htmlspecialchars($item['name']) ?></td>
              <td class="border px-4 py-2">Rp <?= number_format($item['price'], 0, ',', '.') ?></td>
              <td class="border px-4 py-2"><?= htmlspecialchars($item['quantity']) ?></td>
            </tr>
          <?php endforeach; ?>
        </tbody>
      </table>
    </div>
    <div class="mt-4 p-4 border border-green-500 bg-green-100 rounded-lg text-right">
  <p class="text-xl font-semibold text-green-700">
    💳 Total Harga: Rp <?= number_format($total_price, 0, ',', '.') ?>
  </p>
</div>



    <!-- Tombol Aksi -->
   

    <!-- Title Center for Online Payment -->
    <!-- Bagian Tombol Bayar Online + Masukkan Alamat -->
    <div class="mt-6 flex flex-col md:flex-row gap-3 items-center ">
      <h2 class="text-xl font-bold text-center mb-2 md:mb-0">Pembayaran Online</h2>

      <!-- Form Bayar Online ke Midtrans -->
      <form action="get_midtrans.php" method="POST">
        <input type="hidden" name="amount" value="10000">
        <button type="submit" class="w-full md:w-auto bg-green-500 text-white px-4 py-2 rounded-lg hover:bg-green-600 transition duration-300 flex items-center justify-center gap-2">
          <i class="fas fa-credit-card"></i> Bayar Online
        </button>
      </form>


      <!-- Modal Masukkan Al
  <!-- Modal Pembayaran -->
      

      <!-- Script -->
      <script>
        function toggleModal() {
          const modal = document.getElementById("payment-modal");
          modal.classList.toggle("hidden");
        }

        function sendToWhatsApp() {
          const name = document.getElementById("buyer_name").value;
          const address = document.getElementById("buyer_email").value;
          const amount = document.getElementById("payment_amount").value;

          const message = `Halo Admin, saya ingin melakukan pembayaran:\n\nNama: ${name}\nAlamat: ${address}\nJumlah: Rp${amount}\n\nBukti pembayaran sudah saya upload.`;

          const whatsappURL = `https://wa.me/6281234567890?text=${encodeURIComponent(message)}`;
          window.open(whatsappURL, '_blank');
        }
      </script>


</body>

</html>



</body>

</html>