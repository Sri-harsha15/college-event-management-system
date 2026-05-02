<?php
session_start();
include 'connect.php';
if (!isset($_SESSION['email']) || $_SESSION['role'] !== 'student') {
    header("Location: login.php");
    exit();
}
if (!isset($_GET['id']) || !is_numeric($_GET['id'])) {
    echo "<script>alert('Invalid event ID'); window.location.href='student_dashboard.php';</script>";
    exit();
}
$event_id = intval($_GET['id']);
$user_id = $_SESSION['user_id'];
// Check if already registered
$check = $conn->prepare("SELECT * FROM registrations WHERE user_id = ? AND event_id = ?");
$check->bind_param("ii", $user_id, $event_id);
$check->execute();
$result = $check->get_result();
if ($result->num_rows > 0) {
    echo "<script>alert('Already registered for this event!'); window.location.href='student_dashboard.php';</script>";
    exit();
}
// Insert registration
$stmt = $conn->prepare("INSERT INTO registrations (user_id, event_id, registration_time) VALUES (?, ?, NOW())");
$stmt->bind_param("ii", $user_id, $event_id);
if ($stmt->execute()) {
    $last_id = $stmt->insert_id;
    header("Location: registration_confirmation.php?id=$last_id");
    exit();
} else {
    echo "<script>alert('Registration failed. Try again.'); window.location.href='student_dashboard.php';</script>";
}
?>
