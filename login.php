<?php
session_start();

// Admin credentials
$admin_username = 'admin';
$admin_password = 'admin123';

$error = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
  $username = $_POST['username'] ?? '';
  $password = $_POST['password'] ?? '';

  if ($username === $admin_username && $password === $admin_password) {
    $_SESSION['admin_logged_in'] = true;
    header('Location: admin_dashboard_1.php');

    exit;
  } else {
    $error = 'Invalid credentials. Please try again.';
  }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <title>Admin Login</title>
  <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="bg-black text-white min-h-screen">

<nav class="flex justify-between items-center px-10 py-5  bg-black text-[#D4AF37]">
  <div class="flex items-center gap-4">
    <img src="SA logo - 2.png" alt="Logo" class="h-14 w-auto">
    <img src="SA logo.png" alt="Isabelle" class="h-12 md:h-16 w-auto">
  </div>

</nav>

<div class="flex justify-center items-center mt-12">
  <form method="POST" class="bg-[#1e1e1e] border border-[#D4AF37] p-8 rounded-lg w-full max-w-sm">
    <h2 class="text-2xl font-bold text-[#D4AF37] mb-6 text-center">Admin Login</h2>

    <?php if ($error): ?>
      <p class="text-red-500 text-sm mb-4 text-center"><?php echo $error; ?></p>
    <?php endif; ?>

    <input type="text" name="username" placeholder="Username" required class="w-full mb-4 p-2 rounded bg-black border border-gray-600 text-white">
    <input type="password" name="password" placeholder="Password" required class="w-full mb-4 p-2 rounded bg-black border border-gray-600 text-white">

    <button type="submit" class="w-full bg-[#D4AF37] text-black py-2 rounded hover:bg-yellow-600 font-semibold">Login</button>
  </form>
</div>

</body>
</html>
