<?php
include '../config/db.php';

// Ambil data produk dari database
$query = "SELECT * FROM products ORDER BY created_at DESC";
$result = $conn->query($query);
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <script src="https://cdn.tailwindcss.com"></script>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <title>Daftar Produk</title>
</head>

<body class="bg-gray-100">

    <?php include('../components/sidebar.php'); ?>

    <div class="p-8 ml-64">
        <h1 class="text-2xl font-bold mb-6">Daftar Produk</h1>

        <div class="bg-white shadow-md rounded-lg overflow-hidden">
            <table class="min-w-full bg-white">
                <thead>
                    <tr class="bg-gray-800 text-white">
                        <th class="text-left py-3 px-4 uppercase font-semibold text-sm">Id</th>
                        <th class="text-left py-3 px-4 uppercase font-semibold text-sm">Nama</th>
                        <th class="text-left py-3 px-4 uppercase font-semibold text-sm">Harga</th>
                        <th class="text-left py-3 px-4 uppercase font-semibold text-sm">Stok</th>
                        <th class="text-left py-3 px-4 uppercase font-semibold text-sm">Gambar</th>
                        <th class="text-left py-3 px-4 uppercase font-semibold text-sm">Tanggal Ditambahkan</th>
                        <th class="text-left py-3 px-4 uppercase font-semibold text-sm">Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    <?php if ($result->num_rows > 0): ?>
                    <?php $no = 1; while ($row = $result->fetch_assoc()): ?>
                    <tr class="border-b hover:bg-gray-100">
                        <td class="py-3 px-4"><?= $no++; ?></td>
                        <td class="py-3 px-4"><?= htmlspecialchars($row['name']); ?></td>
                        <td class="py-3 px-4">Rp <?= number_format($row['price'], 0, ',', '.'); ?></td>
                        <td class="py-3 px-4"><?= htmlspecialchars($row['stock']); ?></td>
                        <td class="py-3 px-4">
                            <img src="../uploads/<?= htmlspecialchars($row['image']); ?>"
                                alt="<?= htmlspecialchars($row['name']); ?>" class="h-16 w-16 object-cover rounded-lg">
                        </td>
                        <td class="py-3 px-4"><?= $row['created_at']; ?></td>
                        <td class="py-3 px-4 flex space-x-2">
                            <button
                                onclick="openEditModal(<?= $row['id']; ?>, '<?= htmlspecialchars($row['name']); ?>', <?= $row['price']; ?>, '<?= htmlspecialchars($row['stock']); ?>')"
                                class="bg-yellow-500 text-white px-3 py-1 rounded hover:bg-yellow-600 text-sm">Edit</button>
                            <button onclick="confirmDelete(<?= $row['id']; ?>)"
                                class="bg-red-500 text-white px-3 py-1 rounded hover:bg-red-600 text-sm">Hapus</button>
                        </td>
                    </tr>
                    <?php endwhile; ?>
                    <?php else: ?>
                    <tr>
                        <td colspan="7" class="text-center py-4">Tidak ada data produk.</td>
                    </tr>
                    <?php endif; ?>
                </tbody>
            </table>
        </div>
    </div>

    <!-- Modal Edit -->
    <div id="editModal" class="fixed inset-0 bg-gray-800 bg-opacity-50 flex items-center justify-center hidden">
        <div class="bg-white p-6 rounded-lg shadow-lg w-1/3">
            <h2 class="text-xl font-bold mb-4">Edit Produk</h2>
            <form id="editForm" method="POST" action="update_product.php">
                <input type="hidden" name="id" id="editId">
                <div class="mb-4">
                    <label for="editName" class="block text-sm font-medium text-gray-700">Nama Produk</label>
                    <input type="text" name="name" id="editName"
                        class="mt-1 block w-full rounded-lg border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500">
                </div>
                <div class="mb-4">
                    <label for="editPrice" class="block text-sm font-medium text-gray-700">Harga</label>
                    <input type="number" name="price" id="editPrice"
                        class="mt-1 block w-full rounded-lg border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500">
                </div>
                <div class="mb-4">
                    <label for="editStock" class="block text-sm font-medium text-gray-700">Stok</label>
                    <input type="text" name="stock" id="editStock"
                        class="mt-1 block w-full rounded-lg border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500">
                </div>
                <div class="flex justify-end">
                    <button type="button" onclick="closeEditModal()"
                        class="bg-gray-500 text-white px-4 py-2 rounded hover:bg-gray-600 mr-2">Batal</button>
                    <button type="submit"
                        class="bg-blue-500 text-white px-4 py-2 rounded hover:bg-blue-600">Simpan</button>
                </div>
            </form>
        </div>
    </div>

    <script>
    function openEditModal(id, name, price, stock) {
        document.getElementById('editId').value = id;
        document.getElementById('editName').value = name;
        document.getElementById('editPrice').value = price;
        document.getElementById('editStock').value = stock;
        document.getElementById('editModal').classList.remove('hidden');
    }

    function closeEditModal() {
        document.getElementById('editModal').classList.add('hidden');
    }

    function confirmDelete(id) {
        Swal.fire({
            title: 'Anda yakin?',
            text: "Data produk akan dihapus secara permanen!",
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#d33',
            cancelButtonColor: '#3085d6',
            confirmButtonText: 'Ya, hapus!'
        }).then((result) => {
            if (result.isConfirmed) {
                window.location.href = `delete_product.php?id=${id}`;
            }
        });
    }
    </script>
</body>

</html>