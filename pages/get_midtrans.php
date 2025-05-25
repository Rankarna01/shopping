<?php
session_start();
require '../config/db.php'; // Pastikan koneksi $conn tersedia

// ✅ Midtrans Server Key
$serverKey = 'SB-Mid-server-szbIqYYmTDOcvWam9rUSt1hM';


$sql = "SELECT c.id AS checkout_id, p.name, p.price, c.quantity
        FROM checkouts c
        JOIN products p ON c.product_id = p.id
        WHERE c.status = 'unpaid'"; // Mengambil data tanpa user_id

$stmt = $conn->prepare($sql);
$stmt->execute();
$result = $stmt->get_result();

$items = [];
$total_price = 0;

while ($row = $result->fetch_assoc()) {
    $item_total = $row['price'] * $row['quantity']; // Hitung total per item
    $total_price += $item_total; // Tambahkan ke total harga keseluruhan

    // Simpan item dalam array untuk transaksi Midtrans
    $items[] = [
        'id' => 'checkout-' . $row['checkout_id'], // Tambahkan prefix untuk ID unik
        'price' => (int) $row['price'], // Pastikan harga dalam bentuk integer
        'quantity' => (int) $row['quantity'], // Pastikan quantity dalam bentuk integer
        'name' => $row['name'] // Nama produk
    ];
}

// ✅ Validasi jika tidak ada item
if (count($items) === 0 || $total_price <= 0) {
    die('Tidak ada item yang perlu dibayar.');
}

// ✅ Siapkan data transaksi untuk Midtrans
$transaction = [
    'transaction_details' => [
        'order_id' => 'ORDER-' . uniqid(), // ID unik untuk order
        'gross_amount' => $total_price // Total harga dari semua item
    ],
    'item_details' => $items, // Daftar item yang akan dibayar
    'customer_details' => [
        'first_name' => 'User', // Nama depan pengguna (bisa diubah sesuai data login)
        'last_name' => 'Online', // Nama belakang pengguna (bisa diubah sesuai data login)
        'email' => 'user@example.com', // Email pengguna (bisa diubah sesuai data login)
        'phone' => '08123456789' // Nomor telepon pengguna (bisa diubah sesuai data login)
    ]
];

// ✅ Kirim data transaksi ke Midtrans menggunakan cURL
$ch = curl_init();
curl_setopt($ch, CURLOPT_URL, 'https://app.sandbox.midtrans.com/snap/v1/transactions');
curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
curl_setopt($ch, CURLOPT_POST, 1);
curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($transaction)); // Kirim data transaksi dalam format JSON
curl_setopt($ch, CURLOPT_HTTPHEADER, [
    'Content-Type: application/json',
    'Accept: application/json',
    'Authorization: Basic ' . base64_encode($serverKey . ':') // Gunakan Server Key Midtrans
]);

$response = curl_exec($ch); // Eksekusi cURL
if (curl_errno($ch)) {
    echo 'Curl error: ' . curl_error($ch); // Tangani jika terjadi error pada cURL
    exit;
}
curl_close($ch); // Tutup koneksi cURL

// ✅ Tangani respons dari Midtrans
$result = json_decode($response, true);
if (isset($result['redirect_url'])) {
    header("Location: " . $result['redirect_url']); // Arahkan ke URL Midtrans untuk pembayaran
    exit;
} else {
    echo 'Gagal membuat transaksi Midtrans: ' . $response; // Jika gagal
}
?>
