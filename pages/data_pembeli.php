<?php
require_once '../config/db.php';

// Ambil data pembeli dari database
$query = "SELECT email, username, alamat FROM users WHERE role = 'user'";
$result = $conn->query($query);

?>
<!DOCTYPE html>
<html lang="en">

<head>
    <link href="https://cdn.jsdelivr.net/npm/tailwindcss@2.2.19/dist/tailwind.min.css" rel="stylesheet">
    <title>Data Pembeli</title>
</head>

<body class="bg-gray-100 text-gray-800">
    <?php include('../components/sidebar.php'); ?>

    <section class="container mx-auto px-4 py-8">
        <h1 class="text-3xl font-semibold mb-6 text-center">Data Pembeli</h1>

        <!-- Tabel Data Pembeli -->
        <?php if ($result->num_rows > 0): ?>
        <div class="overflow-x-auto max-w-screen-md mx-auto">
            <table class="w-full bg-white rounded-lg shadow-lg overflow-hidden">
                <thead class="bg-gray-700 text-gray-300">
                    <tr>
                        <th class="py-2 px-4 text-left">Nama</th>
                        <th class="py-2 px-4 text-left">Email</th>
                        <th class="py-2 px-4 text-left">Alamat</th>
                        <th class="py-2 px-4 text-left">Aksi</th>
                    </tr>
                </thead>
                <tbody class="text-gray-800">
                    <?php while ($row = $result->fetch_assoc()): ?>
                    <tr class="border-t border-gray-300 hover:bg-gray-200">
                        <td class="py-2 px-4"><?= $row['username']; ?></td>
                        <td class="py-2 px-4"><?= $row['email']; ?></td>
                        <td class="py-2 px-4"><?= $row['alamat']; ?></td>
                        <td class="py-2 px-4">
                            
                        </td>
                    </tr>
                    <?php endwhile; ?>
                </tbody>
            </table>
        </div>
        <?php else: ?>
        <p class="text-center text-gray-400">Tidak ada data pembeli.</p>
        <?php endif; ?>
    </section>
</body>

</html>
