<?php
$host = 'localhost';
$user = 'root';
$password = '';
$dbname = 'isabelle_fashion';
$conn = new mysqli($host, $user, $password, $dbname);
if ($conn->connect_error) {
  die("Connection failed: " . $conn->connect_error);
}

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['add_rental'])) {
  $stmt = $conn->prepare("INSERT INTO rentals (dress_id, rental_date, customer_name, customer_phone, down_payment, return_date) VALUES (?, ?, ?, ?, ?, ?)");
  $stmt->bind_param("isssds", $_POST['rental_dress_id'], $_POST['rental_date'], $_POST['customer_name'], $_POST['phone'], $_POST['down_payment'], $_POST['return_date']);
  $stmt->execute();
  $stmt->close();
  header("Location: admin_rentals.php");
  exit;
}

$rentalSummary = $conn->query("SELECT r.*, d.name AS dress_name, d.price AS rental_price FROM rentals r JOIN dresses d ON r.dress_id = d.id ORDER BY r.rental_date DESC");
?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <title>Manage Dresses</title>
  <script src="https://cdn.tailwindcss.com"></script>
    <style>
    body {
      background-image: url('images/background.png');
      background-size: cover;
      background-repeat: no-repeat;
      background-attachment: fixed;
      background-position: center;
    }
    <link href="https://fonts.googleapis.com/css2?family=Playfair+Display:ital,wght@1,400&family=Work+Sans:wght@300;400;600&display=swap" rel="stylesheet">
<style>
  body {
    font-family: 'Work Sans', sans-serif;
  }

  .sub-font {
    font-family: 'Playfair Display', serif;
    font-style: italic;
  }
</style>

  </style>
  <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/flatpickr/dist/flatpickr.min.css">
  <script src="https://cdn.jsdelivr.net/npm/flatpickr"></script>
</head>
<body class="bg-black text-white">

<nav class="flex justify-between items-center px-10 py-5  bg-black text-[#D4AF37]">
  <div class="flex items-center gap-4">
    <img src="SA logo - 2.png" alt="Logo" class="h-14 w-auto">
    <img src="SA logo.png" alt="Isabelle" class="h-12 md:h-16 w-auto">
  </div>
  <div class="flex gap-8 items-center">
    <a href="admin_dresses.php" class="hover:text-white">Dresses</a>
    <a href="admin_rentals.php" class="text-white underline">Rentals</a>
    <a href="login.php" class="text-red-500 hover:text-white font-semibold">Logout</a>
  </div>
</nav>

<!-- Add Rental -->
<section class="max-w-6xl mx-auto mt-10 px-6 bg-black border border-[#D4AF37] text-white p-6 rounded-xl">
  <h2 class="text-3xl font-extrabold text-[#D4AF37] mb-6">Add Rental</h2>
  <form method="POST" class="grid grid-cols-1 md:grid-cols-2 gap-4">
    <input type="hidden" name="add_rental" value="1">
    <select name="rental_dress_id" required class="border p-2 rounded text-black">
      <option value="">-- Select Dress --</option>
      <?php
      $res = $conn->query("SELECT id, name FROM dresses");
      while ($d = $res->fetch_assoc()) {
        echo "<option value='{$d['id']}'>{$d['name']}</option>";
      }
      ?>
    </select>
    <input type="text" name="customer_name" placeholder="Customer Name" class="border p-2 rounded text-black" required>
    <input type="text" name="phone" placeholder="Customer Phone" class="border p-2 rounded text-black" required>
    <input type="text" id="rental-date" name="rental_date" placeholder="Rental Date" class="border p-2 rounded text-black bg-white" required>
    <input type="text" id="return-date" name="return_date" placeholder="Return Date" class="border p-2 rounded text-black bg-white" required>
    <input type="number" name="down_payment" step="0.01" placeholder="Down Payment" class="border p-2 rounded text-black" required>
    <button type="submit" class="col-span-2 w-full bg-[#D4AF37] text-black py-2 rounded hover:bg-yellow-600 font-semibold">Add Rental</button>
  </form>
