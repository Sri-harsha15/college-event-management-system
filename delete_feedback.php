<?php
session_start();
include 'connect.php';
if (!isset($_SESSION['email']) || $_SESSION['role'] !== 'student') {
    header("Location: login.php");
    exit();
}
if (isset($_GET['id']) && is_numeric($_GET['id'])) {
    $feedback_id = intval($_GET['id']);
    $user_id = $_SESSION['user_id'];
    // Check if the feedback belongs to the current student
    $check = $conn->prepare("SELECT * FROM feedback WHERE feedback_id = ? AND user_id = ?");
    $check->bind_param("ii", $feedback_id, $user_id);
    $check->execute();
    $result = $check->get_result();
    if ($result && $result->num_rows > 0) {
        $delete = $conn->prepare("DELETE FROM feedback WHERE feedback_id = ?");
        $delete->bind_param("i", $feedback_id);
        if ($delete->execute()) {
            $_SESSION['toast'] = "✅ Feedback deleted successfully!";
        } else {
            $_SESSION['toast'] = "❌ Failed to delete feedback.";
        }
    } else {
        $_SESSION['toast'] = "⚠️ Feedback not found or unauthorized.";
    }
}
header("Location: my_feedback.php");
exit();
