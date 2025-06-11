<?php
$host = 'localhost';
$user = 'root';
$password = '';
$dbname = 'isabelle_fashion';

$conn = new mysqli($host, $user, $password, $dbname);
if ($conn->connect_error) {
  die("Connection failed: " . $conn->connect_error);
}

$term = $_GET['term'] ?? '';
$term = "%$term%";

$stmt = $conn->prepare("SELECT name FROM dresses WHERE name LIKE ? ORDER BY name LIMIT 10");
$stmt->bind_param("s", $term);
$stmt->execute();
$result = $stmt->get_result();

$suggestions = [];
while ($row = $result->fetch_assoc()) {
  $suggestions[] = $row['name'];
}

echo json_encode($suggestions);
?>
