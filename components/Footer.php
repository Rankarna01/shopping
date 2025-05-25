<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <title>Footer Hijau</title>
  <link href="https://cdn.jsdelivr.net/npm/tailwindcss@2.2.19/dist/tailwind.min.css" rel="stylesheet" />
</head>

<body class="bg-gray-100">
  <footer class="bg-green-700 text-white">
    <div class="grid grid-cols-1 lg:grid-cols-5">
      <!-- Maps -->
      <div class="lg:col-span-2">
        <div class="w-full h-64 lg:h-full">
          <iframe 
            src="https://www.google.com/maps/embed?pb=!1m18!1m12!1m3!1d3982.1735673004746!2d98.71069779999999!3d3.5474164!2m3!1f0!2f0!3f0!3m2!1i1024!2i768!4f13.1!3m3!1m2!1s0x3031308435df2797%3A0x4d1489153ec98b07!2sJl.%20Garu%201%20No.170%2C%20Sitirejo%20III%2C%20Kec.%20Medan%20Amplas%2C%20Kota%20Medan%2C%20Sumatera%20Utara%2020147!5e0!3m2!1sid!2sid!4v1747638246545!5m2!1sid!2sid" 
            width="100%" 
            height="100%" 
            style="border:0;" 
            allowfullscreen="" 
            loading="lazy" 
            referrerpolicy="no-referrer-when-downgrade"
            class="w-full h-full"
          ></iframe>
        </div>
      </div>

      <!-- Konten -->
      <div class="lg:col-span-3 px-6 py-10">
        <div class="grid sm:grid-cols-2 gap-8">
          <div>
            <h2 class="text-xl font-semibold">Hubungi Kami</h2>
            <p class="mt-2 text-lg">0858-3511-6946</p>
            <p class="mt-2 text-sm">Senin - Jumat: 10.00 - 19.00 WIB</p>

            <div class="flex space-x-4 mt-4">
              <!-- Facebook -->
              <a href="#" class="hover:text-gray-200 transition">
                <svg class="w-6 h-6" fill="currentColor" viewBox="0 0 24 24">
                  <path d="M22 12..."></path>
                </svg>
              </a>
              <!-- Instagram -->
              <a href="#" class="hover:text-gray-200 transition">
                <svg class="w-6 h-6" fill="currentColor" viewBox="0 0 24 24">
                  <path d="M12.315 2..."></path>
                </svg>
              </a>
              <!-- Twitter -->
              <a href="#" class="hover:text-gray-200 transition">
                <svg class="w-6 h-6" fill="currentColor" viewBox="0 0 24 24">
                  <path d="M8.29 20.251..."></path>
                </svg>
              </a>
            </div>
          </div>

          <div>
            <h2 class="text-xl font-semibold">Tentang Kami</h2>
            <p class="mt-2 text-sm">
              Kami siap membantu Anda membangun solusi digital modern dan efektif. Fokus pada kualitas, kecepatan, dan hasil terbaik.
            </p>
          </div>
        </div>
      </div>
    </div>

    <div class="text-center text-sm text-white py-4 border-t border-green-600">
      &copy; 2025 dapoer bunasya. All rights reserved.
    </div>
  </footer>
</body>
</html>