</section>

<!-- Rental Summary -->
<section class="max-w-6xl mx-auto mt-10 px-6 bg-black border border-[#D4AF37] text-white p-6 rounded-xl">
  <h2 class="text-3xl font-extrabold text-[#D4AF37] mb-6">Rental Summary</h2>
  <table class="w-full table-auto border-collapse">
    <thead>
      <tr class="bg-[#D4AF37] text-black">
        <th class="p-2 border">Dress</th>
        <th class="p-2 border">Customer</th>
        <th class="p-2 border">Rental Date</th>
        <th class="p-2 border">Return Date</th>
        <th class="p-2 border">Price</th>
        <th class="p-2 border">Down Payment</th>
        <th class="p-2 border">Remaining</th>
      </tr>
    </thead>
    <tbody>
      <?php
      if ($rentalSummary->num_rows > 0) {
        while ($row = $rentalSummary->fetch_assoc()) {
          $remaining = $row['rental_price'] - $row['down_payment'];
          echo "<tr>
            <td class='p-2 border'>{$row['dress_name']}</td>
            <td class='p-2 border'>{$row['customer_name']}</td>
            <td class='p-2 border'>{$row['rental_date']}</td>
            <td class='p-2 border'>{$row['return_date']}</td>
            <td class='p-2 border'>\$" . number_format($row['rental_price'], 2) . "</td>
            <td class='p-2 border'>\$" . number_format($row['down_payment'], 2) . "</td>
            <td class='p-2 border'>\$" . number_format($remaining, 2) . "</td>
          </tr>";
        }
      } else {
        echo "<tr><td colspan='7' class='p-4 text-center'>No rentals yet.</td></tr>";
      }
      ?>
    </tbody>
  </table>
</section>

<!-- Trial Summary -->
<section class="max-w-6xl mx-auto mt-10 px-6 bg-black border border-[#D4AF37] text-white p-6 rounded-xl">
  <h2 class="text-3xl font-extrabold text-[#D4AF37] mb-6">Trial Appointments</h2>
  <table class="w-full table-auto border-collapse">
    <thead>
      <tr class="bg-[#D4AF37] text-black">
        <th class="p-2 border">Dress</th>
        <th class="p-2 border">Name</th>
        <th class="p-2 border">Phone</th>
        <th class="p-2 border">Trial Date</th>
        <th class="p-2 border">Time</th>
        <th class="p-2 border">Booked At</th>
      </tr>
    </thead>
    <tbody>
      <?php
      $trials = $conn->query("
        SELECT t.*, d.name AS dress_name
        FROM trials t
        JOIN dresses d ON t.dress_id = d.id
        ORDER BY t.trial_date DESC, t.trial_time ASC
      ");
      if ($trials->num_rows > 0) {
        while ($row = $trials->fetch_assoc()) {
          echo "<tr>
            <td class='p-2 border'>{$row['dress_name']}</td>
            <td class='p-2 border'>" . htmlspecialchars($row['user_name']) . "</td>
            <td class='p-2 border'>" . htmlspecialchars($row['phone']) . "</td>
            <td class='p-2 border'>{$row['trial_date']}</td>
            <td class='p-2 border'>{$row['trial_time']}</td>
            <td class='p-2 border'>{$row['created_at']}</td>
          </tr>";
        }
      } else {
        echo "<tr><td colspan='6' class='p-4 text-center'>No trial appointments found.</td></tr>";
      }
      ?>
    </tbody>
  </table>
</section>


<script>
flatpickr("#rental-date", {
  minDate: "today",
  dateFormat: "Y-m-d"
});
flatpickr("#return-date", {
  minDate: new Date().fp_incr(1),
  dateFormat: "Y-m-d"
});
</script>

</body>
</html>
