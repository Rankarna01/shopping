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
    <link href="https://cdn.jsdelivr.net/npm/tailwindcss@2.2.19/dist/tailwind.min.css" rel="stylesheet">
    <style>
        body {
            background-image: url('../images/bg-login.jpg');
            background-size: cover;
            background-position: center;
        }

        .glass {
            backdrop-filter: blur(10px);
            background: rgba(255, 255, 255, 0.2);
        }
    </style>
    <title>Register</title>
</head>

<body class="flex items-center justify-center h-screen">
    <div class="flex w-4/5 lg:w-3/5 bg-opacity-0 shadow-lg rounded-lg overflow-hidden">
        <!-- Left Image -->
        <div class="hidden md:block w-1/2 bg-cover" style="background-image: url('../images/toko.jpg');"></div>

        <!-- Right Form -->
        <div class="w-full md:w-1/2 p-8 glass">
            <form method="POST" class="text-white">
                <h2 class="text-3xl font-bold mb-6 text-center">Register</h2>

                <input type="email" name="email"
                    class="w-full p-3 mb-4 bg-gray-700 rounded focus:outline-none focus:ring-2 focus:ring-blue-400"
                    placeholder="Email" required>

                <input type="text" name="username"
                    class="w-full p-3 mb-4 bg-gray-700 rounded focus:outline-none focus:ring-2 focus:ring-blue-400"
                    placeholder="Username" required>

                <textarea name="alamat"
                    class="w-full p-3 mb-4 bg-gray-700 rounded focus:outline-none focus:ring-2 focus:ring-blue-400"
                    placeholder="Alamat Lengkap" required></textarea>

                <input type="password" name="password"
                    class="w-full p-3 mb-4 bg-gray-700 rounded focus:outline-none focus:ring-2 focus:ring-blue-400"
                    placeholder="Password" required>

                <button type="submit"
                    class="w-full bg-blue-600 py-3 rounded text-white hover:bg-blue-700 transition">Register</button>

                <p class="text-sm text-black-700 mt-4 text-center">
                    Sudah Memiliki Akun?
                    <a href="login.php" class="text-blue-900 hover:underline">Login Disini</a>
                </p>
            </form>
        </div>
    </div>
</body>

</html>
