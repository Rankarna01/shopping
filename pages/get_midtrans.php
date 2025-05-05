<?php
session_start();
require '../config/db.php'; // Pastikan file ini berisi koneksi $conn

// Pastikan user sudah login
if (!isset($_SESSION['user_id'])) {
    die('Anda harus login terlebih dahulu.');
}

// Ambil ID user dari sesi login
$userId = $_SESSION['user_id'];

// Server Key Midtrans
$serverKey = 'SB-Mid-server-szbIqYYmTDOcvWam9rUSt1hM';

// Ambil data checkout user yang belum dibayar
$sql = "SELECT c.id AS checkout_id, p.name, p.price, c.quantity
        FROM checkouts c
        JOIN products p ON c.product_id = p.id
        WHERE c.user_id = ? AND c.status = 'unpaid'";  // Ambil berdasarkan user_id
$stmt = $conn->prepare($sql);
$stmt->bind_param("i", $userId);
$stmt->execute();
$result = $stmt->get_result();

// Hitung total dan siapkan item
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

if ($total_price <= 0) {
    die('Tidak ada item yang perlu dibayar.');
}

// Siapkan data transaksi untuk Midtrans
$transaction = [
    'transaction_details' => [
        'order_id' => 'ORDER-' . time(),
        'gross_amount' => $total_price
    ],
    'item_details' => $items,
    'customer_details' => [
        'first_name' => 'User',  // Sesuaikan dengan data pengguna yang login
        'last_name' => 'Online', // Sesuaikan dengan data pengguna yang login
        'email' => 'user@example.com',  // Sesuaikan dengan email user yang login
        'phone' => '08123456789'  // Sesuaikan dengan nomor telepon user yang login
    ]
];

// Kirim request ke Midtrans
$ch = curl_init();
curl_setopt($ch, CURLOPT_URL, 'https://app.sandbox.midtrans.com/snap/v1/transactions');
curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
curl_setopt($ch, CURLOPT_POST, 1);
curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($transaction));
curl_setopt($ch, CURLOPT_HTTPHEADER, [
    'Content-Type: application/json',
    'Accept: application/json',
    'Authorization: Basic ' . base64_encode($serverKey . ':')
]);

$response = curl_exec($ch);
if (curl_errno($ch)) {
    echo 'Curl error: ' . curl_error($ch);
    exit;
}
curl_close($ch);

// Redirect ke halaman pembayaran Midtrans
$result = json_decode($response, true);
if (isset($result['redirect_url'])) {
    header("Location: " . $result['redirect_url']);
    exit;
} else {
    echo 'Gagal membuat transaksi Midtrans: ' . $response;
}
?>
