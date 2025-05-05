<?php
include '../config/db.php';

// Hitung jumlah barang checkout yang belum dibayar
$sql = "SELECT COUNT(*) AS total FROM checkouts WHERE status = 'unpaid'";
$result = $conn->query($sql);
$notification = $result->fetch_assoc()['total'];
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Navbar</title>
    <link href="https://cdn.jsdelivr.net/npm/tailwindcss@2.2.19/dist/tailwind.min.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css" rel="stylesheet">
</head>

<body class="bg-v-100">
    <script src="https://cdn.jsdelivr.net/gh/alpinejs/alpine@v2.x.x/dist/alpine.min.js" defer></script>
    <nav class="bg-white shadow-lg">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="flex justify-between h-16 items-center">
                <!-- Logo -->
                <a href="#" class="flex items-center space-x-2">
                    <img src="../images/logo.png" alt="Logo" class="h-10">
                    <span class="text-lg font-semibold text-gray-900">TOKO ONLINE</span>
                </a>
                
                <!-- Menu -->
                <div class="hidden md:flex space-x-6">
                    <a href="#" class="text-gray-700 hover:text-blue-900 font-bold">Home</a>
                    <a href="#product" class="text-gray-700 hover:text-blue-900 font-bold">Product</a>
                    <a href="../auth/login.php" class="text-blue-900 hover:text-blue-800 font-bold">Logout</a>
                    <a href="../pages/checkout.php" class="relative flex items-center">
                        <i class="fas fa-shopping-cart text-xl"></i>
                        <?php if ($notification > 0): ?>
                            <span class="absolute top-0 right-0 inline-flex items-center justify-center px-2 py-1 text-xs font-bold text-white bg-red-500 rounded-full">
                                <?= $notification ?>
                            </span>
                        <?php endif; ?>
                    </a>
                </div>

                <!-- Mobile Menu Button -->
                <button class="md:hidden focus:outline-none" @click="open = !open">
                    <i class="fas fa-bars text-xl"></i>
                </button>
            </div>
        </div>
    </nav>
</body>
</html>