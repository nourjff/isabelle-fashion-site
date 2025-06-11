<?php
$host = 'localhost';
$user = 'root';
$password = '';
$dbname = 'isabelle_fashion';

$conn = new mysqli($host, $user, $password, $dbname);
if ($conn->connect_error) {
  die("Connection failed: " . $conn->connect_error);
}

$dressId = (int)$_GET['dress_id'];
$trialDate = $_GET['trial_date'];

$start = strtotime('10:00');
$end = strtotime('19:00');
$booked = [];

$stmt = $conn->prepare("SELECT trial_time FROM trials WHERE dress_id = ? AND trial_date = ?");
$stmt->bind_param("is", $dressId, $trialDate);
$stmt->execute();
$result = $stmt->get_result();
while ($row = $result->fetch_assoc()) {
  $booked[] = $row['trial_time'];
}

$times = [];
for ($t = $start; $t < $end; $t += 1800) {
  $time = date("H:i:s", $t);
  $times[] = [
    'value' => $time,
    'available' => !in_array($time, $booked)
  ];
}

header('Content-Type: application/json');
echo json_encode($times);
