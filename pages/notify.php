<?php
require '../config/db.php'; // Koneksi database

// Ambil body notifikasi dari Midtrans
$input = file_get_contents('php://input');
$notification = json_decode($input, true);

if (!$notification) {
    http_response_code(400);
    echo 'Invalid notification';
    exit;
}

$order_id = $notification['order_id'] ?? null;
$transaction_status = $notification['transaction_status'] ?? null;

if ($order_id) {
    if ($transaction_status == 'capture' || $transaction_status == 'settlement') {
        // Update status checkout jadi 'paid'
        $stmt = $conn->prepare("UPDATE checkouts SET status = 'paid' WHERE order_id = ?");
        $stmt->bind_param('s', $order_id);
        $stmt->execute();
        $stmt->close();

        http_response_code(200);
        echo 'OK';
        exit;
    } else if ($transaction_status == 'cancel' || $transaction_status == 'deny' || $transaction_status == 'expire') {
        // Update status checkout jadi 'failed' atau hapus sesuai kebutuhan
        $stmt = $conn->prepare("UPDATE checkouts SET status = 'failed' WHERE order_id = ?");
        $stmt->bind_param('s', $order_id);
        $stmt->execute();
        $stmt->close();

        http_response_code(200);
        echo 'OK';
        exit;
    }
}

http_response_code(400);
echo 'Bad Request';
