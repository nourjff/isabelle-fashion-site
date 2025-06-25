<?php
$host = 'localhost';
$user = 'root';
$password = '';
$dbname = 'isabelle_fashion';
$conn = new mysqli($host, $user, $password, $dbname);
if ($conn->connect_error) {
  die("Connection failed: " . $conn->connect_error);
}

$calendarJS = "";

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
  if (isset($_POST['add_dress'])) {
    $name = $_POST['name'];
    $price = $_POST['price'];
    $size = $_POST['size'];

    if (isset($_FILES['image']) && $_FILES['image']['error'] === 0) {
      $imgName = basename($_FILES["image"]["name"]);
      $targetDir = "images/";
      $targetFile = $targetDir . $imgName;
      $imageFileType = strtolower(pathinfo($targetFile, PATHINFO_EXTENSION));
      $allowed = ['jpg', 'jpeg', 'png', 'gif'];

      if (in_array($imageFileType, $allowed)) {
        move_uploaded_file($_FILES["image"]["tmp_name"], $targetFile);
        $stmt = $conn->prepare("INSERT INTO dresses (name, price, size, image_url) VALUES (?, ?, ?, ?)");
        $stmt->bind_param("sdss", $name, $price, $size, $targetFile);
        $stmt->execute();
        $stmt->close();
      }
      header("Location: admin_dashboard.php");
      exit;
    }
  }

  if (isset($_POST['update_dress'])) {
    $stmt = $conn->prepare("UPDATE dresses SET name=?, price=?, size=?, discount=? WHERE id=?");
    $stmt->bind_param("sdsii", $_POST['name'], $_POST['price'], $_POST['size'], $_POST['discount'], $_POST['id']);
    $stmt->execute();
    $stmt->close();
  }

  if (isset($_POST['add_rental'])) {
    $stmt = $conn->prepare("INSERT INTO rentals (dress_id, rental_date, customer_name, customer_phone, down_payment, return_date) VALUES (?, ?, ?, ?, ?, ?)");
    $stmt->bind_param("isssds", $_POST['dress_id'], $_POST['rental_date'], $_POST['customer_name'], $_POST['customer_phone'], $_POST['down_payment'], $_POST['return_date']);
    $stmt->execute();
    $stmt->close();
    header("Location: admin_dashboard.php");
    exit;
  }
}

