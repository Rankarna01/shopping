<?php
require_once '../config/db.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $email = $_POST['email'];
    $username = $_POST['username'];
    $alamat = $_POST['alamat'];
    $password = password_hash($_POST['password'], PASSWORD_BCRYPT);

    $query = "INSERT INTO users (email, username, alamat, password, role) 
              VALUES ('$email', '$username', '$alamat', '$password', 'user')";
    if ($conn->query($query)) {
        header("Location: login.php");
        exit;
    } else {
        echo "Error: " . $conn->error;
    }
}
?>
<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1" />
  <title>Register</title>
  <link href="https://cdn.jsdelivr.net/npm/tailwindcss@2.2.19/dist/tailwind.min.css" rel="stylesheet" />
  <style>
    body {
      background-image: url('../images/banner-login.jpg');
      background-size: cover;
      background-position: center;
    }

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
    class="flex flex-col md:flex-row w-11/12 max-w-4xl shadow-lg rounded-lg overflow-hidden bg-transparent">
    <!-- Left Image -->
    <div
      class="hidden md:block md:w-1/2 bg-cover bg-center"
      style="background-image: url('../images/hero.jpg');"
      aria-label="Hero Image"
    ></div>

    <!-- Right Form -->
    <div class="w-full md:w-1/2 p-8 glass flex flex-col justify-center">
      <form method="POST" class="text-white space-y-5">
        <h2 class="text-3xl font-bold mb-6 text-center">Register</h2>

        <input type="email" name="email" placeholder="Email" required
          class="w-full p-3 rounded bg-gray-700 bg-opacity-70 placeholder-gray-300 focus:outline-none focus:ring-2 focus:ring-green-400 transition" />

        <input type="text" name="username" placeholder="Username" required
          class="w-full p-3 rounded bg-gray-700 bg-opacity-70 placeholder-gray-300 focus:outline-none focus:ring-2 focus:ring-green-400 transition" />

        <textarea name="alamat" placeholder="Alamat Lengkap" required rows="3"
          class="w-full p-3 rounded bg-gray-700 bg-opacity-70 placeholder-gray-300 focus:outline-none focus:ring-2 focus:ring-green-400 transition resize-none"></textarea>

        <input type="password" name="password" placeholder="Password" required
          class="w-full p-3 rounded bg-gray-700 bg-opacity-70 placeholder-gray-300 focus:outline-none focus:ring-2 focus:ring-green-400 transition" />

        <button type="submit"
          class="w-full bg-green-600 py-3 rounded text-white hover:bg-green-700 transition font-semibold">
          Register
        </button>

        <p class="text-center text-gray-200 text-sm">
          Sudah Memiliki Akun?
          <a href="login.php" class="text-green-400 hover:underline font-medium">Login Disini</a>
        </p>
      </form>
    </div>
  </div>
</body>

</html>
