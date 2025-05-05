<?php
require '../config/db.php';
require '../vendor/autoload.php'; // Library Dompdf

use Dompdf\Dompdf;

// Ambil ID user login (contoh hardcoded untuk user ID = 1)
$userId = 1;

// Ambil data checkout yang terkait dengan user
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

// Proses cetak PDF
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['generate_pdf'])) {
    $buyer_name = $_POST['buyer_name'];
    $buyer_email = $_POST['buyer_email'];
    $payment_amount = $_POST['payment_amount'];

    if (empty($buyer_name) || empty($buyer_email) || empty($payment_amount)) {
        header("Location: payment.php?error=1");
        exit;
    }

    // Hitung kembalian
    $change = $payment_amount - $total_price;

    // Buat konten HTML untuk PDF
    $html = "
    <style>
        body { font-family: Arial, sans-serif; color: #333; }
        h2 { text-align: center; color: #444; }
        .invoice-box { width: 100%; padding: 20px; border: 1px solid #ddd; border-radius: 8px; }
        .invoice-header { display: flex; justify-content: space-between; align-items: center; margin-bottom: 20px; }
        .invoice-header img { max-width: 100px; }
        .invoice-details { margin-bottom: 20px; }
        .invoice-details p { margin: 5px 0; }
        .table { width: 100%; border-collapse: collapse; }
        .table th, .table td { border: 1px solid #ddd; padding: 8px; text-align: left; }
        .table th { background-color: #f4f4f4; }
        .total-section { text-align: right; margin-top: 20px; }
    </style>

    <div class='invoice-box'>
        <div class='invoice-header'>
            <div>
                <h2>Invoice Pembayaran Toko Bunasya</h2>
                <p><strong>No. Invoice:</strong> INV" . rand(1000, 9999) . "</p>
                <p><strong>Tanggal:</strong> " . date('d-m-Y') . "</p>
            </div>
            <img src='' alt='Techno Ran     '>
        </div>

        <div class='invoice-details'>
            <p><strong>Nama Pembeli:</strong> $buyer_name</p>
            <p><strong>Alamat:</strong> $buyer_email</p>
        </div>

        <table class='table'>
            <thead>
                <tr>
                    <th>Nama Produk</th>
                    <th>Harga</th>
                    <th>Jumlah</th>
                    <th>Subtotal</th>
                </tr>
            </thead>
            <tbody>";
            foreach ($cart_items as $item) {
                $subtotal = $item['price'] * $item['quantity'];
                $html .= "
                <tr>
                    <td>{$item['name']}</td>
                    <td>Rp " . number_format($item['price'], 2, ',', '.') . "</td>
                    <td>{$item['quantity']}</td>
                    <td>Rp " . number_format($subtotal, 2, ',', '.') . "</td>
                </tr>";
            }
$html .= "
            </tbody>
        </table>

        <div class='total-section'>
            <p><strong>Total Pembayaran:</strong> Rp " . number_format($total_price, 2, ',', '.') . "</p>
            <p><strong>Jumlah Dibayarkan:</strong> Rp " . number_format($payment_amount, 2, ',', '.') . "</p>
            <p><strong>Kembalian:</strong> Rp " . number_format($change, 2, ',', '.') . "</p>
        </div>

        <p style='text-align: center; margin-top: 20px;'><em>Terima kasih atas pembelian Anda!</em></p>
    </div>";


    // Generate PDF
    $dompdf = new Dompdf();
    $dompdf->loadHtml($html);
    $dompdf->setPaper('A4', 'portrait');
    $dompdf->render();
    $dompdf->stream("Invoice.pdf", ["Attachment" => false]);
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
          <tr class="bg-gray-200">
            <th class="px-4 py-2 text-left">📦 Nama Produk</th>
            <th class="px-4 py-2 text-left">💰 Harga</th>
            <th class="px-4 py-2 text-left">🔢 Jumlah</th>
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
    <p class="mt-4 text-xl font-semibold text-right">💳 Total Harga: Rp <?= number_format($total_price, 0, ',', '.') ?></p>



    <!-- Tombol Aksi -->
  <div class="mt-6 flex flex-col md:flex-row gap-3 items-center">
  <!-- Title Center -->
  <h2 class="text-center text-xl font-bold mb-4">Pembayaran Offline / Kasir</h2>

  <!-- Tombol Bayar -->
  <button onclick="toggleModal()" class="w-full md:w-auto bg-green-500 text-white px-4 py-2 rounded-lg hover:bg-green-600 transition duration-300 flex items-center justify-center gap-2">
    <i class="fas fa-credit-card"></i> Bayar
  </button>

  <!-- Tombol Kembali -->
  <a href="../pages/Shopping.php" class="w-full md:w-auto">
    <button class="w-full md:w-auto bg-gray-500 text-white px-4 py-2 rounded-lg hover:bg-gray-600 transition duration-300 flex items-center justify-center gap-2">
      <i class="fas fa-arrow-left"></i> Kembali
    </button>
  </a>
</div>

<!-- Title Center for Online Payment -->
<!-- Bagian Tombol Bayar Online + Masukkan Alamat -->
<div class="mt-6 flex flex-col md:flex-row gap-3 items-center ">
  <h2 class="text-xl font-bold text-center mb-2 md:mb-0">Pembayaran Online</h2>

 <!-- Form Bayar Online ke Midtrans -->
<form action="get_midtrans.php" method="POST">
  <input type="hidden" name="amount" value="10000">
  <button type="submit" class="w-full md:w-auto bg-blue-500 text-white px-4 py-2 rounded-lg hover:bg-blue-600 transition duration-300 flex items-center justify-center gap-2">
    <i class="fas fa-credit-card"></i> Bayar Online
  </button>
</form>


  <!-- Tombol Masukkan Alamat -->
  <!-- <button onclick="document.getElementById('alamatModal').classList.remove('hidden')"
    class="w-full md:w-auto bg-green-500 text-white px-4 py-2 rounded-lg hover:bg-green-600 transition duration-300">
    Masukkan Alamat
  </button>
</div> -->

<!-- Modal Masukkan Alamat -->
<div id="alamatModal" class="fixed inset-0 bg-black bg-opacity-50 flex items-center justify-center hidden z-50">
  <div class="bg-white p-6 rounded-lg w-full max-w-md relative">
    <button onclick="document.getElementById('alamatModal').classList.add('hidden')"
      class="absolute top-2 right-2 text-gray-600 hover:text-gray-800 text-lg">✖</button>
    
    <h3 class="text-xl font-bold mb-4 text-center">Masukkan Alamat Anda</h3>
    
    <form onsubmit="return kirimKeWhatsApp(event)">
      <input type="text" id="nama" placeholder="Nama Lengkap" required
        class="w-full mb-3 px-4 py-2 border rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500">
      <textarea id="alamat" placeholder="Alamat Lengkap" required rows="4"
        class="w-full mb-3 px-4 py-2 border rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500"></textarea>
      
      <button type="submit"
        class="w-full bg-green-600 text-white px-4 py-2 rounded-md hover:bg-green-700 transition">Kirim ke WhatsApp</button>
    </form>
  </div>
</div>

<script>
  function kirimKeWhatsApp(e) {
    e.preventDefault();
    const nama = document.getElementById('nama').value.trim();
    const alamat = document.getElementById('alamat').value.trim();
    const noWA = '6285835116946'; // ← Ganti dengan nomor WhatsApp Anda (gunakan format internasional tanpa +)
    const pesan = `Halo, saya sudah memesan dengan alamat sebagai berikut:\n\nNama: ${nama}\nAlamat: ${alamat}`;

    const url = `https://wa.me/${noWA}?text=${encodeURIComponent(pesan)}`;
    window.open(url, '_blank');

    // Tutup modal setelah mengirim
    document.getElementById('alamatModal').classList.add('hidden');
    return false;
  }
</script>





  <!-- Modal Pembayaran -->
  <div id="payment-modal" class="hidden fixed inset-0 bg-black bg-opacity-50 flex items-center justify-center p-4 z-50">
    <div class="bg-white p-6 rounded-lg shadow-lg w-full max-w-md">
      <div class="flex justify-between items-center mb-4">
        <h3 class="text-xl font-bold">
          <i class="fas fa-file-invoice-dollar"></i> Masukkan Detail Pembayaran
        </h3>
        <button onclick="toggleModal()" class="text-red-600 text-lg font-bold hover:text-red-800">&times;</button>
      </div>

      <form id="paymentForm" action="payment.php" method="POST" enctype="multipart/form-data" target="_blank">
        <div class="mb-4">
          <label for="buyer_name" class="block text-gray-700">🧑 Nama Kamu</label>
          <input type="text" name="buyer_name" id="buyer_name" class="w-full border px-4 py-2 rounded-lg" required>
        </div>

        <div class="mb-4">
          <label for="buyer_email" class="block text-gray-700">📧 Alamat</label>
          <input type="text" name="buyer_email" id="buyer_email" class="w-full border px-4 py-2 rounded-lg" required>
        </div>

        <div class="mb-4">
          <label for="payment_amount" class="block text-gray-700">💰 Jumlah Pembayaran</label>
          <!-- Menghapus readonly agar jumlah bisa diinput oleh pengguna -->
          <input type="number" name="payment_amount" id="payment_amount" class="w-full border px-4 py-2 rounded-lg" value="" required>
        </div>

        <!-- <div class="mb-4">
          <label for="payment_proof" class="block text-gray-700">🖼️ Upload Bukti Pembayaran</label>
          <input type="file" name="payment_proof" id="payment_proof" accept="image/*" class="w-full border px-4 py-2 rounded-lg" required>
        </div> -->

        <div class="flex justify-between gap-2">
          <button type="submit" name="generate_pdf" class="bg-green-500 text-white px-4 py-2 rounded-lg hover:bg-green-600 transition duration-300 flex items-center gap-2">
            <i class="fas fa-print"></i> Cetak PDF
          </button>

          <!-- <button type="button" onclick="sendToWhatsApp()" class="bg-blue-500 text-white px-4 py-2 rounded-lg hover:bg-blue-600 transition duration-300 flex items-center gap-2">
            <i class="fab fa-whatsapp"></i> Kirim ke WA
          </button> -->
        </div>
      </form>
    </div>
  </div>

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