$rentalSummary = $conn->query("
  SELECT r.*, d.name AS dress_name, d.price AS rental_price
  FROM rentals r
  JOIN dresses d ON r.dress_id = d.id
  ORDER BY r.rental_date DESC
");
?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8" />
  <title>Admin Dashboard</title>
  <script src="https://cdn.tailwindcss.com"></script>
  <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/flatpickr/dist/flatpickr.min.css">
  <script src="https://cdn.jsdelivr.net/npm/flatpickr"></script>
  <style>
    body {
      background-image: url('images/background.png');
      background-size: cover;
      background-repeat: no-repeat;
      background-attachment: fixed;
      background-position: center;
    }
    .flatpickr-day.rental-date {
      background: #dc2626 !important;
      color: white !important;
    }
    .flatpickr-day.trial-date {
      background: #D4AF37 !important;
      color: black !important;
    }
    .modal {
      position: fixed;
      top: 0;
      left: 0;
      right: 0;
      bottom: 0;
      background: rgba(0,0,0,0.7);
      display: flex;
      align-items: center;
      justify-content: center;
      z-index: 50;
    }
  </style>
</head>
<body class="bg-black text-white">

<!-- Add Dress -->
<section class="max-w-4xl mx-auto mt-10 px-6 bg-black border border-[#D4AF37] p-6 rounded-xl">
  <h2 class="text-3xl font-extrabold text-[#D4AF37] mb-6">Add New Dress</h2>
  <form method="POST" enctype="multipart/form-data" class="grid grid-cols-1 md:grid-cols-2 gap-4">
    <input type="hidden" name="add_dress" value="1">
    <input type="text" name="name" placeholder="Dress Name" required class="border p-2 rounded text-black">
    <input type="text" name="size" placeholder="Size (e.g. M, 42-44)" required class="border p-2 rounded text-black">
    <input type="number" step="0.01" name="price" placeholder="Price" required class="border p-2 rounded text-black">
    <input type="file" name="image" accept="image/*" required class="border p-2 rounded bg-white">
    <button type="submit" class="col-span-2 w-full bg-[#D4AF37] text-black py-2 rounded hover:bg-yellow-600 font-semibold">Add Dress</button>
  </form>
</section>

<!-- Manage Dresses with Rental Modal Button -->
<section class="max-w-6xl mx-auto mt-10 px-6">
  <h2 class="text-3xl font-extrabold text-[#D4AF37] mb-6">Manage Dresses</h2>
  <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
    <?php
    $dresses = $conn->query("SELECT * FROM dresses ORDER BY id DESC");
    while ($dress = $dresses->fetch_assoc()) {
      $id = $dress['id'];

      // Build rental and trial date data for Flatpickr
      $rentalDates = [];
      $res = $conn->query("SELECT rental_date FROM rentals WHERE dress_id = $id");
      while ($r = $res->fetch_assoc()) {
        $date = $r['rental_date'];
        $dayBefore = date('Y-m-d', strtotime($date . ' -1 day'));
        $dayAfter  = date('Y-m-d', strtotime($date . ' +1 day'));
        $rentalDates[] = $dayBefore;
        $rentalDates[] = $date;
        $rentalDates[] = $dayAfter;
      }
      $trialDates = [];
      $res2 = $conn->query("SELECT trial_date FROM trials WHERE dress_id = $id");
      while ($t = $res2->fetch_assoc()) {
        $trialDates[] = $t['trial_date'];
      }
      echo "<div class='bg-black border border-[#D4AF37] text-white rounded-xl p-4 shadow space-y-3'>
        <form method='POST'>
          <input type='hidden' name='update_dress' value='1'>
          <input type='hidden' name='id' value='{$id}'>
          <img src='{$dress['image_url']}' class='w-full h-40 object-cover rounded' />
          <input type='text' name='name' value='" . htmlspecialchars($dress['name']) . "' class='w-full border rounded px-2 py-1 text-black'>
          <input type='text' name='size' value='{$dress['size']}' class='w-full border rounded px-2 py-1 text-black'>
          <input type='number' name='price' value='{$dress['price']}' class='w-full border rounded px-2 py-1 text-black'>
          <input type='number' name='discount' value='{$dress['discount']}' class='w-full border rounded px-2 py-1 text-black' placeholder='Discount %'>
          <button type='submit' class='w-full mt-2 bg-[#D4AF37] text-black font-semibold py-1 rounded hover:bg-yellow-500'>Update</button>
        </form>
        <button onclick='openRentalModal({$id})' class='w-full bg-green-600 text-white py-2 rounded hover:bg-green-700 font-semibold'>Add Rental</button>
        <script>
          window.unavailable_{$id} = " . json_encode(array_values(array_unique($rentalDates))) . ";
          window.trials_{$id} = " . json_encode($trialDates) . ";
        </script>
      </div>";
    }
    ?>
  </div>
</section>

<!-- Rental Modal (reusable) -->
<div id="rental-modal" class="modal hidden">
  <div class="bg-white text-black p-6 rounded-lg w-full max-w-lg relative">
    <button onclick="closeRentalModal()" class="absolute top-2 right-2 text-black">&times;</button>
    <form method="POST" id="rental-form">
      <input type="hidden" name="add_rental" value="1">
      <input type="hidden" name="dress_id" id="modal-dress-id">
      <label class="font-bold">Customer Name</label>
      <input type="text" name="customer_name" required class="w-full mb-2 p-2 border rounded">
      <label class="font-bold">Customer Phone</label>
      <input type="text" name="customer_phone" required class="w-full mb-2 p-2 border rounded">
      <label class="font-bold">Rental Date</label>
      <input type="text" name="rental_date" id="rental-date-picker" required class="w-full mb-2 p-2 border rounded">
      <label class="font-bold">Return Date (auto-filled)</label>
      <input type="text" name="return_date" id="return-date" readonly class="w-full mb-2 p-2 border rounded">
      <label class="font-bold">Down Payment</label>
      <input type="number" name="down_payment" step="0.01" required class="w-full mb-4 p-2 border rounded">
      <button type="submit" class="w-full bg-[#D4AF37] text-black font-semibold py-2 rounded hover:bg-yellow-500">Submit Rental</button>
    </form>
  </div>
</div>

<!-- Rental Summary Table -->
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

<script>
function openRentalModal(dressId) {
  document.getElementById('rental-modal').classList.remove('hidden');
  document.getElementById('modal-dress-id').value = dressId;
  const unavailable = window['unavailable_' + dressId] || [];
  const trials = window['trials_' + dressId] || [];
  const rentalPicker = flatpickr("#rental-date-picker", {
    minDate: "today",
    disable: unavailable,
    dateFormat: "Y-m-d",
    onDayCreate: function(dObj, dStr, fp, dayElem) {
      const dateStr = dayElem.dateObj.toISOString().split("T")[0];
      if (unavailable.includes(dateStr)) dayElem.classList.add("rental-date");
      if (trials.includes(dateStr)) {
        dayElem.classList.add("trial-date");
        dayElem.title = "Trial date: " + dateStr;
      }
    },
    onChange: function(selectedDates) {
      if (selectedDates.length > 0) {
        const d = new Date(selectedDates[0]);
        d.setDate(d.getDate() + 1);
        document.getElementById('return-date').value = d.toISOString().split('T')[0];
      }
    }
  });
}
function closeRentalModal() {
  document.getElementById('rental-modal').classList.add('hidden');
  document.getElementById('rental-form').reset();
  document.getElementById('return-date').value = '';
}
</script>
</body>
</html>
