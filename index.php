<!doctype html>
<html lang="en">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <title>Isabelle Dresses</title>

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


  <!-- Tailwind CSS -->
  <script src="https://cdn.tailwindcss.com"></script>

  <!-- Google Fonts: Nunito & Playfair Display -->
  <link href="https://fonts.googleapis.com/css2?family=Open+Sans:wght@300;400;600&family=Playfair+Display:ital@1&display=swap" rel="stylesheet">

  <!-- AOS (Animation On Scroll) -->
  <link href="https://unpkg.com/aos@2.3.1/dist/aos.css" rel="stylesheet" />

  <style>
    * {
      margin: 0;
      padding: 0;
      box-sizing: border-box;
    }

    html, body {
      background-image: url('images/background.png');
      background-size: cover;
      background-repeat: no-repeat;
      background-attachment: fixed;
      background-position: center;
      font-family: 'Open Sans';

      font-weight: 300;
    }

    html {
      scroll-behavior: smooth;
    }

    .sub-font {
      font-family: 'Playfair Display', serif !important;
      font-style: italic !important;
    }

    .product-card {
      background-color: #000;
      border: 1.5px solid #D4AF37;
      color: white;
    }

    .product-card h3 {
      color: #D4AF37;
    }

    .gold-icon {
      filter: brightness(0) saturate(100%) invert(71%) sepia(42%) saturate(900%) hue-rotate(4deg) brightness(95%) contrast(90%);
    }

   
  /* --- GLOBAL FONT RESET ----------------------------------- */
  /* Make every element inherit Open Sans Light unless told otherwise */
  body, h1, h2, h3, h4, h5, h6,
  p, a, span, li, input, button, div, section, nav {
    font-family: 'Open Sans', sans-serif !important;
    font-weight: 300 !important;     /* thin by default             */
  }

  /* Keep Playfair Display for the decorative sub-headings you marked */
  .sub-font {                        /* already defined above       */
    font-family: 'Playfair Display', serif !important;
    font-style: italic !important;
    font-weight: 400 !important;     /* regular weight looks best   */
  }

  </style>
</head>
<body class="text-white">

<!-- Header -->
<!-- 🟦 NAVIGATION SECTION -->
<nav class="flex justify-between items-center px-10 py-8 bg-black text-[#D4AF37] text-2xl font-semibold">
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
  <a href="index.php" class="text-white font-semibold">Home</a>
  <a href="about.php" class="text-[#D4AF37] hover:text-white">About</a>
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


<!-- Hero Section -->
<section class="relative h-[600px] max-sm:h-[400px]">
  <img src="img1.jpg" alt="Luxury fashion items" class="object-cover w-full h-full block" />
  <div class="absolute top-[70%] left-1/2 transform -translate-x-1/2 -translate-y-1/2 text-center text-white">
<h2 class="mb-5 text-4xl sm:text-5xl max-sm:text-2xl font-light tracking-wide">Elegance for Every Occasion</h2>
<a href="#products-anchor"
   class="inline-block bg-[#D4AF37] text-black rounded-[30px] px-6 sm:px-10 py-3 sm:py-4 text-sm sm:text-lg font-bold hover:bg-yellow-500 transition text-center max-w-[90vw]">
  Browse Collection
</a>


  </div>
</section>

<!-- Stats Section -->
<section class="bg-black py-16 px-4">
  <div class="max-w-screen-xl mx-auto flex flex-col md:flex-row justify-center items-center gap-16">

    <div class="flex items-center gap-4" data-aos="fade-right" data-aos-duration="1000">
      <img src="dress.svg" alt="Dress Icon" class="h-10 w-10" />
      <div class="text-left">
        <p class="text-white text-3xl font-bold">80+</p>
        <p class="uppercase text-sm tracking-widest text-[#D4AF37] font-semibold">Dresses Were Rented</p>
      </div>
    </div>
    <div class="flex items-center gap-4" data-aos="fade-left" data-aos-duration="1000">
      <img src="heart.svg" alt="Heart Icon" class="h-10 w-10" />
      <div class="text-left">
        <p class="text-white text-3xl font-bold">200+</p>
        <p class="uppercase text-sm tracking-widest text-[#D4AF37] font-semibold">Satisfied Clients</p>
      </div>
    </div>
  </div>
