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
      move_uploaded_file($_FILES["image"]["tmp_name"], $targetFile);
      $stmt = $conn->prepare("INSERT INTO dresses (name, price, size, image_url) VALUES (?, ?, ?, ?)");
      $stmt->bind_param("sdss", $name, $price, $size, $targetFile);
      $stmt->execute();
      $dress_id = $stmt->insert_id;

      // Link selected scarfs
      if (!empty($_POST['scarfs'])) {
        $scarfStmt = $conn->prepare("INSERT INTO dress_scarfs (dress_id, scarf_id) VALUES (?, ?)");
        foreach ($_POST['scarfs'] as $scarfId) {
          $scarfStmt->bind_param("ii", $dress_id, $scarfId);
          $scarfStmt->execute();
        }
        $scarfStmt->close();
      }

      // Handle new scarfs
      if (!empty($_POST['new_scarf_names']) && isset($_FILES['new_scarf_images'])) {
        $names = $_POST['new_scarf_names'];
        $images = $_FILES['new_scarf_images'];
        $insertedScarfIds = [];

        for ($i = 0; $i < count($names); $i++) {
          if (!empty($names[$i]) && $images['error'][$i] === 0) {
            $scarfImgName = basename($images["name"][$i]);
            $scarfFile = $targetDir . $scarfImgName;
            move_uploaded_file($images["tmp_name"][$i], $scarfFile);

            $stmt2 = $conn->prepare("INSERT INTO scarfs (name, image_url) VALUES (?, ?)");
            $stmt2->bind_param("ss", $names[$i], $scarfFile);
            $stmt2->execute();
            $insertedScarfIds[] = $stmt2->insert_id;
            $stmt2->close();
          }
        }

        if (!empty($insertedScarfIds)) {
          $linkStmt = $conn->prepare("INSERT INTO dress_scarfs (dress_id, scarf_id) VALUES (?, ?)");
          foreach ($insertedScarfIds as $sid) {
            $linkStmt->bind_param("ii", $dress_id, $sid);
            $linkStmt->execute();
          }
          $linkStmt->close();
        }
      }

      $stmt->close();
      header("Location: admin_dresses.php");
      exit;
    }
  }

  if (isset($_POST['update_dress'])) {
    $stmt = $conn->prepare("UPDATE dresses SET name=?, price=?, size=?, discount=? WHERE id=?");
    $stmt->bind_param("sdsii", $_POST['name'], $_POST['price'], $_POST['size'], $_POST['discount'], $_POST['id']);
    $stmt->execute();
    $stmt->close();
  }

  if (isset($_POST['delete_dress']) && isset($_POST['id'])) {
    $id = (int)$_POST['id'];
    $imgRes = $conn->query("SELECT image_url FROM dresses WHERE id = $id");
    if ($imgRes && $imgRes->num_rows > 0) {
      $imgRow = $imgRes->fetch_assoc();
      if (file_exists($imgRow['image_url'])) {
        unlink($imgRow['image_url']);
      }
    }

    $conn->query("DELETE FROM trials WHERE dress_id = $id");
    $conn->query("DELETE FROM rentals WHERE dress_id = $id");
    $conn->query("DELETE FROM dress_scarfs WHERE dress_id = $id");
    $conn->query("DELETE FROM dresses WHERE id = $id");

    header("Location: admin_dresses.php");
    exit;
  }

  if (isset($_POST['add_scarf_to_dress'])) {
  $dressId = (int)$_POST['dress_id'];
  $scarfId = (int)$_POST['scarf_id'];
  // Prevent duplicates
  $check = $conn->query("SELECT 1 FROM dress_scarfs WHERE dress_id = $dressId AND scarf_id = $scarfId");
  if ($check && $check->num_rows == 0) {
    $stmt = $conn->prepare("INSERT INTO dress_scarfs (dress_id, scarf_id) VALUES (?, ?)");
    $stmt->bind_param("ii", $dressId, $scarfId);
    $stmt->execute();
    $stmt->close();
  }
  header("Location: admin_dresses.php");
  exit;


}

