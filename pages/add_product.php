<?php
include '../config/db.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
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
            // Redirect dengan parameter success untuk menunjukkan sukses
            header('Location: dashboard.php?success=1');
            echo 'Redirecting to: dashboard.php?success=1'; // Untuk debug
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
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script> <!-- Tambahkan SweetAlert -->
    <title>Admin Dashboard</title>
</head>

<body class="bg-gray-100">

    <?php include('../components/sidebar.php'); ?>

    <div class="p-8 ml-64">
        <h1 class="text-2xl font-bold mb-6">Tambah Produk</h1>

        <?php if (isset($_GET['success'])): ?>
        <script>
        Swal.fire({
            title: 'Produk Berhasil Ditambahkan!',
            text: 'Produk baru telah berhasil ditambahkan ke database.',
            icon: 'success',
            confirmButtonText: 'Ok',
            timer: 3000, // Auto redirect setelah 3 detik
        }).then(function() {
            window.location.href = 'dashboard.php'; // Mengarahkan ke dashboard.php setelah SweetAlert
        });
        </script>
        <?php endif; ?>


        <form action="add_product.php" method="POST" enctype="multipart/form-data"
            class="bg-white p-6 rounded-lg shadow-md space-y-6">
            <div class="mb-4">
                <label for="category" class="block text-sm font-medium text-gray-700">Kategori</label>
                <select name="category" id="category"
                    class="mt-1 block w-full rounded border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500">
                    <option value="Bolu">Bolu</option>
                    <option value="Kue-Kering">Kue kering</option>
                    <option value="Kue-Basah">Kue Basah</option>
                </select>
            </div>
            <div class="mb-4">
                <label for="name" class="block text-sm font-medium text-gray-700">Nama Produk</label>
                <input type="text" name="name" id="name" required
                    class="mt-1 block w-full rounded-lg border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500">
            </div>
            <div class="mb-4">
                <label for="price" class="block text-sm font-medium text-gray-700">Harga</label>
                <input type="number" name="price" id="price" required
                    class="mt-1 block w-full rounded-lg border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500">
            </div>
            <div class="mb-4">
                <label for="stock" class="block text-sm font-medium text-gray-700">Stok</label>
                <input type="text" name="stock" id="stock" required
                    class="mt-1 block w-full rounded-lg border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500">
            </div>
            <div class="mb-4">
                <label for="image" class="block text-sm font-medium text-gray-700">Upload Gambar</label>
                <div class="flex items-center space-x-4 mt-2">
                    <label for="image"
                        class="flex items-center cursor-pointer bg-indigo-600 text-white px-4 py-2 rounded-lg hover:bg-indigo-700">
                        <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"
                            xmlns="http://www.w3.org/2000/svg">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M16 14l-4-4m0 0l-4 4m4-4V3m0 6V9m0 12h3m-3-3H6m-3 0v3m0-3v-3m3 0H3"></path>
                        </svg>
                        Pilih File
                    </label>
                    <input type="file" name="image" id="image" accept="image/*" required class="hidden" />
                    <span class="text-gray-500" id="fileName">No file selected</span>
                </div>
            </div>
            <button type="submit"
                class="bg-indigo-600 text-white px-6 py-3 rounded-lg shadow hover:bg-indigo-700 focus:outline-none">
                Tambah Produk
            </button>
        </form>
    </div>

    <script>
    // Update the filename display when a file is chosen
    document.getElementById('image').addEventListener('change', function(e) {
        const fileName = e.target.files.length > 0 ? e.target.files[0].name : 'No file selected';
        document.getElementById('fileName').textContent = fileName;
    });
    </script>

</body>

</html>