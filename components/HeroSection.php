<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Modern Responsive Hero</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>

<body class="bg-white">
    <section class="flex items-center justify-center min-h-screen px-6 md:px-12">
        <div class="max-w-7xl w-full grid grid-cols-1 md:grid-cols-2 gap-12 items-center">
            
            <!-- Bagian Teks -->
            <div class="text-center md:text-left">
    <h1 id="welcome-text" class="text-4xl md:text-6xl font-extrabold text-gray-900 leading-tight"></h1>
    <p class="mt-4 md:mt-6 text-lg md:text-xl text-gray-600 font-light">
        Selamat Datang Di Toko HP, Leptop & Komputer TECHNO RAN Solusi Bagi Kamu Untuk Mencari Hp, Leptop, dan Komputer Gaming 
    </p>
    <div class="mt-6 flex flex-col sm:flex-row justify-center md:justify-start gap-4">
        <a href="#product"
            class="px-6 py-3 text-lg font-semibold bg-blue-600 hover:bg-blue-700 text-white rounded-lg shadow-md transition">
            Pilih Produk
        </a>
        <a href="#learn-more"
            class="px-6 py-3 text-lg font-semibold bg-gray-200 hover:bg-gray-300 text-gray-900 rounded-lg shadow-md transition">
            Learn More
        </a>
    </div>
</div>

<script>
    const text = "Selamat Datang";
    const welcomeText = document.getElementById("welcome-text");

    function typeEffect(index = 0) {
        if (index < text.length) {
            welcomeText.innerHTML += text[index];
            setTimeout(() => typeEffect(index + 1), 100); // Menambahkan huruf tiap 100ms
        }
    }

    typeEffect(); // Memulai efek animasi teks
</script>


            <!-- Bagian Gambar -->
            <div class="flex justify-center">
    <img src="../images/hero1.jpg" alt="Hero Image"
        class="w-full max-w-md md:max-w-lg rounded-3xl shadow-2xl object-cover transition transform duration-300 hover:scale-105 hover:shadow-3xl">
</div>


        </div>
    </section>
    <!-- ====== Brands Section Start -->
<section class="bg-white py-20 lg:py-[120px] dark:bg-dark">
  <div class="container mx-auto">
    <div class="-mx-4 flex flex-wrap">
      <div class="w-full px-4">
        <div class="flex flex-wrap items-center justify-center">
          <a
            href="javascript:void(0)"
            class="mx-4 flex w-[150px] items-center justify-center py-5 2xl:w-[180px]"
          >
            <img
              src="https://www.svgrepo.com/show/303110/apple-black-logo.svg"
              alt="image"
              class="h-10 w-full dark:hidden"
            />
            <img
              src=""
              alt="image"
              class="hidden h-10 w-full dark:block"
            />
          </a>
          <a
            href="javascript:void(0)"
            class="mx-4 flex w-[150px] items-center justify-center py-5 2xl:w-[180px]"
          >
            <img
              src="https://www.svgrepo.com/show/303630/nvidia-logo.svg"
              alt="image"
              class="h-10 w-full dark:hidden"
            />
            <img
              src="https://www.svgrepo.com/show/303630/nvidia-logo.svg"
              alt="image"
              class="hidden h-10 w-full dark:block"
            />
          </a>
          <a
            href="javascript:void(0)"
            class="mx-4 flex w-[150px] items-center justify-center py-5 2xl:w-[180px]"
          >
            <img
              src="https://www.svgrepo.com/show/303108/google-icon-logo.svg"
              alt="image"
              class="h-10 w-full dark:hidden"
            />
            <img
              src="https://cdn.tailgrids.com/2.2/assets/images/brands/uideck-white.svg"
              alt="image"
              class="hidden h-10 w-full dark:block"
            />
          </a>
          <a
            href="javascript:void(0)"
            class="mx-4 flex w-[150px] items-center justify-center py-5 2xl:w-[180px]"
          >
            <img
              src="https://www.svgrepo.com/show/303338/xiaomi-logo.svg"
              alt="image"
              class="h-10 w-full dark:hidden"
            />
            <img
              src="https://cdn.tailgrids.com/2.2/assets/images/brands/ayroui-white.svg"
              alt="image"
              class="hidden h-10 w-full dark:block"
            />
          </a>
        </div>
      </div>
    </div>
  </div>
</section>
<!-- ====== Brands Section End -->
</body>

</html>
