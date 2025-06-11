<?php
// Step 1: Connect to MySQL
$host = 'localhost';
$user = 'root';
$password = ''; // default for WAMP is empty
$dbname = 'isabelle_fashion';

$conn = new mysqli($host, $user, $password, $dbname);
if ($conn->connect_error) {
  die("Connection failed: " . $conn->connect_error);
}

// Step 2: Get dress ID
$dressId = $_GET['dress_id'] ?? null;
if (!$dressId) {
  echo "<h1 style='color: red'>Invalid dress ID</h1>";
  exit;
}

// Step 3: Fetch the dress name (optional for display)
$dressName = '';
$stmt = $conn->prepare("SELECT name FROM dresses WHERE id = ?");
$stmt->bind_param("i", $dressId);
$stmt->execute();
$stmt->bind_result($dressName);
$stmt->fetch();
$stmt->close();

if (!$dressName) {
  echo "<h1 style='color: red'>Dress not found</h1>";
  exit;
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <title>Book a Trial for <?php echo htmlspecialchars($dressName); ?></title>
  <script src="https://cdn.tailwindcss.com"></script>
  <style>
    body {
      background-color: black;
    }
    .form-container {
      background: white;
      padding: 2rem;
      border-radius: 1rem;
      max-width: 600px;
      margin: 4rem auto;
    }
    label {
      font-weight: bold;
    }
  </style>
</head>
<body class="text-zinc-800">

<!-- Header -->
<nav class="flex justify-between items-center px-10 py-5 border-b border-zinc-100 bg-black text-[#D4AF37]">
  <div class="flex items-center gap-4">
    <img src="SA logo - 2.png" alt="Logo" class="h-14 w-auto">
    <img src="SA logo.png" alt="Isabelle" class="h-12 md:h-16 w-auto">
  </div>
  <div class="flex gap-8">
    <a href="index.html">Home</a>
    <a href="dresses.php" class="underline">Dresses</a>
    <a href="scarfs.html">Scarfs</a>
    <a href="heels.html">Heels</a>
  </div>
</nav>

<section class="form-container">
  <h2 class="text-2xl text-center mb-6 text-black">Book a Trial Appointment for <span class="text-[#D4AF37] font-semibold"><?php echo htmlspecialchars($dressName); ?></span></h2>
  <form action="submit_trial.php" method="POST" class="space-y-4">
    <input type="hidden" name="dress_id" value="<?php echo htmlspecialchars($dressId); ?>">

    <div>
      <label for="trial_date" class="block text-zinc-700 mb-1">Choose a Date:</label>
      <input type="date" name="trial_date" id="trial_date" required
             class="w-full px-4 py-2 border rounded bg-zinc-100 text-black">
    </div>

    <div class="text-center">
      <button type="submit"
              class="mt-4 px-6 py-2 bg-[#D4AF37] text-black rounded-full hover:bg-yellow-600 transition">
        Confirm Appointment
      </button>
    </div>
  </form>
</section>

<!-- Footer -->
<footer class="bg-black text-white py-6 mt-10">
  <div class="flex justify-center items-center gap-3">
    <img src="weboxa-logo.png" alt="Weboxa Logo" class="h-5 w-5" />
    <a href="https://weboxa.com" target="_blank" class="text-sm hover:text-[#D4AF37] transition-colors">
      Powered by Weboxa
    </a>
  </div>
</footer>

</body>
</html>
