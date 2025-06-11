<?php
$host = 'localhost';
$user = 'root';
$password = '';
$dbname = 'isabelle_fashion';

$conn = new mysqli($host, $user, $password, $dbname);
if ($conn->connect_error) {
  die("Connection failed: " . $conn->connect_error);
}

$filter = $_GET['filter'] ?? 'all';
$color = $_GET['color'] ?? '';

$conditions = [];

if ($filter === 'engagement') {
  $conditions[] = "name LIKE '%engagement%'";
} elseif ($filter === 'evening') {
  $conditions[] = "name NOT LIKE '%engagement%'";
}

if (!empty($color)) {
  $conditions[] = "color = '" . $conn->real_escape_string($color) . "'";
}

$sql = "SELECT * FROM dresses";
if (!empty($conditions)) {
  $sql .= " WHERE " . implode(" AND ", $conditions);
}

$result = $conn->query($sql);
?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <title>Dresses | Isabelle Dresses</title>
  <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/flatpickr/dist/flatpickr.min.css">
  <script src="https://cdn.tailwindcss.com"></script>
  <script src="https://cdn.jsdelivr.net/npm/flatpickr"></script>
  <script src="https://cdn.jsdelivr.net/npm/flatpickr/dist/plugins/rangePlugin.js"></script>
<style>
  .flatpickr-day.rental-day {
    background: #dc2626 !important;  /* red */
    color: white !important;
  }
  .flatpickr-day.trial-day {
    background: #facc15 !important; /* gold */
    color: black !important;
  }
</style>

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
</head>
<body class="text-white">

<nav class="flex justify-between items-center px-10 py-8 bg-black text-[#D4AF37]">
  <div class="flex items-center gap-4">
    <img src="SA logo - 2.png" alt="Logo" class="h-20 w-auto">
    <img src="SA logo.png" alt="Isabelle" class="h-24 md:h-16 w-auto">
  </div>
  <div class="flex gap-10 text-xl font-medium">
    <a href="index.html">Home</a>
    <a href="dresses.php" class="underline">Dresses</a>
    <a href="heels.php">Heels</a>
  </div>
</nav>

<section class="pt-6 pb-8 px-6">
  <div class="w-full flex flex-col lg:flex-row gap-8 items-start">


    <!-- Sidebar -->
<!-- 📱 Filter Toggle Button (Mobile Only) -->
<div class="md:hidden flex justify-end mb-2">
  <button onclick="toggleFilters()" class="bg-[#D4AF37] text-black text-sm px-4 py-2 rounded shadow">
    ☰ Filter
  </button>
</div>



<!-- 🧾 Filters Panel (hidden on mobile, visible on md+) -->
<!-- 📱 Filters Panel -->
<!-- 🧾 Filters Panel -->
<aside id="filterPanel" class="hidden md:block md:w-56 w-full transition-all duration-300 ease-in-out">



  <div class="space-y-2 mb-6">
    <?php
    $active = $_GET['filter'] ?? 'all';
    $filters = ['all' => 'All', 'engagement' => 'Engagement Dresses', 'evening' => 'Evening Dresses'];
    foreach ($filters as $key => $label) {
      $isActive = ($active === $key);
      echo '<a href="dresses.php?filter=' . $key . '" class="block w-full text-left px-4 py-3 rounded text-lg font-medium transition-all ' . 
           ($isActive ? 'bg-[#D4AF37] text-black' : 'text-white hover:bg-[#D4AF37] hover:text-black') . '">' . 
           $label . '</a>';
    }
    ?>
  </div>

  <!-- Color Filter -->
  <div class="space-y-2">
    <p class="text-white font-semibold mb-1">Filter by Color:</p>
    <?php
    $colorOptions = ['pink', 'black', 'white', 'gold'];
    foreach ($colorOptions as $c) {
      $isSelected = ($color === $c) ? 'bg-[#D4AF37] text-black' : 'text-white hover:bg-[#D4AF37] hover:text-black';
      echo '<a href="dresses.php?filter=' . $filter . '&color=' . $c . '" class="block w-full text-left px-4 py-2 rounded text-base font-medium transition-all ' . $isSelected . '">' . ucfirst($c) . '</a>';
    }
    ?>
  </div>
