<!doctype html>
<html lang="en">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <title>About Us | Isabelle Dresses</title>

  <!-- Tailwind font config (same as index.php) -->
  <script>
    tailwind.config = {
      theme: {
        extend: {
          fontFamily: {
            sans: ['"Open Sans"', 'sans-serif'],
            serif: ['"Playfair Display"', 'serif'],
          }
        }
      }
    }
  </script>

  <!-- Tailwind CDN -->
  <script src="https://cdn.tailwindcss.com"></script>

  <!-- Google Fonts (Open Sans + Playfair Display) -->
  <link href="https://fonts.googleapis.com/css2?family=Open+Sans:wght@300;400;600;700;800&family=Playfair+Display:ital@1&display=swap" rel="stylesheet">

  <!-- AOS -->
  <link rel="stylesheet" href="https://unpkg.com/aos@2.3.1/dist/aos.css">

  <style>
    /* Base background + font (matches homepage) */
    html, body {
      background-image: url('images/background.png');
      background-size: cover;
      background-repeat: no-repeat;
      background-attachment: fixed;
      background-position: center;
      font-family: 'Open Sans', sans-serif;
      font-weight: 300;
      color: #fff;
      scroll-behavior: smooth;
    }

    /* Gold-tint for icons (re-used) */
    .gold-icon {
      filter: brightness(0) saturate(100%) invert(71%) sepia(42%) saturate(900%) hue-rotate(4deg) brightness(95%) contrast(90%);
    }

    /* Decorative italic headings */
    .sub-font {
      font-family: 'Playfair Display', serif !important;
      font-style: italic !important;
      font-weight: 400 !important;
    }

    /* Global font override so every tag inherits Open Sans Light */
    body, h1, h2, h3, h4, h5, h6,
    p, a, span, li, input, button, div, section, nav {
      font-family: 'Open Sans', sans-serif !important;
      font-weight: 300 !important;
    }
  </style>
</head>
<body class="text-white">

<!-- Header -->
<!-- 🟦 NAVIGATION SECTION -->
<nav class="flex justify-between items-center px-10 py-8 bg-black text-[#D4AF37] text-3xl font-semibold">
  <!-- Logo -->
  <div class="flex items-center gap-6">
    <img src="SA logo - 2.png" alt="SA Icon Logo" class="h-16 w-auto" />
    <img src="SA logo.png" alt="Isabelle Dresses Logo" class="h-20 w-auto" />
  </div>
  </div>

<button id="menuToggle"
        class="sm:hidden text-3xl text-[#D4AF37] fixed top-4 right-4 z-[999] bg-black bg-opacity-80 p-2 rounded-full shadow-lg">
  ☰
</button>


<!-- DESKTOP Menu -->
<div class="hidden sm:flex gap-10 text-xl font-light">
  <a href="index.php" class="text-[#D4AF37] hover:text-white">Home</a>
  <a href="about.php" class="text-white font-semibold">About</a>
  <a href="dresses.php" class="text-[#D4AF37] hover:text-white">Dresses</a>
  <a href="heels.php" class="text-[#D4AF37] hover:text-white">Heels</a>
</div>



<!-- MOBILE Sidebar Menu -->
<div id="menu"
     class="fixed top-0 right-[-100%] w-[70%] h-full bg-black text-[#D4AF37] p-6 z-50 transition-all duration-300 sm:hidden flex flex-col gap-6 shadow-lg">
  <a href="index.html" class="hover:text-white text-lg">Home</a>
  <a href="about.php" class="hover:text-white text-lg">About</a>
  <a href="dresses.php" class="hover:text-white text-lg">Dresses</a>
  <a href="heels.php" class="hover:text-white text-lg">Heels</a>
</div>


