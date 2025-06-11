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
  <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/flatpickr/dist/flatpickr.min.css">
  <script src="https://cdn.tailwindcss.com"></script>
  <style>
    body {
      background-image: url('images/background.png');
      background-size: cover;
      background-repeat: no-repeat;
      background-attachment: fixed;
      background-position: center;
    }
  </style>
</head>
<body class="text-white">

<!-- Navbar -->
<nav class="flex justify-between items-center px-10 py-8 bg-black text-[#D4AF37]">
  <div class="flex items-center gap-4">
    <img src="SA logo - 2.png" alt="Logo" class="h-20 w-auto">
    <img src="SA logo.png" alt="Isabelle" class="h-24 md:h-16 w-auto">
  </div>
  <div class="flex gap-10 text-xl font-medium">
    <a href="index.html">Home</a>
    <a href="dresses.php">Dresses</a>
    <a href="heels.php" class="underline">Heels</a>
  </div>
</nav>

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

<!-- Note -->


<div class="w-full bg-[#D4AF37] text-black text-center py-3 text-base font-semibold animate-pulse">
 Heels are rented with some dresses for an extra fee of $7.
</div>

<!-- Footer -->
<!-- <footer class="bg-black text-white py-6 mt-10">
  <div class="flex justify-center items-center gap-3">
    <img src="weboxa.png" alt="Weboxa Logo" class="h-10 w-10" />
    <a href="https://weboxa.com" target="_blank" class="text-sm hover:text-[#D4AF37] transition-colors">Powered by Weboxa</a>
  </div>
</footer> -->

</body>
</html>