</aside>



    <!-- Dresses Grid -->
    <div class="w-full lg:w-4/5">
      <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-6">
        <?php
        while ($row = $result->fetch_assoc()) {
          $dressId = $row['id'];
          // Fetch scarfs for this dress
$scarfsResult = $conn->query("SELECT s.* FROM scarfs s JOIN dress_scarfs ds ON s.id = ds.scarf_id WHERE ds.dress_id = $dressId");
$scarfs = [];
while ($scarf = $scarfsResult->fetch_assoc()) {
  $scarfs[] = [
    'name' => $scarf['name'],
    'image_url' => $scarf['image_url']
  ];

  
}
$scarfsDataJSON = htmlspecialchars(json_encode($scarfs), ENT_QUOTES, 'UTF-8');

          echo '<div class="bg-black border border-[#D4AF37] rounded-xl overflow-hidden">';
          echo '<img src="' . htmlspecialchars($row['image_url']) . '" class="w-full h-[340px] object-cover">';
          echo '<div class="p-4 text-center">';
          echo '<h3 class="text-lg font-semibold mb-1">' . htmlspecialchars($row['name']) . '</h3>';
          echo '<p class="text-sm">$' . number_format($row['price'], 2) . '</p>';
          echo '<p class="text-sm text-gray-300">Size: ' . htmlspecialchars($row['size']) . '</p>';
          echo '</div>';

          echo '<div class="px-4 pb-2">';
          echo '<button onclick="openTrialModal(' . $dressId . ')" class="w-full bg-[#D4AF37] text-black py-2 rounded hover:opacity-90">Book a Trial</button>';

          echo '</div>';

          echo '<div id="form_' . $dressId . '" class="p-4 hidden">';
          echo '<form action="submit_trial.php" method="POST" class="text-sm space-y-2">';
          echo '<input type="hidden" name="dress_id" value="' . $dressId . '">';
          echo '<input type="text" name="customer_name" placeholder="Your Name" required class="w-full p-2 rounded bg-black border border-gray-600 placeholder-white">';
          echo '<input type="text" name="customer_mobile" placeholder="Mobile Number" required class="w-full p-2 rounded bg-black border border-gray-600 placeholder-white">';
          echo '<input type="text" name="trial_date" id="trial_date_' . $dressId . '" placeholder="Select Trial Date" required class="w-full p-2 rounded bg-black border border-gray-600 placeholder-white">';
          echo '<input type="time" name="trial_time" required class="w-full p-2 rounded bg-black border border-gray-600 placeholder-white">';
          echo '<button type="submit" class="w-full mt-2 bg-[#D4AF37] text-black py-2 rounded hover:opacity-90">Submit</button>';
          echo '</form>';
          echo '</div>';

          $scarfsSql = "SELECT s.* FROM scarfs s JOIN dress_scarfs ds ON s.id = ds.scarf_id WHERE ds.dress_id = $dressId";
          $scarfsResult = $conn->query($scarfsSql);
echo '<div class="p-4">';
echo '<button onclick=\'handleScarfClick(' . $scarfsDataJSON . ')\' class="bg-[#D4AF37] text-black px-4 py-2 rounded hover:opacity-90">Show Available Scarfs</button>';
echo '</div>';




          echo '<script>
            flatpickr("#trial_date_' . $dressId . '", { minDate: "today" });
            function toggleForm(id) {
              const form = document.getElementById("form_" + id);
              form.classList.toggle("hidden");
            }
          </script>';

          echo '</div>';
        }
        ?>
      </div>
    </div>
  </div>
</section>

<script>
  function toggleScarfs(id) {
    const scarfDiv = document.getElementById('scarfs_' + id);
    scarfDiv.classList.toggle('hidden');
  }
</script>

<div class="w-full bg-[#D4AF37] text-black text-center py-3 text-base font-semibold animate-pulse">
  Scarfs are available with some dresses for an additional fee of $3.
</div>

