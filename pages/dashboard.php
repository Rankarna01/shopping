<?php
include '../config/db.php';

// Ambil data jumlah produk per kategori
$sql = "SELECT category, COUNT(*) as product_count FROM products GROUP BY category";
$result = $conn->query($sql);

$categories = [];
$product_counts = [];

if ($result->num_rows > 0) {
    while ($row = $result->fetch_assoc()) {
        $categories[] = $row['category'];
        $product_counts[] = $row['product_count'];
    }
} else {
    $categories = ['No Data'];
    $product_counts = [0];
}

// Ambil total pendapatan dari transaksi paid
$sql_income = "SELECT SUM(p.price * c.quantity) as total_income
               FROM checkouts c
               JOIN products p ON c.product_id = p.id
               WHERE c.status = 'paid'";
$result_income = $conn->query($sql_income);
$total_income = 0;
if ($result_income && $row_income = $result_income->fetch_assoc()) {
    $total_income = $row_income['total_income'] ?? 0;
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <script src="https://cdn.tailwindcss.com"></script>
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <link
  href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css"
  rel="stylesheet"
/>
    <title>Admin Dashboard</title>
</head>

<body class="bg-gray-100">
    <?php include('../components/sidebar.php'); ?>

    <div class="ml-64 p-8 space-y-6">
  <h2 class="text-4xl font-bold text-gray-800">Dashboard Utama</h2>

  <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
    <div
      class="bg-white p-6 rounded-lg shadow-md flex items-center transition-transform transform hover:scale-105 hover:shadow-xl cursor-pointer"
    >
      <span class="text-blue-500 text-5xl mr-4">
  <i class="fa-solid fa-bread-slice"></i>
</span>

      <div>
        <h3 class="text-lg font-semibold text-gray-700">Jumlah Produk</h3>
        <p class="text-2xl font-bold text-gray-900"><?php echo array_sum($product_counts); ?> Produk</p>
      </div>
    </div>

    <div
      class="bg-white p-6 rounded-lg shadow-md flex items-center transition-transform transform hover:scale-105 hover:shadow-xl cursor-pointer"
    >
      <span class="text-blue-500 text-5xl mr-4">
  <i class="fa-solid fa-layer-group"></i>
</span>
      <div>
        <h3 class="text-lg font-semibold text-gray-700">Jumlah Kategori</h3>
        <p class="text-2xl font-bold text-gray-900"><?php echo count($categories); ?> Kategori</p>
      </div>
    </div>

    <div
      class="bg-white p-6 rounded-lg shadow-md flex items-center transition-transform transform hover:scale-105 hover:shadow-xl cursor-pointer"
    >
      <span class="text-blue-500 text-5xl mr-4">
  <i class="fa-solid fa-money-bill-wave"></i>
</span>
      <div>
        <h3 class="text-lg font-semibold text-gray-700">Total Pendapatan</h3>
        <p class="text-2xl font-bold text-gray-900">
          Rp <?php echo number_format($total_income, 0, ',', '.'); ?>
        </p>
      </div>
    </div>
  </div>

  <div class="bg-white p-6 rounded-lg shadow-md">
    <h3 class="text-xl font-semibold text-gray-800">Jumlah Produk per Kategori</h3>
    <canvas id="categoryChart"></canvas>
  </div>
</div>


    <script>
  var ctx = document.getElementById('categoryChart').getContext('2d');
  var categoryChart = new Chart(ctx, {
    type: 'bar',
    data: {
      labels: <?php echo json_encode($categories); ?>,
      datasets: [{
        label: 'Jumlah Produk',
        data: <?php echo json_encode($product_counts); ?>,
        backgroundColor: 'rgba(34, 197, 94, 0.5)',      // Tailwind green-500 transparent
        borderColor: 'rgba(21, 128, 61, 1)',            // Tailwind green-700 solid
        borderWidth: 1,
        hoverBackgroundColor: 'rgba(34, 197, 94, 0.7)', // Lebih gelap saat hover
        hoverBorderColor: 'rgba(21, 128, 61, 1)',
      }]
    },
    options: {
      responsive: true,
      animation: { duration: 1500 },
      scales: {
        x: { grid: { display: false } },
        y: { 
          beginAtZero: true, 
          grid: { color: 'rgba(0, 0, 0, 0.1)' },
          ticks: { color: '#166534' }  // warna hijau gelap untuk angka skala y
        }
      },
      plugins: { 
        legend: { display: false },
        tooltip: {
          backgroundColor: 'rgba(34, 197, 94, 0.9)', // tooltip background hijau
          titleColor: '#ffffff',
          bodyColor: '#ffffff',
        }
      }
    }
  });
</script>

</body>
</html>