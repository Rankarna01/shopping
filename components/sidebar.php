<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
    <link rel="stylesheet" href="path/to/font-awesome/css/font-awesome.min.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">

    <style>
        .menu-item {
            display: flex;
            align-items: center;
        }

        .menu-icon {
            margin-right: 10px;
        }
    </style>
</head>

<body>
    <div class="fixed inset-y-0 left-0 bg-gradient-to-b from-gray-800 via-gray-700 to-green-900 text-white w-54 p-4">
        <h1 class="text-2xl font-bold mb-6">Admin Dashboard</h1>
        <ul>
            <li>
                <a href="../pages/dashboard.php" class="block py-2 px-4 rounded hover:bg-green-200 menu-item">
                    <i class="fa fa-home menu-icon"></i>Home
                </a>
            </li>
            <li>
                <a href="../pages/add_product.php" class="block py-2 px-4 rounded hover:bg-green-200 menu-item">
                    <i class="fa fa-plus menu-icon"></i>Tambah Produk
                </a>
            </li>
            <li>
                <a href="../pages/daftar_produk.php" class="block py-2 px-4 rounded hover:bg-green-200 menu-item">
                    <i class="fa fa-list menu-icon"></i>Daftar Produk
                </a>
            </li>
            <li>
                <a href="data_penjualan.php" class="block py-2 px-4 rounded hover:bg-green-200 menu-item">
                    <i class="fas fa-chart-bar menu-icon"></i>Data Penjualan
                </a>
            </li>

            <a href="data_pembeli.php" class="block py-2 px-4 rounded hover:bg-green-200 menu-item">
                <i class="fa fa-users menu-icon"></i>Data users
            </a>
            </li>
            <li>
                <a href="shopping.php" class="block py-2 px-4 rounded hover:bg-green-200 menu-item">
                    <i class="fa fa-shopping-cart menu-icon"></i>Lihat Produk
                </a>
            </li>
            <li>
                <a href="../pages/posts.php" class="block py-2 px-4 rounded hover:bg-green-200 menu-item">
                    <i class="fa-solid fa-receipt menu-icon"></i>
                    <span style="margin-left:10px;">POST Kasir</span>
                </a>
            </li>
        </ul>
    </div>
</body>

</html>