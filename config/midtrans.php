<?php

// Mengambil data JSON yang dikirim oleh client
$data = json_decode(file_get_contents('php://input'), true);

// Cek apakah data ada dan valid
if ($data && isset($data['gross_amount']) && isset($data['first_name']) && isset($data['last_name']) && isset($data['email']) && isset($data['phone'])) {
    
    // Set parameter untuk transaksi
    $params = array(
        'transaction_details' => array(
            'order_id' => 'ORDER-' . rand(),
            'gross_amount' => (float)$data['gross_amount'], // Pastikan ini float
        ),
        'customer_details' => array(
            'first_name' => $data['first_name'],
            'last_name' => $data['last_name'],
            'email' => $data['email'],
            'phone' => $data['phone'],
        ),
    );

    // Pastikan gross_amount lebih dari atau sama dengan 0.01
    if ($params['transaction_details']['gross_amount'] < 0.01) {
        echo json_encode(['error' => 'gross_amount must be greater than or equal to 0.01']);
        exit;
    }

    // Inisialisasi Midtrans
    require_once '/path/to/Midtrans.php';  // Ganti dengan path yang sesuai
    \Midtrans\Config::$serverKey = 'SB-Mid-server-szbIqYYmTDOcvWam9rUSt1hM';  // Ganti dengan server key yang benar
    \Midtrans\Config::$isProduction = false;  // Set ke true jika sudah live

    try {
        // Dapatkan token Snap
        $snapToken = \Midtrans\Snap::getSnapToken($params);
        
        // Kirim token kembali ke client
        echo json_encode(['token' => $snapToken]);
    } catch (Exception $e) {
        // Tangani error dari Midtrans
        echo json_encode(['error' => $e->getMessage()]);
    }
} else {
    echo json_encode(['error' => 'Invalid input data']);
}
