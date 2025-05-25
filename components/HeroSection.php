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
          Selamat Datang Di Toko Kue Dapoer Bunasya, Tempat Terbaik Untuk Memenuhi Kebutuhan Kue Anda.
          Temukan Berbagai Pilihan Kue Lezat dan Nikmati Pengalaman Berbelanja yang Menyenangkan Bersama Kami.
        </p>
        <div class="mt-6 flex flex-col sm:flex-row justify-center md:justify-start gap-4">
          <a href="#product"
            class="px-6 py-3 text-lg font-semibold bg-green-600 hover:bg-green-900 text-white rounded-lg shadow-md transition">
            Pilih Produk
          </a>
          <a href="#learn-more"
            class="px-6 py-3 text-lg font-semibold bg-gray-200 hover:bg-gray-300 text-gray-900 rounded-lg shadow-md transition">
            Tentang Kami
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
      <div class="relative w-full max-w-lg mx-auto overflow-hidden rounded-3xl shadow-2xl">
  <div id="carousel-images" class="flex transition-transform duration-700 ease-in-out">
    <img src="../images/hero.jpg" class="w-full flex-shrink-0 object-cover" alt="Hero 1" />
    <img src="../images/hero-2.png" class="w-full flex-shrink-0 object-cover" alt="Hero 2" />
    <img src="../images/hero-3.png" class="w-full flex-shrink-0 object-cover" alt="Hero 3" />
  </div>

  <!-- Navigation Buttons -->
  <button onclick="prevSlide()" class="absolute left-2 top-1/2 transform -translate-y-1/2 bg-white bg-opacity-70 px-2 py-1 rounded-full shadow-md hover:bg-opacity-100">
    &#8592;
  </button>
  <button onclick="nextSlide()" class="absolute right-2 top-1/2 transform -translate-y-1/2 bg-white bg-opacity-70 px-2 py-1 rounded-full shadow-md hover:bg-opacity-100">
    &#8594;
  </button>
</div>

<script>
  const carousel = document.getElementById('carousel-images');
  const totalSlides = carousel.children.length;
  let currentIndex = 0;

  function updateSlide() {
    const offset = -currentIndex * 100;
    carousel.style.transform = `translateX(${offset}%)`;
  }

  function nextSlide() {
    currentIndex = (currentIndex + 1) % totalSlides;
    updateSlide();
  }

  function prevSlide() {
    currentIndex = (currentIndex - 1 + totalSlides) % totalSlides;
    updateSlide();
  }
</script>



    </div>
  </section>
  <!-- ====== Brands Section Start -->
  <section class="bg-white py-20 lg:py-[120px] dark:bg-dark">
  <div class="container mx-auto">
    <div class="-mx-4 flex flex-wrap">
      <div class="w-full px-4">
        <div class="flex flex-wrap items-center justify-center">
          <!-- Gambar 1 -->
          <a href="javascript:void(0)"
             class="mx-4 flex w-[150px] items-center justify-center py-5 2xl:w-[180px] brand-logo animate-slide-right-to-left">
            <img src="../images//mandiri.svg" alt="Apple logo" class="h-10 w-full dark:hidden" />
            <img src="" alt="Apple logo dark mode" class="hidden h-10 w-full dark:block" />
          </a>

          <!-- Gambar 2 -->
          <a href="javascript:void(0)"
             class="mx-4 flex w-[150px] items-center justify-center py-5 2xl:w-[180px] brand-logo animate-slide-right-to-left">
            <img src="../images/bca.svg" alt="Nvidia logo" class="h-10 w-full dark:hidden" />
            
          </a>

          <!-- Gambar 3 -->
          <a href="javascript:void(0)"
             class="mx-4 flex w-[150px] items-center justify-center py-5 2xl:w-[180px] brand-logo animate-slide-right-to-left">
            <img src="../images/bri.svg" alt="Google logo" class="h-10 w-full dark:hidden" />
            
          </a>

          <!-- Gambar 4 -->
          <a href="javascript:void(0)"
             class="mx-4 flex w-[150px] items-center justify-center py-5 2xl:w-[180px] brand-logo animate-slide-right-to-left">
            <img src="../images/bank.svg" alt="Xiaomi logo" class="h-10 w-full dark:hidden" />
          </a>
        </div>
      </div>
    </div>
  </div>
</section>

<!-- CSS Animasi -->
<style>
  @keyframes slide-right-to-left {
    0% {
      transform: translateX(100%); /* Mulai dari kanan */
      opacity: 0;
    }
    50% {
      transform: translateX(0); /* Posisikan di tengah */
      opacity: 1;
    }
    100% {
      transform: translateX(-100%); /* Geser ke kiri */
      opacity: 0;
    }
  }

  .animate-slide-right-to-left {
    animation: slide-right-to-left 10s linear infinite;
  }
</style>

  <!-- ====== Brands Section End -->
</body>

</html>