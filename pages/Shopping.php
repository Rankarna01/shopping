<?php
include '../config/db.php';

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

$sql = "SELECT id, category, name, price, stock, image FROM products";
$result = $conn->query($sql);

if (!$result) {
    die("Query Error: " . $conn->error);
}

$products = [];
$categories = [];
if ($result->num_rows > 0) {
    while ($row = $result->fetch_assoc()) {
        $products[] = $row;
        if (!in_array($row['category'], $categories)) {
            $categories[] = $row['category'];
        }
    }
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <link href="https://cdn.jsdelivr.net/npm/tailwindcss@2.2.19/dist/tailwind.min.css" rel="stylesheet">
    <title>Shopping</title>
    <link rel="stylesheet" href="../global.css">
    <link rel="stylesheet" href="https://unpkg.com/aos@next/dist/aos.css" />
</head>

<body class="bg-white text-gray-800">
    <?php include '../components/Navbar.php'; ?>

    <section class="relative overflow-hidden rounded-lg shadow-lg">
        <!-- <img src="../images/bg.jpg" alt="Hero" class="w-full h-[200px] object-cover rounded-t-lg">
        <div class="absolute inset-0 bg-black opacity-50 rounded-t-lg"></div>
        <div class="absolute inset-0 flex justify-center items-center text-white">
            <h2 class="text-3xl font-bold">Selamat Datang Di Store SHOPPING SHOP</h2>
        </div> -->
        <?php
        include '../components/HeroSection.php';
        ?>


    </section>

    <section class="bg-white py-12" id="product">
        <div class="container mx-auto px-4">
            <h2 class="text-2xl font-bold mb-8 text-center text-gray-800">Kategori Produk</h2>

            <div class="flex flex-wrap gap-3 justify-center mb-8">
                <button onclick="filterCategory('all')" class="bg-green-600 text-gray-100 px-5 py-2 rounded-full hover:bg-gray-300 transition">
                    Lihat Semua
                </button>
                <?php foreach ($categories as $category): ?>
                    <button onclick="filterCategory('<?= $category; ?>')" class="bg-green-600 text-gray-100 px-5 py-2 rounded-full hover:bg-gray-300 transition">
                        <?= ucfirst($category); ?>
                    </button>
                <?php endforeach; ?>
            </div>

            <div id="product-list" class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 lg:grid-cols-4 gap-4 bg-green-50 p-4 rounded-lg">
                <?php foreach ($products as $product): ?>
                    <div class="bg-green p-4 rounded-lg shadow-lg transition duration-300 hover:shadow-xl hover:scale-105 product-item"
                        data-category="<?= $product['category']; ?>">

                        <img src="../uploads/<?= $product['image']; ?>" alt="<?= $product['name']; ?>"
                            class="w-full h-36 object-cover rounded-md cursor-pointer transition-transform duration-300 hover:scale-110"
                            onclick="openImageModal('../uploads/<?= $product['image']; ?>', '<?= $product['name']; ?>')">

                        <h3 class="text-sm font-semibold mt-2"><?= $product['name']; ?></h3>
                        <p class="text-xs text-gray-600">Kategori: <?= $product['category']; ?></p>
                        <p class="text-xs text-gray-600">Stok: <?= $product['stock']; ?></p>
                        <p class="text-sm text-red-600 font-bold mt-2">Rp <?= number_format($product['price']); ?></p>

                        <div class="mt-3 text-left">
                            <button onclick="openModal(<?= $product['id']; ?>, '<?= $product['name']; ?>', <?= $product['stock']; ?>)"
                                class="bg-green-500 text-white text-sm px-4 py-2 rounded-lg transition duration-300 hover:bg-green-800 hover:scale-105">
                                Beli
                            </button>
                        </div>
                    </div>
                <?php endforeach; ?>
            </div>



            <!-- Modal Gambar -->
            <div id="imageModal" class="fixed inset-0 bg-black bg-opacity-50 flex items-center justify-center hidden z-50">
                <div class="bg-white p-6 rounded-lg shadow-lg max-w-md w-full relative">
                    <button class="absolute top-2 right-2 text-gray-500 hover:text-gray-800 text-xl" onclick="closeImageModal()">×</button>
                    <img id="modalImage" src="" alt="Product Image" class="w-full h-auto rounded-lg">
                    <h3 id="modalTitle" class="text-lg font-semibold mt-4 text-center text-gray-800"></h3>
                </div>
            </div>

            <script>
                function openImageModal(imageSrc, productName) {
                    document.getElementById('modalImage').src = imageSrc;
                    document.getElementById('modalTitle').innerText = productName;
                    document.getElementById('imageModal').classList.remove('hidden');
                }

                function closeImageModal() {
                    document.getElementById('imageModal').classList.add('hidden');
                }
            </script>
        </div>
    </section>


    <div id="modal" class="hidden fixed inset-0 bg-black bg-opacity-50 flex items-center justify-center z-50 transition duration-300 ease-in-out">
        <div class="bg-white p-6 rounded-xl w-11/12 max-w-md shadow-2xl transform transition-all duration-300">

            <!-- Modal Header -->
            <div class="flex items-center justify-between border-b pb-3 mb-4">
                <h2 id="modal-title" class="text-2xl font-semibold text-gray-800 flex items-center gap-2">
                    <svg class="w-6 h-6 text-green-600" fill="none" stroke="currentColor" stroke-width="2"
                        viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round"
                            d="M3 3h18v4H3V3zm0 6h18v4H3V9zm0 6h18v4H3v-4z" />
                    </svg>
                    Checkout
                </h2>
            </div>

            <!-- Form -->
            <form id="checkout-form" action="checkout.php" method="POST" class="space-y-4">
                <input type="hidden" name="product_id" id="product-id">

                <p id="modal-stock" class="text-sm text-gray-500">Stok tersedia: ...</p>

                <div>
                    <label for="quantity" class="block text-gray-700 font-medium">Jumlah</label>
                    <input type="number" name="quantity" id="quantity" min="1" max="" required
                        class="w-full mt-1 px-4 py-2 border rounded-lg focus:ring-2 focus:ring-green-400 focus:outline-none transition">
                </div>

                <div class="flex justify-end space-x-3 pt-4 border-t mt-4">
                    <button type="submit"
                        class="bg-green-500 text-white px-4 py-2 rounded-lg hover:bg-green-600 transition font-medium">
                        Checkout
                    </button>
                    <button type="button" onclick="closeModal()"
                        class="bg-red-500 text-white px-4 py-2 rounded-lg hover:bg-red-600 transition font-medium">
                        Cancel
                    </button>
                </div>
            </form>
        </div>
    </div>



    <?php
    include '../components/Footer.php';
    ?>



    <script>
        function filterCategory(category) {
            const productItems = document.querySelectorAll('.product-item');
            productItems.forEach(item => {
                if (category === 'all' || item.dataset.category === category) {
                    item.style.display = 'block';
                } else {
                    item.style.display = 'none';
                }
            });
        }

        function openModal(productId, productName, stock) {
            document.getElementById('modal').classList.remove('hidden');
            document.getElementById('product-id').value = productId;
            document.getElementById('modal-title').textContent = `Checkout - ${productName}`;
            document.getElementById('modal-stock').textContent = `Stock available: ${stock}`;
            document.getElementById('quantity').max = stock;
        }

        function closeModal() {
            document.getElementById('modal').classList.add('hidden');
        }
    </script>
    <script src="https://unpkg.com/aos@next/dist/aos.js"></script>
    <script>
        AOS.init();
    </script>
</body>

</html>