</section>

<!-- Scroll anchor -->
<div id="products-anchor"></div>

<!-- Product Grid -->
<div class="px-4">
  <div id="productGrid" class="max-w-screen-xl mx-auto flex justify-center flex-wrap gap-12 py-14"></div>
</div>


<!-- Product Script -->
<script>
  document.addEventListener("DOMContentLoaded", () => {
    const productGrid = document.getElementById("productGrid");
    const state = {
      products: [
        { id: 1, name: "Dresses", image: "img2.png", link: "dresses.php" },
        { id: 3, name: "Heels", image: "heels.png", link: "heels.php" }
      ]
    };
    state.products.forEach((product) => {
      const productCard = document.createElement("a");
      productCard.href = product.link;
      productCard.className = "w-[400px] md:w-[450px] product-card rounded-2xl shadow-md overflow-hidden transition-transform duration-300 ease-in-out hover:scale-105";
      productCard.innerHTML = `
      <img src="${product.image}" alt="${product.name}" class="object-cover w-full h-[500px] sm:h-[400px] max-sm:h-[300px]" />

        <div class="p-6 text-center">
          <h3 class="text-2xl font-semibold">${product.name}</h3>
        </div>
      `;
      productGrid.appendChild(productCard);
    });
  });
</script>

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




  <!-- WhatsApp Button -->
  <!-- <a href="https://wa.me/81971871" target="_blank" class="fixed bottom-4 right-4 z-50 sm:bottom-5 sm:right-5">

    <div class="bg-[#D4AF37] rounded-full p-4 shadow-lg hover:scale-110 transition">
      <img src="icons/whatsapp.svg" alt="WhatsApp" class="h-12 w-12">
    </div>
  </a>
</section> -->

<a href="https://wa.me/81971871" target="_blank" class="fixed bottom-4 right-4 z-50 sm:bottom-5 sm:right-5">
  <div class="bg-[#25D366] rounded-full p-4 shadow-lg hover:scale-110 transition">
    <img src="icons/whatsapp-icon.png" alt="WhatsApp" class="h-12 w-12">
    
  </div>
</a>


<!-- Footer -->
<!-- <footer class="bg-black text-white py-8 mt-16 border-t border-zinc-800">
  <div class="flex justify-center items-center gap-3">
    <img src="weboxa.png" alt="Weboxa Logo" class="h-10 w-10" />
    <a href="https://weboxa.com" target="_blank" class="text-sm hover:text-[#D4AF37] transition-colors">Powered by Weboxa</a>
  </div>
</footer> -->

<!-- AOS Script -->
<script src="https://unpkg.com/aos@2.3.1/dist/aos.js"></script>
<script>AOS.init();</script>

<script>
  document.addEventListener("DOMContentLoaded", function () {
    const menu = document.getElementById("menu");
    const toggle = document.getElementById("menuToggle");

    toggle.addEventListener("click", () => {
      if (menu.style.right === "0px") {
        menu.style.right = "-100%";
      } else {
        menu.style.right = "0px";
      }
    });

    // Swipe-to-close (touch gestures)
    let touchStartX = 0;

    menu.addEventListener("touchstart", function (e) {
      touchStartX = e.changedTouches[0].screenX;
    });

    menu.addEventListener("touchend", function (e) {
      const touchEndX = e.changedTouches[0].screenX;
      const diff = touchEndX - touchStartX;

      // If user swipes right to left, close menu
      if (diff < -50) {
        menu.style.right = "-100%";
      }
    });

    // Optionally swipe to open from right edge (if needed)
    document.body.addEventListener("touchstart", function (e) {
      touchStartX = e.changedTouches[0].screenX;
    });

    document.body.addEventListener("touchend", function (e) {
      const touchEndX = e.changedTouches[0].screenX;
      const diff = touchEndX - touchStartX;

      // If user swipes left to right from the edge, open menu
      if (touchStartX > window.innerWidth - 30 && diff > 50) {
        menu.style.right = "0px";
      }
    });
  });
</script>



</body>
</html>