<!-- Trial Booking Modal -->
<div id="trialModal" class="fixed inset-0 bg-black bg-opacity-80 flex items-center justify-center hidden z-50">
  <div class="bg-black border border-[#D4AF37] p-6 rounded-lg w-full max-w-md relative">
    <button onclick="closeTrialModal()" class="absolute top-2 right-4 text-2xl text-[#D4AF37] hover:text-white">&times;</button>
    <form id="trialForm" method="POST" action="submit_trial.php" class="space-y-3">
      <input type="hidden" name="dress_id" id="modal_dress_id">
      <input type="text" name="customer_name" placeholder="Your Name" required class="w-full p-2 rounded bg-black border border-gray-600 placeholder-white">
      <input type="text" name="customer_mobile" placeholder="Mobile Number" required class="w-full p-2 rounded bg-black border border-gray-600 placeholder-white">
      <input type="text" name="trial_date" id="modal_trial_date" placeholder="Select Trial Date" required class="w-full p-2 rounded bg-black border border-gray-600 placeholder-white">
      <select name="trial_time" id="modal_trial_time" required class="w-full p-2 rounded bg-black border border-gray-600 text-white">
  <option value="">Select Time</option>
</select>

      <button type="submit" class="w-full bg-[#D4AF37] text-black py-2 rounded hover:opacity-90">Submit</button>
    </form>
  </div>
</div>

<script>
function openTrialModal(dressId) {
  document.getElementById('trialModal').classList.remove('hidden');
  document.getElementById('modal_dress_id').value = dressId;

  // Apply date picker dynamically
  flatpickr("#modal_trial_date", {
    minDate: "today"
  });
  flatpickr("#modal_trial_date", {
  minDate: "today",
  onChange: function(selectedDates, dateStr, instance) {
    const trialDate = dateStr;
    const dressId = document.getElementById("modal_dress_id").value;

    fetch(`get_available_times.php?dress_id=${dressId}&trial_date=${trialDate}`)
  .then(response => response.json())
  .then(times => {
    const timeDropdown = document.getElementById("modal_trial_time");
    timeDropdown.innerHTML = '<option value="">Select Time</option>';

    times.forEach(slot => {
      const option = document.createElement('option');
      const readable = new Date('1970-01-01T' + slot.value).toLocaleTimeString([], { hour: '2-digit', minute: '2-digit' });
      option.text = readable;
      option.value = slot.value;

      if (!slot.available) {
        option.disabled = true;
        option.title = "This dress is not available at this time";
        option.className = "text-gray-400 cursor-not-allowed";
      }

      timeDropdown.appendChild(option);
    });
  });

  }
});

  
}

function closeTrialModal() {
  document.getElementById('trialModal').classList.add('hidden');
}
</script>
  <!-- Scarfs Modal -->
<div id="scarfsModal" class="fixed inset-0 bg-black bg-opacity-80 z-50 hidden items-center justify-center">
  <div class="bg-black border border-[#D4AF37] rounded-xl p-6 w-full max-w-md text-white relative">
    <button onclick="closeScarfsModal()" class="absolute top-2 right-4 text-2xl text-[#D4AF37] hover:text-white">&times;</button>
    <h2 class="text-2xl font-bold mb-4 text-[#D4AF37]" id="scarfName"></h2>
    <img id="scarfImage" src="" alt="Scarf Image" class="w-full h-64 object-cover rounded border border-[#D4AF37]">
    
    <div class="flex justify-between mt-4">
      <button onclick="prevScarf()" class="bg-[#D4AF37] text-black px-4 py-2 rounded">Prev</button>
      <button onclick="nextScarf()" class="bg-[#D4AF37] text-black px-4 py-2 rounded">Next</button>
    </div>
  </div>
</div>
<div id="noScarfModal" class="fixed inset-0 bg-black bg-opacity-80 z-50 hidden items-center justify-center">
  <div class="bg-black border border-[#D4AF37] rounded-xl p-6 w-full max-w-sm text-white text-center relative">
    <button onclick="closeNoScarfModal()" class="absolute top-2 right-4 text-2xl text-[#D4AF37] hover:text-white">&times;</button>
    <h2 class="text-xl font-bold text-[#D4AF37] mb-4">Sorry , No available scarf for this dress!</h2>
    <button onclick="closeNoScarfModal()" class="bg-[#D4AF37] text-black px-4 py-2 rounded hover:bg-yellow-600 transition">OK</button>
  </div>
</div>

<script>
let scarfs = [];
let currentScarf = 0;

function handleScarfClick(data) {
  scarfs = data;

if (!scarfs.length) {
  showNoScarfModal();
  return;
}


  currentScarf = 0;
  updateScarfModal();
  document.getElementById("scarfsModal").classList.remove("hidden");
  document.getElementById("scarfsModal").classList.add("flex");
}

