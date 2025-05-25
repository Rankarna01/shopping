<?php
session_start(); // WAJIB ditaruh paling atas!

require_once '../config/db.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $username = $_POST['username'];
    $password = $_POST['password'];

    // Check admin credentials
    if ($username === 'admin' && $password === 'admin') {
        $_SESSION['user_id'] = 'admin'; // Bisa diatur sesuai kebutuhan
        header("Location: ../pages/dashboard.php");
        exit;
    }

    // Check user credentials
    $query = "SELECT * FROM users WHERE username = ?";
    $stmt = $conn->prepare($query);
    $stmt->bind_param("s", $username);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows > 0) {
        $user = $result->fetch_assoc();
        if (password_verify($password, $user['password'])) {
            $_SESSION['user_id'] = $user['id']; // ✅ Simpan user_id di session
            header("Location: ../pages/Shopping.php");
            exit;
        }
    }
    $error = "Username atau password salah.";
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1" />
  <title>Login</title>
  <link href="https://cdn.jsdelivr.net/npm/tailwindcss@2.2.19/dist/tailwind.min.css" rel="stylesheet" />
  <style>
    body {
      background-image: url('../images/banner-login.jpg');
      background-size: cover;
      background-position: center;
    }

    /* Glassmorphism tanpa @apply */
    .glass {
      backdrop-filter: blur(10px);
      background-color: rgba(255, 255, 255, 0.2);
      border-radius: 0.5rem; /* rounded-lg */
      box-shadow: 0 10px 15px -3px rgba(0, 0, 0, 0.1),
        0 4px 6px -2px rgba(0, 0, 0, 0.05);
    }
  </style>
</head>

<body class="min-h-screen flex items-center justify-center">
  <div
    class="flex flex-col md:flex-row w-11/12 max-w-4xl shadow-lg rounded-lg overflow-hidden">
    <!-- Left Image -->
    <div
      class="hidden md:block md:w-1/2 bg-cover bg-center"
      style="background-image: url('../images/hero.jpg');"
      aria-label="Hero Image"
    ></div>

    <!-- Right Form -->
    <div class="w-full md:w-1/2 p-10 glass flex flex-col justify-center">
      <form method="POST" class="space-y-6 text-white">
        <h2 class="text-4xl font-bold text-center mb-6">Login</h2>

        <?php if (isset($error)) : ?>
        <p class="text-red-500 text-center"><?php echo $error; ?></p>
        <?php endif; ?>

        <input
          type="text"
          name="username"
          placeholder="Username"
          required
          class="w-full px-4 py-3 rounded bg-gray-700 bg-opacity-75 placeholder-gray-300 focus:outline-none focus:ring-2 focus:ring-green-400 transition"
        />
        <input
          type="password"
          name="password"
          placeholder="Password"
          required
          class="w-full px-4 py-3 rounded bg-gray-700 bg-opacity-75 placeholder-gray-300 focus:outline-none focus:ring-2 focus:ring-green-400 transition"
        />
        <button
          type="submit"
          class="w-full py-3 rounded bg-green-600 hover:bg-green-700 transition text-white font-semibold"
        >
          Login
        </button>

        <p class="text-center text-gray-200 text-sm">
          Belum memiliki akun?
          <a href="register.php" class="text-green-400 hover:underline font-medium">Buat akun di sini</a>
        </p>
      </form>
    </div>
  </div>
</body>

</html>
