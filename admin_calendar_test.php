<?php
$host = 'localhost';
$user = 'root';
$password = '';
$dbname = 'isabelle_fashion';
$conn = new mysqli($host, $user, $password, $dbname);
if ($conn->connect_error) {
  die("Connection failed: " . $conn->connect_error);
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <title>Calendar Test</title>
  <script src="https://cdn.tailwindcss.com"></script>
  <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/flatpickr/dist/flatpickr.min.css">
  <script src="https://cdn.jsdelivr.net/npm/flatpickr"></script>
</head>
<body class="bg-black text-white px-6 py-10">

<h1 class="text-3xl mb-6 text-[#D4AF37] font-bold">Test: Per-Dress Calendar View</h1>

<?php
$dresses = $conn->query("SELECT * FROM dresses ORDER BY id DESC");
$calendarJS = "";

while ($dress = $dresses->fetch_assoc()) {
  $id = $dress['id'];

  // Rental dates with -1, 0, +1 logic
  $unavailable = [];
  $res = $conn->query("SELECT rental_date FROM rentals WHERE dress_id = $id");
  while ($r = $res->fetch_assoc()) {
    $d = $r['rental_date'];
    $unavailable[] = date('Y-m-d', strtotime("$d -1 day"));
    $unavailable[] = date('Y-m-d', strtotime($d));
    $unavailable[] = date('Y-m-d', strtotime("$d +1 day"));
  }

  // Trial dates
  $trialDates = [];
  $res2 = $conn->query("SELECT trial_date FROM trials WHERE dress_id = $id");
  while ($t = $res2->fetch_assoc()) {
    $trialDates[] = $t['trial_date'];
  }

  $calendarJS .= "initCalendar($id, " . json_encode($unavailable) . ", " . json_encode($trialDates) . ");\n";

  echo "
    <div class='bg-white text-black p-4 rounded mb-6'>
      <h2 class='text-xl font-bold mb-2'>{$dress['name']}</h2>
      <button onclick='toggleCalendar($id)' class='mb-3 text-blue-600 underline'>Show Calendar</button>
      <div id='calendar-box-$id' class='hidden'>
        <input type='text' id='calendar-$id' />
      </div>
    </div>
  ";
}
?>

<script>
function toggleCalendar(id) {
  const box = document.getElementById("calendar-box-" + id);
  box.classList.toggle("hidden");
}

function initCalendar(id, unavailable, trials) {
  flatpickr("#calendar-" + id, {
    inline: true,
    dateFormat: "Y-m-d",
    disable: unavailable,
    minDate: "today",
    onDayCreate: function(_, __, ___, dayElem) {
      const dateStr = dayElem.dateObj.toISOString().split("T")[0];

      if (unavailable.includes(dateStr)) {
        dayElem.style.backgroundColor = "#dc2626"; // red
        dayElem.style.color = "white";
        dayElem.title = "Unavailable (Rental)";
      }

      if (trials.includes(dateStr) && !unavailable.includes(dateStr)) {
        dayElem.style.backgroundColor = "#D4AF37"; // gold
        dayElem.style.color = "black";
        dayElem.title = "Trial Appointment";
      }

      
    }
  });
}

<?php echo $calendarJS; ?>
</script>

</body>
</html>
