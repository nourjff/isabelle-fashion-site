<?php
session_start();
if (!isset($_SESSION['admin_logged_in'])) {
  header('Location: login.php');
  exit;
}

$host = 'localhost';
$user = 'root';
$password = '';
$dbname = 'isabelle_fashion';
$conn = new mysqli($host, $user, $password, $dbname);
if ($conn->connect_error) {
  die("Connection failed: " . $conn->connect_error);
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
  // Add new heel
  if (isset($_POST['add_heel'])) {
    $name = $_POST['name'];
    $size = $_POST['size'];

    if (isset($_FILES['image']) && $_FILES['image']['error'] === 0) {
      $imgName = basename($_FILES["image"]["name"]);
      $targetDir = "images/";
      $targetFile = $targetDir . $imgName;
      move_uploaded_file($_FILES["image"]["tmp_name"], $targetFile);

      $stmt = $conn->prepare("INSERT INTO heels (name, image_url, size) VALUES (?, ?, ?)");
      $stmt->bind_param("sss", $name, $targetFile, $size);
      $stmt->execute();
      $stmt->close();
    }
    header("Location: admin_heels.php");
    exit;
  }

  // Delete heel
  if (isset($_POST['delete_heel']) && isset($_POST['id'])) {
    $id = (int)$_POST['id'];
    $imgRes = $conn->query("SELECT image_url FROM heels WHERE id = $id");
    if ($imgRes && $imgRes->num_rows > 0) {
      $imgRow = $imgRes->fetch_assoc();
      if (file_exists($imgRow['image_url'])) {
        unlink($imgRow['image_url']);
      }
    }
    $conn->query("DELETE FROM heels WHERE id = $id");
    header("Location: admin_heels.php");
    exit;
  }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <title>Manage Heels</title>
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
<body class="bg-black text-white">

<nav class="flex justify-between items-center px-10 py-5 bg-black text-[#D4AF37]">
  <div class="flex items-center gap-4">
    <img src="SA logo - 2.png" alt="Logo" class="h-14 w-auto">
    <img src="SA logo.png" alt="Isabelle" class="h-12 md:h-16 w-auto">
  </div>
  <div class="flex gap-8 items-center">
    <a href="admin_dresses.php" class="hover:text-white">Dresses</a>
    <a href="admin_heels.php" class="text-white underline">Heels</a>
    <a href="admin_rentals.php" class="hover:text-white">Rentals</a>
    <a href="login.php" class="text-red-500 hover:text-white font-semibold">Logout</a>
  </div>
</nav>

<section class="max-w-6xl mx-auto mt-10 px-6">
  <h2 class="text-3xl font-extrabold text-[#D4AF37] mb-6">Manage Heels</h2>

  <div class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 gap-6">
    <?php
    $heels = $conn->query("SELECT * FROM heels ORDER BY id DESC");
    while ($heel = $heels->fetch_assoc()) {
      echo '<div class="bg-black border border-[#D4AF37] p-4 rounded-xl space-y-3 shadow text-white">';
      echo '<img src="' . $heel['image_url'] . '" class="w-full h-48 object-cover rounded">';
      echo '<h3 class="text-lg font-semibold">' . htmlspecialchars($heel['name']) . '</h3>';
      echo '<p class="text-sm text-gray-300">Size: ' . htmlspecialchars($heel['size']) . '</p>';
      echo '<form method="POST" onsubmit="return confirm(\'Are you sure you want to delete this heel?\');">';
      echo '<input type="hidden" name="delete_heel" value="1">';
      echo '<input type="hidden" name="id" value="' . $heel['id'] . '">';
      echo '<button type="submit" class="w-full mt-2 bg-red-600 text-white py-1 rounded hover:bg-red-800 font-semibold">Delete</button>';
      echo '</form>';
      echo '</div>';
    }
    ?>

    <!-- Add new heel -->
    <div class="bg-black border border-[#D4AF37] p-4 rounded-xl shadow space-y-4">
      <h3 class="text-xl font-semibold text-center text-[#D4AF37]">Add New Heel</h3>
      <form method="POST" enctype="multipart/form-data" class="space-y-3">
        <input type="hidden" name="add_heel" value="1">
        <input type="text" name="name" placeholder="Heel Name" required class="w-full p-2 rounded text-black border">
        <input type="text" name="size" placeholder="Size (e.g. 38, 39-40)" required class="w-full p-2 rounded text-black border">
        <input type="file" name="image" accept="image/*" required class="w-full p-2 rounded bg-white">
        <button type="submit" class="w-full bg-[#D4AF37] text-black py-2 rounded hover:bg-yellow-600 font-semibold">Add Heel</button>
      </form>
    </div>
  </div>
</section>

</body>
</html>