function updateScarfModal() {
  if (!scarfs.length) return;

  document.getElementById("scarfName").innerText = scarfs[currentScarf].name;
  document.getElementById("scarfImage").src = scarfs[currentScarf].image_url;

  const prevBtn = document.querySelector("#scarfsModal button[onclick='prevScarf()']");
  const nextBtn = document.querySelector("#scarfsModal button[onclick='nextScarf()']");
  
  if (scarfs.length > 1) {
    prevBtn.style.display = "inline-block";
    nextBtn.style.display = "inline-block";
  } else {
    prevBtn.style.display = "none";
    nextBtn.style.display = "none";
  }
}

function nextScarf() {
  if (scarfs.length > 0) {
    currentScarf = (currentScarf + 1) % scarfs.length;
    updateScarfModal();
  }
}

function prevScarf() {
  if (scarfs.length > 0) {
    currentScarf = (currentScarf - 1 + scarfs.length) % scarfs.length;
    updateScarfModal();
  }
}

function closeScarfsModal() {
  document.getElementById("scarfsModal").classList.add("hidden");
  document.getElementById("scarfsModal").classList.remove("flex");
}


function showNoScarfModal() {
  document.getElementById("noScarfModal").classList.remove("hidden");
  document.getElementById("noScarfModal").classList.add("flex");
}

function closeNoScarfModal() {
  document.getElementById("noScarfModal").classList.add("hidden");
  document.getElementById("noScarfModal").classList.remove("flex");
}

</script>


<script>
<script>
  function toggleFilters() {
    const panel = document.getElementById('filterPanel');
    panel.classList.toggle('hidden');
  }
</script>

</script>





  <!-- WhatsApp Button -->
  <a href="https://wa.me/81971871" target="_blank" class="fixed bottom-5 right-5 z-50">
    <div class="bg-[#D4AF37] rounded-full p-4 shadow-lg hover:scale-110 transition">
      <img src="icons/whatsapp.svg" alt="WhatsApp" class="h-12 w-12">
    </div>
  </a>
<?php if (isset($_GET['success']) && $_GET['success'] == 1): ?>
<script>
  window.addEventListener('DOMContentLoaded', () => {
    const name = decodeURIComponent("<?= $_GET['name'] ?>");
    const date = decodeURIComponent("<?= $_GET['date'] ?>");
    const time = decodeURIComponent("<?= $_GET['time'] ?>");

    const popup = document.createElement('div');
    popup.className = "fixed inset-0 bg-black bg-opacity-50 flex items-center justify-center z-[9999]";
    
    popup.innerHTML = `
      <div class="bg-[#D4AF37] text-black px-8 py-6 rounded-xl text-center shadow-2xl border-3 border-black animate-fadeInUp max-w-md w-full">
        <h2 class="text-2xl font-extrabold mb-2"> Trial Booked Successfully!</h2>
        <p class="text-lg mb-1"><strong>Name:</strong> ${name}</p>
        <p class="text-lg mb-1"><strong>Date:</strong> ${date}</p>
        <p class="text-lg mb-3"><strong>Time:</strong> ${new Date('1970-01-01T' + time).toLocaleTimeString([], { hour: '2-digit', minute: '2-digit' })}</p>
        <button onclick="this.closest('.fixed').remove()" class="bg-black text-[#D4AF37] px-4 py-2 rounded hover:bg-gray-900 transition">OK</button>
      </div>
    `;

    document.body.appendChild(popup);
  });
</script>
<style>
@keyframes fadeInUp {
  0% { transform: translateY(40px); opacity: 0; }
  100% { transform: translateY(0); opacity: 1; }
}
.animate-fadeInUp {
  animation: fadeInUp 0.5s ease-out;
}
</style>
<?php endif; ?>


<!-- <footer class="bg-black text-white py-6 mt-10">
  <div class="flex justify-center items-center gap-3">
    <img src="weboxa.png" alt="Weboxa Logo" class="h-10 w-10" />
    <a href="https://weboxa.com" target="_blank" class="text-sm hover:text-[#D4AF37] transition-colors">Powered by Weboxa</a>
  </div>
</footer> -->

</body>
</html>
