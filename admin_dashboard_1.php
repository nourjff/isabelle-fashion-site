<?php
session_start();
if (!isset($_SESSION['admin_logged_in'])) {
  header('Location: login.php');
  exit;
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <title>Admin Dashboard</title>
  <script src="https://cdn.tailwindcss.com"></script>
  <style>
    body {
      background-color: black;
      background-image: url('images/background.png');
      background-size: cover;
      background-repeat: no-repeat;
      background-attachment: fixed;
      background-position: center;
    }
  </style>
</head>
<body class="min-h-screen text-white">

<!-- Header -->
<nav class="flex justify-between items-center px-10 py-5 bg-black text-[#D4AF37]">
  <div class="flex items-center gap-4">
    <img src="SA logo - 2.png" alt="Logo" class="h-14 w-auto">
    <img src="SA logo.png" alt="Isabelle" class="h-12 md:h-16 w-auto">
  </div>
  <div class="flex gap-8 items-center">
    <a href="login.php" class="text-red-500 hover:text-white font-semibold">Logout</a>
  </div>
</nav>

<!-- Main Section -->
<div class="flex flex-col items-center justify-center mt-20 gap-10 px-6">
  <h1 class="text-4xl font-bold text-[#D4AF37]">Welcome, Admin</h1>
  
  <div class="flex flex-col md:flex-row gap-6">
    <a href="admin_dresses.php" class="bg-[#D4AF37] text-black px-10 py-4 rounded text-xl font-semibold hover:bg-yellow-500 transition">
      Manage Dresses
    </a>
    <a href="admin_heels.php" class="bg-[#D4AF37] text-black px-10 py-4 rounded text-xl font-semibold hover:bg-yellow-500 transition">
      Manage Heels
    </a>
  </div>
</div>

</body>
</html>