if (isset($_POST['remove_scarf_from_dress'])) {
  $dressId = (int)$_POST['dress_id'];
  $scarfId = (int)$_POST['scarf_id'];

  $stmt = $conn->prepare("DELETE FROM dress_scarfs WHERE dress_id = ? AND scarf_id = ?");
  $stmt->bind_param("ii", $dressId, $scarfId);
  $stmt->execute();
  $stmt->close();

  header("Location: admin_dresses.php");
  exit;
}

if (isset($_POST['add_rental'])) {
  $stmt = $conn->prepare("INSERT INTO rentals (dress_id, rental_date, customer_name, customer_phone, down_payment, return_date) VALUES (?, ?, ?, ?, ?, ?)");
  $stmt->bind_param("isssds", $_POST['dress_id'], $_POST['rental_date'], $_POST['customer_name'], $_POST['customer_phone'], $_POST['down_payment'], $_POST['return_date']);
  $stmt->execute();
  $stmt->close();
  header("Location: admin_dresses.php");
  exit;
}

if (isset($_POST['add_trial'])) {
  $stmt = $conn->prepare("INSERT INTO trials (dress_id, trial_date, trial_time, user_name, phone, created_at) VALUES (?, ?, ?, ?, ?, NOW())");
  $stmt->bind_param("issss", $_POST['dress_id'], $_POST['trial_date'], $_POST['trial_time'], $_POST['customer_name'], $_POST['customer_phone']);
  $stmt->execute();
  $stmt->close();
  header("Location: admin_dresses.php");
  exit;
}


}
?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <title>Manage Dresses</title>
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

    .flatpickr-day.rental-day {
  background: #dc2626 !important;
  color: white !important;
}
.flatpickr-day.trial-day {
  background: #facc15 !important;
  color: black !important;
}
.input-error {
  border-color: red;
}
.error-msg {
  color: red;
  font-size: 0.875rem;
  margin-top: -8px;
  margin-bottom: 8px;
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
    <a href="admin_dresses.php" class="text-white underline">Dresses</a>
    <a href="admin_rentals.php" class="hover:text-white">Rentals</a>
    <a href="login.php" class="text-red-500 hover:text-white font-semibold">Logout</a>
  </div>
</nav>

<section class="max-w-6xl mx-auto mt-10 px-6">
  <h2 class="text-3xl font-extrabold text-[#D4AF37] mb-6">Manage Dresses</h2>
<?php
$searchTerm = $_GET['search'] ?? '';
$selectedColor = $_GET['color'] ?? '';
?>

<!-- ✅ Combined Filter Form -->
<form method="GET" class="mb-6 flex flex-col md:flex-row md:items-center gap-4">
  <!-- 🔍 Search Bar -->
  <div class="relative w-full md:w-1/3">
    <input type="text" name="search" id="searchInput" placeholder="Search by dress name..."
           value="<?php echo htmlspecialchars($searchTerm); ?>"
           class="w-full p-2 rounded border border-[#D4AF37] text-black">
    <ul id="suggestionsList"
        class="absolute bg-white text-black border border-[#D4AF37] rounded mt-1 w-full max-h-48 overflow-y-auto z-50 hidden"></ul>
  </div>

  <!-- 🎨 Color Dropdown -->
  <div class="flex items-center gap-2">
    <label for="color" class="font-semibold">Color:</label>
    <select name="color" id="color" onchange="this.form.submit()" class="p-2 rounded border text-black">
      <option value="">All Colors</option>
      <?php
      $colorQuery = $conn->query("SELECT DISTINCT color FROM dresses WHERE color IS NOT NULL AND color != '' ORDER BY color");
      while ($row = $colorQuery->fetch_assoc()) {
        $color = $row['color'];
        $selected = ($selectedColor === $color) ? 'selected' : '';
        echo "<option value=\"" . htmlspecialchars($color) . "\" $selected>" . ucfirst(htmlspecialchars($color)) . "</option>";
      }
      ?>
    </select>
  </div>

  <!-- 🔘 Search Button -->
  <button type="submit"
          class="bg-[#D4AF37] text-black px-4 py-2 rounded hover:bg-yellow-500 font-semibold">
    Search
  </button>



  </select>

  <!-- Preserve search term -->
  <?php if (!empty($searchTerm)): ?>
    <input type="hidden" name="search" value="<?php echo htmlspecialchars($searchTerm); ?>">
  <?php endif; ?>
</form>

<?php if (!empty($selectedColor)): ?>
  <form method="GET" class="mb-6">
    <!-- Optional: preserve search when clearing only color -->
    <?php if (!empty($searchTerm)): ?>
      <input type="hidden" name="search" value="<?php echo htmlspecialchars($searchTerm); ?>">
    <?php endif; ?>
    <button type="submit" class="text-sm text-white underline hover:text-[#D4AF37]">Clear Color Filter</button>
  </form>
<?php endif; ?>



  <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
    <!-- + Button Card at the Top -->
<div class="bg-black border border-[#D4AF37] text-white rounded-xl p-4 shadow flex flex-col justify-center items-center cursor-pointer" onclick="openModal()">
  <span class="text-5xl text-[#D4AF37] hover:text-white">+</span>
</div>

<?php
$searchTerm = $_GET['search'] ?? '';
$selectedColor = $_GET['color'] ?? '';

$conditions = [];
$params = [];
$types = '';

if (!empty($searchTerm)) {
  $conditions[] = "name LIKE ?";
  $params[] = '%' . $searchTerm . '%';
  $types .= 's';
}

if (!empty($selectedColor)) {
  $conditions[] = "color = ?";
  $params[] = $selectedColor;
  $types .= 's';
}

$sql = "SELECT * FROM dresses";
if (!empty($conditions)) {
  $sql .= " WHERE " . implode(" AND ", $conditions);
}
$sql .= " ORDER BY id DESC";

$stmt = $conn->prepare($sql);
if (!empty($params)) {
  $stmt->bind_param($types, ...$params);
}
$stmt->execute();
$dresses = $stmt->get_result();


while ($dress = $dresses->fetch_assoc()) {
  $id = $dress['id'];

  // Get linked scarfs
  $scarfsResult = $conn->query("SELECT s.name FROM scarfs s
                                INNER JOIN dress_scarfs ds ON s.id = ds.scarf_id
                                WHERE ds.dress_id = $id");
  $scarfNames = [];
  while ($row = $scarfsResult->fetch_assoc()) {
    $scarfNames[] = htmlspecialchars($row['name']);
  }
$scarfsHtml = '';
if (empty($scarfNames)) {
  $scarfsHtml = '<span class="text-gray-400">No scarfs linked</span>';
} else {
  $scarfsResult = $conn->query("SELECT s.id, s.name FROM scarfs s
                                INNER JOIN dress_scarfs ds ON s.id = ds.scarf_id
                                WHERE ds.dress_id = $id");
  while ($row = $scarfsResult->fetch_assoc()) {
    $scarfId = $row['id'];
    $scarfName = htmlspecialchars($row['name']);
    $scarfsHtml .= '
      <form method="POST" class="inline-block mr-2">
        <input type="hidden" name="remove_scarf_from_dress" value="1">
        <input type="hidden" name="dress_id" value="' . $id . '">
        <input type="hidden" name="scarf_id" value="' . $scarfId . '">
        <span class="text-[#D4AF37] text-sm">' . $scarfName . '</span>
        <button type="submit" title="Remove" class="text-red-500 ml-1 font-bold hover:text-white">×</button>
      </form>';
  }
}


  // Get all available scarfs for dropdown
  $allScarfs = $conn->query("SELECT id, name FROM scarfs ORDER BY name ASC");

  echo '<div class="bg-black border border-[#D4AF37] text-white rounded-xl p-4 shadow space-y-3">
    <form method="POST" enctype="multipart/form-data">
      <input type="hidden" name="update_dress" value="1">
      <input type="hidden" name="id" value="' . $id . '">
      <img src="' . $dress['image_url'] . '" class="w-full h-40 object-cover rounded" />
      <input type="text" name="name" value="' . htmlspecialchars($dress['name']) . '" class="w-full border rounded px-2 py-1 text-black">
      <input type="text" name="size" value="' . htmlspecialchars($dress['size']) . '" class="w-full border rounded px-2 py-1 text-black">
      <input type="number" name="price" value="' . $dress['price'] . '" class="w-full border rounded px-2 py-1 text-black">
      <input type="number" name="discount" value="' . $dress['discount'] . '" class="w-full border rounded px-2 py-1 text-black" placeholder="Discount %">

 <div class="text-sm mt-1"><strong class="text-[#D4AF37]">Available Scarfs:</strong><br>' . $scarfsHtml . '</div>


      <button type="submit" class="w-full mt-2 bg-[#D4AF37] text-black font-semibold py-1 rounded hover:bg-yellow-500">Update</button>
    </form>

    <!-- Add scarf to existing dress -->
    <form method="POST" class="mt-2">
      <input type="hidden" name="add_scarf_to_dress" value="1">
      <input type="hidden" name="dress_id" value="' . $id . '">
      <div class="flex gap-2 items-center">
        <select name="scarf_id" class="flex-1 border rounded px-2 py-1 text-black">
          <option value="">Select scarf...</option>';
          while ($scarf = $allScarfs->fetch_assoc()) {
            echo '<option value="' . $scarf['id'] . '">' . htmlspecialchars($scarf['name']) . '</option>';
          }
  echo '  </select>
        <button type="submit" class="bg-[#D4AF37] text-black px-3 py-1 rounded hover:bg-yellow-600">Add</button>
      </div>
    </form>

<form method="POST" onsubmit="return confirm(\'Are you sure you want to delete this dress?\');">
  <input type="hidden" name="delete_dress" value="1">
  <input type="hidden" name="id" value="' . $id . '">
  <button type="submit" class="w-full mt-2 bg-red-600 text-white font-semibold py-1 rounded hover:bg-red-800">Delete</button>
</form>

<form method="button">
  <button type="button" onclick="showCalendar(' . $id . ')" class="w-full mt-2 bg-[#D4AF37] text-black font-semibold py-1 rounded hover:bg-yellow-500">
    Show Calendar
  </button>
</form>
<form method="button">
  <button type="button" onclick="openRentalModal(' . $id . ')" class="w-full mt-2 bg-green-600 text-white font-semibold py-1 rounded hover:bg-green-700">
    Book Rental
  </button>
</form>
<form method="button">
  <button type="button" onclick="openTrialModal(' . $id . ')" class="w-full mt-2 bg-blue-600 text-white font-semibold py-1 rounded hover:bg-blue-700">
    Book Trial
  </button>
</form>



  </div>';
}
?>

  </div>
</section>

<!-- Add Dress Modal -->
<div id="addDressModal" class="fixed inset-0 bg-black bg-opacity-80 z-50 hidden items-center justify-center">
  <div class="bg-black border border-[#D4AF37] rounded-xl p-6 w-full max-w-3xl relative text-white">
    <button onclick="closeModal()" class="absolute top-2 right-4 text-2xl text-[#D4AF37] hover:text-white">&times;</button>
    <h2 class="text-3xl font-extrabold text-[#D4AF37] mb-6">Add New Dress</h2>
    <form method="POST" enctype="multipart/form-data" class="grid grid-cols-1 md:grid-cols-2 gap-4">
      <input type="hidden" name="add_dress" value="1">
      <input type="text" name="name" placeholder="Dress Name" required class="border p-2 rounded text-black">
      <input type="text" name="size" placeholder="Size (e.g. M, 42-44)" required class="border p-2 rounded text-black">
      <input type="number" step="0.01" name="price" placeholder="Price" required class="border p-2 rounded text-black">
      <input type="file" name="image" accept="image/*" required class="border p-2 rounded bg-white">

      <!-- Existing scarfs -->
      <div class="col-span-2">
        <p class="mb-1 font-semibold text-white">Select Scarfs to associate with this dress (optional):</p>
        <div class="grid grid-cols-2 md:grid-cols-3 gap-2">
          <?php
          $scarfs = $conn->query("SELECT id, name FROM scarfs");
          while ($scarf = $scarfs->fetch_assoc()) {
            echo '<label class="flex items-center gap-2 text-sm">
                    <input type="checkbox" name="scarfs[]" value="' . $scarf['id'] . '" class="accent-[#D4AF37]">
                    ' . htmlspecialchars($scarf['name']) . '
                  </label>';
          }
          ?>
        </div>
      </div>

      <!-- New scarf entry -->
      <div class="col-span-2">
        <button type="button" onclick="document.getElementById('new-scarfs-section').classList.toggle('hidden')" class="text-sm underline text-[#D4AF37] hover:text-white">
          + Add new scarfs
        </button>

        <div id="new-scarfs-section" class="hidden mt-3 space-y-2">
          <div class="grid grid-cols-2 gap-2" id="newScarfInputs">
            <div>
              <input type="text" name="new_scarf_names[]" placeholder="Scarf Name" class="w-full p-2 rounded text-black border" />
              <input type="file" name="new_scarf_images[]" accept="image/*" class="w-full mt-1 text-white" />
            </div>
          </div>
          <button type="button" onclick="addNewScarfInput()" class="text-sm mt-2 underline text-[#D4AF37] hover:text-white">+ Add another scarf</button>
        </div>
      </div>

      <button type="submit" class="col-span-2 w-full bg-[#D4AF37] text-black py-2 rounded hover:bg-yellow-600 font-semibold">Add Dress</button>
    </form>
  </div>
</div>

<script>
function openModal() {
  document.getElementById("addDressModal").classList.remove("hidden");
  document.getElementById("addDressModal").classList.add("flex");
}

function closeModal() {
  document.getElementById("addDressModal").classList.add("hidden");
  document.getElementById("addDressModal").classList.remove("flex");
}

function addNewScarfInput() {
  const container = document.getElementById('newScarfInputs');
  const div = document.createElement('div');
  div.innerHTML = `
    <input type="text" name="new_scarf_names[]" placeholder="Scarf Name" class="w-full p-2 rounded text-black border" />
    <input type="file" name="new_scarf_images[]" accept="image/*" class="w-full mt-1 text-white" />
  `;
  container.appendChild(div);
}
</script>
<div id="calendarModal" class="fixed inset-0 bg-black bg-opacity-70 hidden items-center justify-center z-50">
  <div class="bg-black p-6 rounded-xl border border-[#D4AF37] w-full max-w-md text-white relative">
    <button onclick="closeCalendar()" class="absolute top-2 right-4 text-2xl text-[#D4AF37] hover:text-white">&times;</button>
    <h2 class="text-xl font-bold text-[#D4AF37] mb-3">Dress Calendar</h2>
    <input id="calendar" class="w-full p-2 rounded text-black" placeholder="Calendar will load here" readonly>
  </div>
</div>
<script>
function showCalendar(dressId) {
  fetch(`get_calendar_dates.php?dress_id=${dressId}`)
    .then(res => res.json())
    .then(data => {
      const rentals = data.rentals;
      const trials = data.trials;

      const allDates = {};
      rentals.forEach(date => {
        const d = new Date(date);
        [ -1, 0, 1 ].forEach(offset => {
          const day = new Date(d);
          day.setDate(d.getDate() + offset);
          const iso = day.toISOString().split('T')[0];
          allDates[iso] = 'rental-day';
        });
      });

      trials.forEach(item => {
        allDates[item.date] = 'trial-day';
      });
      if (window.rentalPicker) {
  window.rentalPicker.destroy();
}


window.rentalPicker = flatpickr("#rentalDate", {
  minDate: "today",
  disable: disableDates,
  dateFormat: "Y-m-d",
  onDayCreate: (dObj, dStr, fp, dayElem) => {
    const iso = dayElem.dateObj.toISOString().split("T")[0];
    if (disableDates.includes(iso)) dayElem.classList.add("rental-day");
    if (trialDates.includes(iso)) {
      dayElem.classList.add("trial-day");
      dayElem.title = "Trial date";
    }
  },
  onChange: function(selectedDates) {
    if (selectedDates.length > 0) {
      const returnDate = new Date(selectedDates[0]);
      returnDate.setDate(returnDate.getDate() + 1);
      document.getElementById("returnDate").value = returnDate.toISOString().split("T")[0];
    }
  }
});


      document.getElementById("calendarModal").classList.remove("hidden");
      document.getElementById("calendarModal").classList.add("flex");
    });
}

function closeCalendar() {
  document.getElementById("calendarModal").classList.add("hidden");
  document.getElementById("calendarModal").classList.remove("flex");
}
</script>
<script>
const input = document.getElementById('searchInput');
const suggestionsList = document.getElementById('suggestionsList');
let currentFocus = -1;

input.addEventListener('input', () => {
  const query = input.value.trim();
  if (!query) {
    suggestionsList.innerHTML = '';
    suggestionsList.classList.add('hidden');
    return;
  }

  fetch(`search_suggestions.php?term=${encodeURIComponent(query)}`)
    .then(response => response.json())
    .then(data => {
      suggestionsList.innerHTML = '';
      currentFocus = -1;

      if (data.length === 0) {
        suggestionsList.classList.add('hidden');
        return;
      }

      data.forEach((item, index) => {
        const li = document.createElement('li');
        li.textContent = item;
        li.className = 'px-3 py-1 hover:bg-yellow-200 cursor-pointer';
        li.setAttribute('data-index', index);

li.onclick = () => {
  input.value = item;
  suggestionsList.innerHTML = '';
  suggestionsList.classList.add('hidden');
  setTimeout(() => {
    document.querySelector('form').submit();
  }, 100);
};


        suggestionsList.appendChild(li);
      });

      suggestionsList.classList.remove('hidden');
    });
});

input.addEventListener('keydown', function(e) {
  const items = suggestionsList.getElementsByTagName('li');

  if (e.key === 'ArrowDown') {
    e.preventDefault();
    currentFocus++;
    highlight(items);
  } else if (e.key === 'ArrowUp') {
    e.preventDefault();
    currentFocus--;
    highlight(items);
  } else if (e.key === 'Enter') {
    if (currentFocus > -1 && items[currentFocus]) {
      e.preventDefault(); // prevent native submit if selecting suggestion
      items[currentFocus].click(); // will trigger submit after fill
    } else {
      // ✅ Let it submit normally
      return true;
    }
  }
});



function highlight(items) {
  if (!items || items.length === 0) return;
  removeHighlight(items);

  if (currentFocus >= items.length) currentFocus = 0;
  if (currentFocus < 0) currentFocus = items.length - 1;

  items[currentFocus].classList.add('bg-yellow-200');
  items[currentFocus].scrollIntoView({ block: 'nearest' });
}

function removeHighlight(items) {
  Array.from(items).forEach(item => item.classList.remove('bg-yellow-200'));
}

document.addEventListener('click', function (e) {
  if (!suggestionsList.contains(e.target) && e.target !== input) {
    suggestionsList.classList.add('hidden');
    currentFocus = -1;
  }
});
</script>
<!-- Rental Modal -->
<div id="rentalModal" class="modal hidden fixed inset-0 bg-black bg-opacity-80 z-50 items-center justify-center">
  <div class="bg-white text-black p-6 rounded-xl w-full max-w-lg relative">
    <button onclick="closeRentalModal()" class="absolute top-2 right-4 text-black text-2xl font-bold">&times;</button>
    <h2 class="text-2xl font-bold mb-4">Add Rental</h2>
    <form method="POST" id="rentalForm">
      <input type="hidden" name="add_rental" value="1">
      <input type="hidden" name="dress_id" id="rentalDressId">

      <label class="font-bold">Customer Name</label>
      <input type="text" name="customer_name" required class="w-full mb-2 p-2 border rounded">

      <label class="font-bold">Customer Phone</label>
<input type="text" name="customer_phone" id="rentalPhone" required class="w-full mb-1 p-2 border rounded">
<div id="rentalPhoneError" class="error-msg hidden">Invalid phone number.</div>


      <label class="font-bold">Rental Date</label>
      <input type="text" name="rental_date" id="rentalDate" required class="w-full mb-2 p-2 border rounded">

      <label class="font-bold">Return Date (auto)</label>
      <input type="text" name="return_date" id="returnDate" readonly class="w-full mb-2 p-2 border rounded">

      <label class="font-bold">Down Payment</label>
      <input type="number" step="0.01" name="down_payment" required class="w-full mb-4 p-2 border rounded">

      <button type="submit" class="w-full bg-[#D4AF37] text-black font-semibold py-2 rounded hover:bg-yellow-600">Submit Rental</button>
    </form>
  </div>
</div>
<script>
function openRentalModal(dressId) {
  document.getElementById("rentalModal").classList.remove("hidden");
  document.getElementById("rentalModal").classList.add("flex");
  document.getElementById("rentalDressId").value = dressId;

  fetch(`get_calendar_dates.php?dress_id=${dressId}`)
    .then(res => res.json())
    .then(data => {
      const rentals = data.rentals;
      const trials = data.trials;
      const disableDates = [];

      rentals.forEach(date => {
        const d = new Date(date);
        [-1, 0, 1].forEach(offset => {
          const blocked = new Date(d);
          blocked.setDate(d.getDate() + offset);
          disableDates.push(blocked.toISOString().split("T")[0]);
        });
      });

      const trialDates = trials.map(t => t.date);

      // ✅ HERE is the correct place
      if (window.rentalPicker) {
        window.rentalPicker.destroy();
      }

      window.rentalPicker = flatpickr("#rentalDate", {
        minDate: "today",
        disable: disableDates,
        dateFormat: "Y-m-d",
        onDayCreate: (dObj, dStr, fp, dayElem) => {
          const iso = dayElem.dateObj.toISOString().split("T")[0];
          if (disableDates.includes(iso)) dayElem.classList.add("rental-day");
          if (trialDates.includes(iso)) {
            dayElem.classList.add("trial-day");
            dayElem.title = "Trial date";
          }
        },
        onChange: function(selectedDates) {
          if (selectedDates.length > 0) {
            const returnDate = new Date(selectedDates[0]);
            returnDate.setDate(returnDate.getDate() + 1);
            document.getElementById("returnDate").value = returnDate.toISOString().split("T")[0];
          }
        }
      });
    });
}


function closeRentalModal() {
  document.getElementById("rentalModal").classList.add("hidden");
  document.getElementById("rentalModal").classList.remove("flex");
  document.getElementById("rentalForm").reset();
  document.getElementById("returnDate").value = '';
}
</script>
<!-- Trial Modal -->
<div id="trialModal" class="modal hidden fixed inset-0 bg-black bg-opacity-80 z-50 items-center justify-center">
  <div class="bg-white text-black p-6 rounded-xl w-full max-w-lg relative">
    <button onclick="closeTrialModal()" class="absolute top-2 right-4 text-black text-2xl font-bold">&times;</button>
    <h2 class="text-2xl font-bold mb-4">Book Trial</h2>
    <form method="POST" id="trialForm">
      <input type="hidden" name="add_trial" value="1">
      <input type="hidden" name="dress_id" id="trialDressId">

      <label class="font-bold">Customer Name</label>
      <input type="text" name="customer_name" required class="w-full mb-2 p-2 border rounded">

      <label class="font-bold">Customer Phone</label>
<input type="text" name="customer_phone" id="trialPhone" required class="w-full mb-1 p-2 border rounded">
<div id="trialPhoneError" class="error-msg hidden">Invalid phone number.</div>

      <label class="font-bold">Trial Date</label>
      <input type="text" name="trial_date" id="trialDate" required class="w-full mb-2 p-2 border rounded">

      <label class="font-bold">Time Slot</label>
      <select name="trial_time" id="trialTime" required class="w-full mb-4 p-2 border rounded">
        <option value="">Select Time</option>
      </select>

      <button type="submit" class="w-full bg-[#D4AF37] text-black font-semibold py-2 rounded hover:bg-yellow-600">Submit Trial</button>
    </form>
  </div>
</div>
<script>
function openTrialModal(dressId) {
  document.getElementById("trialModal").classList.remove("hidden");
  document.getElementById("trialModal").classList.add("flex");
  document.getElementById("trialDressId").value = dressId;

  fetch(`get_calendar_dates.php?dress_id=${dressId}`)
    .then(res => res.json())
    .then(data => {
      const rentals = data.rentals;
      const trials = data.trials;
      const disableDates = [];

      rentals.forEach(date => {
        const d = new Date(date);
        [-1, 0, 1].forEach(offset => {
          const blocked = new Date(d);
          blocked.setDate(d.getDate() + offset);
          disableDates.push(blocked.toISOString().split("T")[0]);
        });
      });

      const trialDates = trials.map(t => t.date);

      if (window.trialPicker) {
        window.trialPicker.destroy();
      }

      window.trialPicker = flatpickr("#trialDate", {
        minDate: "today",
        disable: disableDates,
        dateFormat: "Y-m-d",
        onChange: function(selectedDates) {
          if (selectedDates.length > 0) {
            const selected = selectedDates[0].toISOString().split("T")[0];
            loadTrialTimes(dressId, selected, trials);
          }
        },
        onDayCreate: (dObj, dStr, fp, dayElem) => {
          const iso = dayElem.dateObj.toISOString().split("T")[0];
          if (disableDates.includes(iso)) dayElem.classList.add("rental-day");
          if (trialDates.includes(iso)) {
            dayElem.classList.add("trial-day");
            dayElem.title = "Trial booked";
          }
        }
      });
    });
}

function loadTrialTimes(dressId, selectedDate, allTrials) {
  const taken = allTrials.filter(t => t.date === selectedDate && t.dress_id == dressId).map(t => t.time);
  const allSlots = [];

  for (let h = 10; h < 19; h++) {
    allSlots.push(`${h.toString().padStart(2, '0')}:00`);
    allSlots.push(`${h.toString().padStart(2, '0')}:30`);
  }

  const select = document.getElementById("trialTime");
  select.innerHTML = '<option value="">Select Time</option>';

  allSlots.forEach(t => {
    if (!taken.includes(t)) {
      const option = document.createElement("option");
      option.value = t;
      option.textContent = t;
      select.appendChild(option);
    }
  });
}

function closeTrialModal() {
  document.getElementById("trialModal").classList.add("hidden");
  document.getElementById("trialModal").classList.remove("flex");
  document.getElementById("trialForm").reset();
  document.getElementById("trialTime").innerHTML = '<option value="">Select Time</option>';
}
</script>

<script>
function isValidLebanesePhone(phone) {
  return /^(03|71|76|78|79|81)\d{6}$/.test(phone);
}

// Rental Form Validation
document.getElementById("rentalForm").addEventListener("submit", function(e) {
  const phoneInput = document.getElementById("rentalPhone");
  const errorDiv = document.getElementById("rentalPhoneError");
  if (!isValidLebanesePhone(phoneInput.value.trim())) {
    e.preventDefault();
    phoneInput.classList.add("input-error");
    errorDiv.style.display = "block";
  } else {
    phoneInput.classList.remove("input-error");
    errorDiv.style.display = "none";
  }
});

// Trial Form Validation
document.getElementById("trialForm").addEventListener("submit", function(e) {
  const phoneInput = document.getElementById("trialPhone");
  const errorDiv = document.getElementById("trialPhoneError");
  if (!isValidLebanesePhone(phoneInput.value.trim())) {
    e.preventDefault();
    phoneInput.classList.add("input-error");
    errorDiv.style.display = "block";
  } else {
    phoneInput.classList.remove("input-error");
    errorDiv.style.display = "none";
  }
});
</script>
