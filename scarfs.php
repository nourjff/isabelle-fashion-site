<?php
$host = 'localhost';
$user = 'root';
$password = '';
$dbname = 'isabelle_fashion';

$conn = new mysqli($host, $user, $password, $dbname);
if ($conn->connect_error) {
  die("Connection failed: " . $conn->connect_error);
}

$sql = "SELECT * FROM scarfs";
$result = $conn->query($sql);
?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8" />
  <title>Scarfs | Isabelle Dresses</title>
  <script src="https://cdn.tailwindcss.com"></script>
  <style>
    body {
      background-color: black;
    }
    .product-card {
      background-color: white;
    }
  </style>
</head>
<body class="text-zinc-800">

<nav class="flex justify-between items-center px-10 py-5 border-b border-zinc-100 bg-black text-[#D4AF37]">
  <div class="flex items-center gap-4">
    <img src="SA logo - 2.png" alt="Logo" class="h-14 w-auto">
    <img src="SA logo.png" alt="Isabelle" class="h-12 md:h-16 w-auto">
  </div>
  <div class="flex gap-8">
    <a href="index.html">Home</a>
    <a href="dresses.php">Dresses</a>
    <a href="scarfs.php" class="font-bold underline">Scarfs</a>
    <a href="heels.php">Heels</a>
  </div>
</nav>

<section class="text-center py-10">
  <h1 class="text-white text-4xl font-semibold tracking-wide">Our Scarfs</h1>
</section>

<div class="grid gap-8 grid-cols-1 md:grid-cols-2 lg:grid-cols-3 px-10 pb-20">
  <?php
  if ($result->num_rows > 0) {
    while ($row = $result->fetch_assoc()) {
      echo '
        <div class="product-card rounded-2xl shadow-md overflow-hidden transition-transform duration-300 ease-in-out hover:scale-105">
          <img src="' . htmlspecialchars($row['image_url']) . '" alt="' . htmlspecialchars($row['name']) . '" class="object-cover w-full h-[350px]" />
          <div class="p-5 space-y-4 text-center">
            <h3 class="text-lg font-medium text-zinc-800">' . htmlspecialchars($row['name']) . '</h3>
            <p class="text-stone-500">
              <span class="text-zinc-800 font-semibold">$' . number_format($row['price'], 2) . '</span>
              <span>/day</span>
            </p>
            <button class="px-6 py-2 rounded-full bg-stone-500 text-white hover:bg-[#D4AF37] transition-colors">
              Rent Now
            </button>
          </div>
        </div>';
    }
  } else {
    echo '<p class="text-white text-center col-span-3">No scarfs available.</p>';
  }
  ?>
</div>

<footer class="bg-black text-white py-6 mt-10">
  <div class="flex justify-center items-center gap-3">
    <img src="weboxa-logo.png" alt="Weboxa Logo" class="h-5 w-5" />
    <a href="https://weboxa.com" target="_blank" class="text-sm hover:text-[#D4AF37] transition-colors">
      Powered by Weboxa
    </a>
  </div>
</footer>

</body>
</html>
