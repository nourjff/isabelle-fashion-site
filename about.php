<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <title>About Us | Isabelle Dresses</title>
  <script src="https://cdn.tailwindcss.com"></script>
  <link rel="stylesheet" href="https://unpkg.com/aos@2.3.1/dist/aos.css">
  <link href="https://fonts.googleapis.com/css2?family=Cinzel:wght@400;700&display=swap" rel="stylesheet">
  <style>
    body {
      background-image: url('images/background.png');
      background-size: cover;
      background-repeat: no-repeat;
      background-attachment: fixed;
      background-position: center;
    }
    .gold-icon {
      filter: brightness(0) saturate(100%) invert(71%) sepia(42%) saturate(900%) hue-rotate(4deg) brightness(95%) contrast(90%);
    }
  </style>
</head>
<body class="text-white">

<!-- Header -->
<nav class="flex justify-between items-center px-10 py-8 bg-black text-[#D4AF37] text-xl font-semibold">
  <div class="flex items-center gap-6">
    <img src="SA logo - 2.png" alt="SA Icon Logo" class="h-16 w-auto" />
    <img src="SA logo.png" alt="Isabelle Dresses Logo" class="h-20 w-auto" />
  </div>
  <div class="flex gap-10">
    <a href="index.html" class="hover:text-white">Home</a>
    <a href="dresses.php" class="hover:text-white">Dresses</a>
    <a href="heels.php" class="hover:text-white">Heels</a>
    <a href="about.php" class="underline">About</a>
  </div>
</nav>

<!-- About Section -->
<section class="px-6 py-20 max-w-7xl mx-auto" id="about">
  <div class="flex flex-col lg:flex-row items-center gap-12">
    
    <!-- Text Block -->
    <div class="flex-1" data-aos="fade-right">
<h2 class="text-5xl font-bold text-[#D4AF37] tracking-widest mb-6 text-left">About Us</h2>
<p class="text-white leading-relaxed lg:leading-loose text-lg text-justify" style="text-shadow: 0 1px 2px rgba(0,0,0,0.8); text-transform: none !important;">
  Two friends with a shared love for fashion came together to turn a dream into reality. What started from our home in Beirut has grown into a small business built with passion, creativity, and heart. Today, we're proud to dress over 200 amazing clients with our carefully curated collection of beautiful dresses. Every piece is a part of our story—and yours.
</p>

<p class="mt-6 italic text-[#D4AF37] text-lg">— Aya & Sara</p>

    </div>

    <!-- Image Block -->
    <div class="flex-1 relative" data-aos="fade-left">
      <div class="rounded-xl overflow-hidden border-2 border-[#D4AF37] shadow-xl">
        <img src="images/Aboutus.jpg" alt="About Us" class="w-full max-h-[480px] object-cover blur-sm  transition duration-700 ease-in-out cursor-pointer" />
      </div>
    </div>
  </div>
</section>

<!-- Contact Heading Section -->

<!-- Contact Us Section -->
<section class="relative bg-black text-white text-center py-8 px-4">
  <img src="icons/left.svg" alt="Decor" class="absolute top-10 left-10 w-40 h-40 md:w-48 md:h-48">
<img src="icons/right.svg" alt="Decor"
  class="absolute top-6 right-2 w-20 h-20 sm:top-10 sm:right-10 sm:w-32 sm:h-32 md:w-48 md:h-48">

  <h2 class="text-5xl md:text-6xl font-extrabold mb-6 tracking-wide leading-tight sub-font">We Love To Communicate</h2>
  <p class="text-2xl md:text-3xl text-gray-300">We Would Love To Help</p>
</section>
<section class="bg-black text-white py-16 px-6 md:px-20">

    <!-- Contact Info Horizontal Row -->
    <div class="flex flex-wrap justify-center gap-16 items-center mt-10 text-base">

      <!-- Location -->
      <div class="flex items-center gap-3">
        <img src="icons/location.svg" alt="Location" class="h-6 w-6 gold-icon">
        <div>
          <p class="font-bold">Beirut Baabda</p>
          <p class="text-sm text-gray-300">Thwetat Al Ghadir – Near Sunrise School</p>
        </div>
      </div>

      <!-- Phone -->
      <div class="flex items-center gap-3">
        <img src="icons/phone.svg" alt="Phone" class="h-6 w-6 gold-icon">
        <p class="text-gray-300">+971 81 971 871</p>
      </div>

      <!-- Email -->
      <div class="flex items-center gap-3">
        <img src="icons/email.svg" alt="Email" class="h-6 w-6 gold-icon">
        <p class="text-gray-300">info@isballdresses.com</p>
      </div>
<!-- Socials -->
<div class="flex items-center gap-6">
  <!-- TikTok -->
  <a href="https://www.tiktok.com/@isabelle.dresses" target="_blank" class="flex items-center gap-2 hover:underline">
    <img src="icons/tiktok.svg" alt="TikTok" class="h-6 w-6 gold-icon">
    <p class="text-gray-300">isabelle.dresses</p>
  </a>

  <!-- Instagram -->
  <a href="https://www.instagram.com/isabelledresses/" target="_blank" class="flex items-center gap-2 hover:underline">
    <img src="icons/instagram.svg" alt="Instagram" class="h-6 w-6 gold-icon">
    <p class="text-gray-300">isabelledresses</p>
  </a>
</div>

    </div>
  </div>
</section>
  <!-- WhatsApp Floating Button -->
  <a href="https://wa.me/81971871" target="_blank" class="fixed bottom-5 right-5 z-50">
    <div class="bg-[#D4AF37] rounded-full p-4 shadow-lg hover:scale-110 transition">
      <img src="icons/whatsapp.svg" alt="WhatsApp" class="h-12 w-12">
    </div>
  </a>
</section>

<!-- Footer -->
<!-- <footer class="bg-black text-white py-8 mt-16 border-t border-zinc-800">
  <div class="flex justify-center items-center gap-3">
    <img src="weboxa.png" alt="Weboxa Logo" class="h-5 w-5" />
    <a href="https://weboxa.com" target="_blank" class="text-sm hover:text-[#D4AF37] transition-colors">
      Powered by Weboxa
    </a>
  </div>
</footer> -->

<!-- AOS Script -->
<script src="https://unpkg.com/aos@2.3.1/dist/aos.js"></script>
<script>
  AOS.init();
</script>

</body>
</html>
