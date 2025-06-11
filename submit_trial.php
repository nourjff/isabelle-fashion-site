<?php
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

require 'phpmailer/src/Exception.php';
require 'phpmailer/src/PHPMailer.php';
require 'phpmailer/src/SMTP.php';

$host = 'localhost';
$user = 'root';
$password = '';
$dbname = 'isabelle_fashion';

$conn = new mysqli($host, $user, $password, $dbname);
if ($conn->connect_error) {
  die("Connection failed: " . $conn->connect_error);
}

$dress_id = $_POST['dress_id'] ?? null;
$customer_name = trim($_POST['customer_name'] ?? '');
$customer_mobile = trim($_POST['customer_mobile'] ?? '');
$trial_date = $_POST['trial_date'] ?? null;
$trial_time = $_POST['trial_time'] ?? null;

if (!$dress_id || !$customer_name || !$customer_mobile || !$trial_date || !$trial_time) {
  header("Location: dresses.php?success=0");
  exit;
}

// Insert booking in database
$stmt = $conn->prepare("INSERT INTO trials (dress_id, trial_date, trial_time, user_name, phone, created_at) VALUES (?, ?, ?, ?, ?, NOW())");
$stmt->bind_param("issss", $dress_id, $trial_date, $trial_time, $customer_name, $customer_mobile);
$stmt->execute();

// Get dress name from DB
$dress_name = '';
$dressQuery = $conn->prepare("SELECT name FROM dresses WHERE id = ?");
$dressQuery->bind_param("i", $dress_id);
$dressQuery->execute();
$dressQuery->bind_result($dress_name);
$dressQuery->fetch();
$dressQuery->close();

// Send email notification
$mail = new PHPMailer(true);

try {
    // Server settings
    $mail->isSMTP();
    $mail->Host = 'smtp.gmail.com';
    $mail->SMTPAuth = true;
    $mail->Username = 'isabelledressesas@gmail.com';
    $mail->Password = 'dvet gasg aaqi qpeo'; // 🔐 Replace with your app password
    $mail->SMTPSecure = 'tls';
    $mail->Port = 587;

    // Recipients
    $mail->setFrom('isabelledressesas@gmail.com', 'Isabelle Dresses');
    $mail->addAddress('isabelledressesas@gmail.com'); // send to yourself

    // Content
    $mail->isHTML(true);
    $mail->Subject = 'New Trial Booking';
    $mail->Body = "
        <h3>New Trial Appointment</h3>
        <p><strong>Customer Name:</strong> {$customer_name}</p>
        <p><strong>Mobile:</strong> {$customer_mobile}</p>
        <p><strong>Trial Date:</strong> {$trial_date}</p>
        <p><strong>Time:</strong> {$trial_time}</p>
        <p><strong>Dress:</strong> {$dress_name}</p>
    ";

    $mail->send();
} catch (Exception $e) {
    error_log("Email could not be sent. Mailer Error: {$mail->ErrorInfo}");
}

// Redirect back with success message
header("Location: dresses.php?success=1&name=" . urlencode($customer_name) . "&date=" . urlencode($trial_date) . "&time=" . urlencode($trial_time));
exit;
?>
