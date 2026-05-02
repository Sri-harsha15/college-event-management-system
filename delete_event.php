<?php
session_start();
include 'connect.php';
// Ensure only admin can delete
if (!isset($_SESSION['email']) || $_SESSION['role'] !== 'admin') {
    header("Location: login.php");
    exit();
}
if (isset($_GET['id']) && is_numeric($_GET['id'])) {
    $event_id = $_GET['id'];
    // Delete event
    $stmt = $conn->prepare("DELETE FROM events WHERE event_id = ?");
    $stmt->bind_param("i", $event_id);
    if ($stmt->execute()) {
        header("Location: manage_events.php?message=deleted");
    } else {
        header("Location: manage_events.php?message=error");
    }
    $stmt->close();
} else {
    // Invalid ID
    header("Location: manage_events.php?message=invalid");
}
$conn->close();
?>