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
    $stmt->bind_param("isssds", $_POST['rental_dress_id'], $_POST['rental_date'], $_POST['customer_name'], $_POST['phone'], $_POST['down_payment'], $_POST['return_date']);
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
  <script src="https://cdn.jsdelivr.net/npm/flatpickr">

    
  </script>
    <style>
      body {
    background-image: url('images/background.png'); /* or 'images/background.png' if it's in an images folder */
    background-size: cover;
    background-repeat: no-repeat;
    background-attachment: fixed;
    background-position: center;
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

<!-- Manage Dresses -->
<section class="max-w-6xl mx-auto mt-10 px-6">
  <h2 class="text-3xl font-extrabold text-[#D4AF37] mb-6">Manage Dresses</h2>
  <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
    <!-- Dress Cards Injected Here -->
    <?php
    $dresses = $conn->query("SELECT * FROM dresses ORDER BY id DESC");
    $unavailableByDress = [];
    $unavailableQuery = $conn->query("SELECT dress_id, rental_date, return_date FROM rentals");
    while ($row = $unavailableQuery->fetch_assoc()) {
      $start = new DateTime($row['rental_date']);
      $end = new DateTime($row['return_date']);
      $period = new DatePeriod($start, new DateInterval('P1D'), $end->modify('+0 day'));
      foreach ($period as $date) {
        $unavailableByDress[$row['dress_id']][] = $date->format('Y-m-d');
      }
    }
    $calendarJS .= "const unavailableByDress = " . json_encode($unavailableByDress) . ";\n";
    while ($dress = $dresses->fetch_assoc()) {
      $id = $dress['id'];
      $unavailable = [];
      $res = $conn->query("SELECT rental_date, return_date FROM rentals WHERE dress_id = $id");
      while ($r = $res->fetch_assoc()) {
        $start = new DateTime($r['rental_date']);
        $end = new DateTime($r['return_date']);
        $period = new DatePeriod($start, new DateInterval('P1D'), $end->modify('+0 day'));
        foreach ($period as $date) {
          $unavailable[] = $date->format('Y-m-d');
        }
      }
      $appointments = [];
      $res2 = $conn->query("SELECT trial_date FROM trials WHERE dress_id = $id");
      while ($t = $res2->fetch_assoc()) {
        $appointments[] = date('Y-m-d', strtotime($t['trial_date']));
      }
      $calendarJS .= "window['unavailable_$id'] = " . json_encode($unavailable) . ";\n";
      $calendarJS .= "window['appointments_$id'] = " . json_encode($appointments) . ";\n";
      echo '<div class="bg-black border border-[#D4AF37] text-white rounded-xl p-4 shadow space-y-3">
        <form method="POST">
          <input type="hidden" name="update_dress" value="1">
          <input type="hidden" name="id" value="' . $id . '">
          <img src="' . $dress['image_url'] . '" class="w-full h-40 object-cover rounded" />
          <input type="text" name="name" value="' . htmlspecialchars($dress['name']) . '" class="w-full border rounded px-2 py-1 text-black">
          <input type="text" name="size" value="' . htmlspecialchars($dress['size']) . '" class="w-full border rounded px-2 py-1 text-black">
          <input type="number" name="price" value="' . $dress['price'] . '" class="w-full border rounded px-2 py-1 text-black">
          <input type="number" name="discount" value="' . $dress['discount'] . '" class="w-full border rounded px-2 py-1 text-black" placeholder="Discount %">
          <button type="submit" class="w-full mt-2 bg-[#D4AF37] text-black font-semibold py-1 rounded hover:bg-yellow-500">Update</button>
        </form>
        <button onclick="toggleCalendar(' . $id . ')" class="text-sm text-[#D4AF37] underline">Show Calendar</button>
        <div id="calendar-wrapper-' . $id . '" class="hidden">
          <input type="text" id="calendar-' . $id . '" />
        </div>
      </div>';
    }
    ?>
  </div>
</section>

<!-- Add Rental -->
<section class="max-w-6xl mx-auto mt-10 px-6 bg-black border border-[#D4AF37] text-white p-6 rounded-xl">
  <h2 class="text-3xl font-extrabold text-[#D4AF37] mb-6">Add Rental</h2>
  <form method="POST" class="grid grid-cols-1 md:grid-cols-2 gap-4">
    <input type="hidden" name="add_rental" value="1">
    <select name="rental_dress_id" id="rental-dress-id" required class="border p-2 rounded text-black">
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

<script>
<?= $calendarJS ?>
function toggleCalendar(id) {
  const wrapper = document.getElementById('calendar-wrapper-' + id);
  wrapper.classList.toggle('hidden');
  const input = document.getElementById('calendar-' + id);
  if (!input.classList.contains('initialized')) {
    flatpickr(input, {
      inline: true,
      disable: window['unavailable_' + id] || [],
      dateFormat: "Y-m-d",
      onDayCreate: function(_, __, ___, dayElem) {
        const dateStr = dayElem.dateObj.toISOString().split('T')[0];
        if ((window['unavailable_' + id] || []).includes(dateStr)) {
          dayElem.style.background = '#dc2626';
          dayElem.style.color = 'white';
        } else if ((window['appointments_' + id] || []).includes(dateStr)) {
          dayElem.style.background = '#D4AF37';
          dayElem.style.color = 'black';
        }
      }
    });
    input.classList.add('initialized');
  }
}
</script>
</body>
</html>
