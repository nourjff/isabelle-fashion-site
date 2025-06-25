<?php
$host = 'localhost';
$user = 'root';
$password = '';
$dbname = 'isabelle_fashion';

$conn = new mysqli($host, $user, $password, $dbname);
if ($conn->connect_error) {
  die("Connection failed: " . $conn->connect_error);
}

$sql = "SELECT * FROM heels";
$result = $conn->query($sql);
?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <title>Heels | Isabelle Dresses</title>
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />

  <!-- Tailwind Config and CDN -->
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
  <script src="https://cdn.tailwindcss.com"></script>

  <!-- Google Fonts -->
  <link href="https://fonts.googleapis.com/css2?family=Open+Sans:wght@300;400;600;700;800&family=Playfair+Display:ital@1&display=swap" rel="stylesheet">
  <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/flatpickr/dist/flatpickr.min.css">

  <style>
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

    .sub-font {
      font-family: 'Playfair Display', serif !important;
      font-style: italic !important;
      font-weight: 400 !important;
    }

    .gold-icon {
      filter: brightness(0) saturate(100%) invert(71%) sepia(42%) saturate(900%) hue-rotate(4deg) brightness(95%) contrast(90%);
    }

    body, h1, h2, h3, h4, h5, h6,
    p, a, span, li, input, button, div, section, nav {
      font-family: 'Open Sans', sans-serif !important;
      font-weight: 300 !important;
    }
  </style>
</head>
<body class="text-white">

<!-- Navbar -->
<nav class="flex justify-between items-center px-10 py-8 bg-black text-[#D4AF37] text-2xl font-semibold">
  <div class="flex items-center gap-6">
    <img src="SA logo - 2.png" alt="SA Icon Logo" class="h-16 w-auto" />
    <img src="SA logo.png" alt="Isabelle Dresses Logo" class="h-20 w-auto" />
  </div>

  <button id="menuToggle"
    class="sm:hidden text-3xl text-[#D4AF37] fixed top-4 right-4 z-[999] bg-black bg-opacity-80 p-2 rounded-full shadow-lg">
    ☰
  </button>

  <div class="hidden sm:flex gap-10 text-xl font-light">
    <a href="index.php" class="text-[#D4AF37] hover:text-white">Home</a>
    <a href="about.php" class="text-[#D4AF37] hover:text-white">About</a>
    <a href="dresses.php" class="text-[#D4AF37] hover:text-white">Dresses</a>
    <a href="heels.php" class="text-white font-semibold">Heels</a>
  </div>

  <div id="menu"
    class="fixed top-0 right-[-100%] w-[70%] h-full bg-black text-[#D4AF37] p-6 z-50 transition-all duration-300 sm:hidden flex flex-col gap-6 shadow-lg">
    <a href="index.php" class="hover:text-white text-lg">Home</a>
    <a href="about.php" class="hover:text-white text-lg">About</a>
    <a href="dresses.php" class="hover:text-white text-lg">Dresses</a>
    <a href="heels.php" class="text-white font-semibold text-lg">Heels</a>
  </div>
</nav>

<script>
  const toggle = document.getElementById('menuToggle');
  const menu = document.getElementById('menu');
  toggle.addEventListener('click', () => {
    menu.classList.toggle('right-[-100%]');
    menu.classList.toggle('right-0');
  });
</script>

<!-- Heels Grid -->
<section class="py-10 px-6">
  <div class="max-w-screen-xl mx-auto w-full">
    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-6">
      <?php
      if ($result->num_rows > 0) {
        while ($row = $result->fetch_assoc()) {
          echo '<div class="bg-black border border-[#D4AF37] rounded-xl overflow-hidden">';
          echo '<img src="' . htmlspecialchars($row['image_url']) . '" class="w-full h-[340px] object-cover">';
          echo '<div class="p-4 text-center">';
          echo '<h3 class="text-lg font-semibold mb-1 text-[#D4AF37]">' . htmlspecialchars($row['name']) . '</h3>';
          echo '<p class="text-sm text-gray-300">Size: ' . htmlspecialchars($row['size']) . '</p>';
          echo '</div>';
          echo '</div>';
        }
      } else {
        echo '<p class="col-span-3 text-center">No heels available at the moment.</p>';
      }
      ?>
    </div>
  </div>
</section>

<!-- WhatsApp Button -->
<a href="https://wa.me/81971871" target="_blank" class="fixed bottom-4 right-4 z-50 sm:bottom-5 sm:right-5">
  <div class="bg-[#25D366] rounded-full p-4 shadow-lg hover:scale-110 transition">
    <img src="icons/whatsapp-icon.png" alt="WhatsApp" class="h-12 w-12">
  </div>
</a>

<!-- Info Note -->
<div class="w-full bg-[#D4AF37] text-black text-center py-3 text-base font-semibold animate-pulse">
  Heels are rented with some dresses for an extra fee of $7.
</div>

<!-- Contact Section -->
<section class="relative bg-black text-white text-center py-9 px-4">
  <img src="icons/left.svg" alt="Decor" class="absolute top-6 left-2 w-20 h-20 sm:top-10 sm:left-10 sm:w-32 sm:h-32 md:w-48 md:h-48">
  <img src="icons/right.svg" alt="Decor" class="absolute top-6 right-2 w-20 h-20 sm:top-10 sm:right-10 sm:w-32 sm:h-32 md:w-48 md:h-48">
  <h2 class="text-5xl md:text-6xl font-extrabold mb-6 tracking-wide leading-tight">We Love To Communicate</h2>
  <p class="text-2xl md:text-3xl text-gray-300 font-light">We Would Love To Help</p>
</section>
<section class="bg-black text-white py-16 px-4">
  <div class="max-w-screen-xl mx-auto">
    <div class="flex flex-wrap justify-center gap-16 items-center mt-10 text-base">
      <div class="flex flex-col sm:flex-row flex-wrap justify-center gap-10 items-center mt-10 text-base text-center sm:text-left">

        <div class="flex items-center gap-3">
          <img src="icons/location.svg" alt="Location" class="h-6 w-6 gold-icon">
          <div>
            <p class="text-lg font-semibold text-white">Beirut Baabda</p>
            <p class="text-base text-gray-300">Thwetat Al Ghadir – Near Sunrise School</p>
          </div>
        </div>

        <div class="flex items-center gap-3">
          <img src="icons/phone.svg" alt="Phone" class="h-6 w-6 gold-icon">
          <p class="text-base text-gray-300">+971 81 971 871</p>
        </div>

        <div class="flex items-center gap-3">
          <img src="icons/email.svg" alt="Email" class="h-6 w-6 gold-icon">
          <p class="text-base text-gray-300">info@isballdresses.com</p>
        </div>

        <div class="flex items-center gap-6">
          <a href="https://www.tiktok.com/@isabelle.dresses" target="_blank" class="flex items-center gap-2 hover:underline">
            <img src="icons/tiktok.svg" alt="TikTok" class="h-6 w-6 gold-icon">
            <p class="text-base text-gray-300">isabelle.dresses</p>
          </a>

          <a href="https://www.instagram.com/isabelledresses/" target="_blank" class="flex items-center gap-2 hover:underline">
            <img src="icons/instagram.svg" alt="Instagram" class="h-6 w-6 gold-icon">
            <p class="text-base text-gray-300">isabelledresses</p>
          </a>
        </div>

      </div>
    </div>
  </div>
</section>

</body>
</html>
