<?php
$host = 'localhost';
$user = 'root';
$password = '';
$dbname = 'isabelle_fashion';

$conn = new mysqli($host, $user, $password, $dbname);
if ($conn->connect_error) {
  die("DB connection failed");
}

$dressId = (int)$_GET['dress_id'];

$rentals = [];
$trials = [];

// Get rental dates
$res = $conn->query("SELECT rental_date FROM rentals WHERE dress_id = $dressId");
while ($row = $res->fetch_assoc()) {
  $rentals[] = $row['rental_date'];
}

// Get trial dates
$res = $conn->query("SELECT trial_date, trial_time FROM trials WHERE dress_id = $dressId");
while ($row = $res->fetch_assoc()) {
  $trials[] = [
    'date' => $row['trial_date'],
    'time' => substr($row['trial_time'], 0, 5)
  ];
}

header('Content-Type: application/json');
echo json_encode([
  'rentals' => $rentals,
  'trials' => $trials
]);
