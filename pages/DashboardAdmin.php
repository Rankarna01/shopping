<?php
include '../config/db.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Validasi data input
    $category = $_POST['category'];
    $name = $_POST['name'];
    $price = $_POST['price']; // Menambahkan variabel price
    $stock = $_POST['stock'];

    // Path folder uploads
    $uploadDir = '../uploads/';

    // Pastikan folder uploads ada
    if (!is_dir($uploadDir)) {
        mkdir($uploadDir, 0777, true); // Buat folder jika belum ada
    }

    // Proses upload gambar
    $image = $_FILES['image'];
    $imageName = time() . '-' . basename($image['name']);
    $imagePath = $uploadDir . $imageName;

    if (move_uploaded_file($image['tmp_name'], $imagePath)) {
        // Simpan data ke database
        $stmt = $conn->prepare("INSERT INTO products (category, name, price, stock, image) VALUES (?, ?, ?, ?, ?)");
        $stmt->bind_param('ssdss', $category, $name, $price, $stock, $imageName);

        if ($stmt->execute()) {
            header('Location: AdminDashboard.php?success=1');
            exit();
        } else {
            echo "Error: " . $stmt->error;
        }
    } else {
        echo "Gagal mengupload gambar. Pastikan folder 'uploads' memiliki izin yang benar.";
    }
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <script src="https://cdn.tailwindcss.com"></script>
    <title>Admin Dashboard</title>
</head>

<body class="bg-gray-100">

    <?php include('../components/sidebar.php'); ?>

    <div class="p-8 ml-64">
        <h1 class="text-2xl font-bold mb-6">Admin Dashboard</h1>
        <?php if (isset($_GET['success'])): ?>
        <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded relative mb-4" role="alert">
            <strong class="font-bold">Berhasil!</strong>
            <span class="block sm:inline">Produk berhasil ditambahkan.</span>
        </div>
        <?php endif; ?>
    </div>

</body>

</html>