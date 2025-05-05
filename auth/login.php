<?php
require_once '../config/db.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $username = $_POST['username'];
    $password = $_POST['password'];

    // Check admin credentials
    if ($username === 'admin' && $password === 'admin') {
        header("Location: ../pages/dashboard.php");
        exit;
    }

    // Check user credentials
    $query = "SELECT * FROM users WHERE username='$username'";
    $result = $conn->query($query);

    if ($result->num_rows > 0) {
        $user = $result->fetch_assoc();
        if (password_verify($password, $user['password'])) {
            header("Location: ../pages/Shopping.php");
            exit;
        }
    }
    $error = "Invalid username or password.";
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
    <title>Login</title>
</head>

<body class="flex items-center justify-center h-screen">
    <div class="flex w-4/5 lg:w-3/5 bg-opacity-0 shadow-lg rounded-lg overflow-hidden">
        <!-- Left Image -->
        <div class="hidden md:block w-1/2 bg-cover" style="background-image: url('../images/toko.jpg');">
        </div>

        <!-- Right Form -->
        <div class="w-full md:w-1/2 p-8 glass">
            <form method="POST" class="text-white">
                <h2 class="text-3xl font-bold mb-6 text-center text-gray-700">Login</h2>
                <?php if (isset($error)) : ?>
                <p class="text-red-500 mb-4 text-center"><?php echo $error; ?></p>
                <?php endif; ?>
                <input type="text" name="username"
                    class="w-full p-3 mb-4 bg-gray-700 rounded focus:outline-none focus:ring-2 focus:ring-blue-400"
                    placeholder="Username" required>
                <input type="password" name="password"
                    class="w-full p-3 mb-4 bg-gray-700 rounded focus:outline-none focus:ring-2 focus:ring-blue-400"
                    placeholder="Password" required>
                <button class="w-full bg-blue-600 py-3 rounded text-white hover:bg-blue-700 transition">Login</button>
                <p class="text-sm text-black-700 mt-4 text-center">
                    Belum memiliki akun?
                    <a href="register.php" class="text-blue-900 hover:underline">Buat akun di sini</a>
                </p>
            </form>
        </div>
    </div>
</body>

</html>