</nav>
<!-- ███ ABOUT SECTION ███ -->
<section class="px-6 py-20 max-w-7xl mx-auto" id="about">
  <div class="flex flex-col lg:flex-row items-center gap-12">

    <!-- Text -->
    <div class="flex-1" data-aos="fade-right">
      <h2 class="text-5xl font-extrabold text-[#D4AF37] tracking-widest mb-6">About Us</h2>
      <p class="text-lg leading-relaxed text-justify">
        Two friends with a shared love for fashion came together to turn a dream into reality.
        What started from our home in Beirut has grown into a small business built with passion,
        creativity, and heart. Today, we’re proud to dress over 200 amazing clients with our
        carefully curated collection of beautiful dresses. Every piece is a part of our story—and yours.
      </p>
      <p class="mt-6 text-lg italic text-[#D4AF37]">— Aya &amp; Sara</p>
    </div>

    <!-- Image -->
    <div class="flex-1" data-aos="fade-left">
      <div class="rounded-xl overflow-hidden border-2 border-[#D4AF37] shadow-xl">
        <img src="images/Aboutus.jpg"
             alt="About Us"
             class="w-full max-h-[480px] object-cover transition duration-700 ease-in-out blur-sm hover:blur-0">
      </div>
    </div>
  </div>
</section>
<!-- Contact Us Section -->
<section class="relative bg-black text-white text-center py-9 px-4">
<img src="icons/left.svg" alt="Decor"
  class="absolute top-6 left-2 w-20 h-20 sm:top-10 sm:left-10 sm:w-32 sm:h-32 md:w-48 md:h-48">

<img src="icons/right.svg" alt="Decor"
  class="absolute top-6 right-2 w-20 h-20 sm:top-10 sm:right-10 sm:w-32 sm:h-32 md:w-48 md:h-48">


  <h2 class="text-5xl md:text-6xl font-extrabold mb-6 tracking-wide leading-tight">We Love To Communicate</h2>
 <p class="text-2xl md:text-3xl text-gray-300 font-light">
  We Would Love To Help
</p>

</section>
<section class="bg-black text-white py-16 px-4">
  <div class="max-w-screen-xl mx-auto">
    <div class="flex flex-wrap justify-center gap-16 items-center mt-10 text-base">


    <!-- Contact Info Horizontal Row -->
   <div class="flex flex-col sm:flex-row flex-wrap justify-center gap-10 items-center mt-10 text-base text-center sm:text-left">


   <!-- Location -->
<div class="flex items-center gap-3">
  <img src="icons/location.svg" alt="Location" class="h-6 w-6 gold-icon">
  <div>
    <p class="text-lg font-semibold text-white">Beirut Baabda</p>
    <p class="text-base text-gray-300">Thwetat Al Ghadir – Near Sunrise School</p>
  </div>
</div>

<!-- Phone -->
<div class="flex items-center gap-3">
  <img src="icons/phone.svg" alt="Phone" class="h-6 w-6 gold-icon">
  <p class="text-base text-gray-300">+971 81 971 871</p>
</div>

<!-- Email -->
<div class="flex items-center gap-3">
  <img src="icons/email.svg" alt="Email" class="h-6 w-6 gold-icon">
  <p class="text-base text-gray-300">info@isballdresses.com</p>
</div>

<!-- Socials -->
<div class="flex items-center gap-6">
  <!-- TikTok -->
  <a href="https://www.tiktok.com/@isabelle.dresses" target="_blank" class="flex items-center gap-2 hover:underline">
    <img src="icons/tiktok.svg" alt="TikTok" class="h-6 w-6 gold-icon">
    <p class="text-base text-gray-300">isabelle.dresses</p>
  </a>

  <!-- Instagram -->
  <a href="https://www.instagram.com/isabelledresses/" target="_blank" class="flex items-center gap-2 hover:underline">
    <img src="icons/instagram.svg" alt="Instagram" class="h-6 w-6 gold-icon">
    <p class="text-base text-gray-300">isabelledresses</p>
  </a>
</div>

  </div>
</section>

<!-- ███ WHATSAPP FLOAT ███ -->
<a href="https://wa.me/81971871" target="_blank" class="fixed bottom-4 right-4 z-50 sm:bottom-5 sm:right-5">
  <div class="bg-[#25D366] rounded-full p-4 shadow-lg hover:scale-110 transition">
    <img src="icons/whatsapp-icon.png" alt="WhatsApp" class="h-12 w-12">
  </div>
</a>

<!-- AOS -->
<script src="https://unpkg.com/aos@2.3.1/dist/aos.js"></script>
<script>AOS.init();</script>
</body>
</html>
