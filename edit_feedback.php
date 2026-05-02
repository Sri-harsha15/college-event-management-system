<?php
session_start();
include 'connect.php';
if (!isset($_SESSION['email']) || $_SESSION['role'] !== 'student') {
    header("Location: login.php");
    exit();
}
$user_id = $_SESSION['user_id'];
if (!isset($_GET['id'])) {
    die("Invalid feedback ID.");
}
$feedback_id = intval($_GET['id']);
$message = "";
// Fetch feedback
$stmt = $conn->prepare("SELECT * FROM feedback WHERE feedback_id = ? AND user_id = ?");
$stmt->bind_param("ii", $feedback_id, $user_id);
$stmt->execute();
$result = $stmt->get_result();
if ($result->num_rows === 0) {
    die("Feedback not found.");
}
$feedback = $result->fetch_assoc();
$submittedDate = date('Y-m-d', strtotime($feedback['submitted_at']));
$today = date('Y-m-d');
if ($submittedDate !== $today) {
    die("🔒 You can only edit feedback on the day it was submitted.");
}
// Update feedback
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $rating = intval($_POST['rating']);
    $comment = trim($_POST['comment']);
    $update = $conn->prepare("UPDATE feedback SET rating = ?, comments = ? WHERE feedback_id = ? AND user_id = ?");
    $update->bind_param("isii", $rating, $comment, $feedback_id, $user_id);
    if ($update->execute()) {
        $message = "✅ Feedback updated successfully!";
        // Optional redirect:
        // header("Location: my_feedback.php");
    } else {
        $message = "❌ Failed to update feedback.";
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <title>Edit Feedback</title>
  <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;600&display=swap" rel="stylesheet">
  <style>
    body {
      font-family: 'Poppins', sans-serif;
      background: linear-gradient(to right, #f7f8fc, #e0eafc);
      padding: 40px;
    }
    .container {
      max-width: 600px;
      margin: auto;
      background: white;
      padding: 30px;
      border-radius: 12px;
      box-shadow: 0 8px 20px rgba(0,0,0,0.1);
    }
    h2 {
      text-align: center;
      color: #2c3e50;
    }
    .message {
      text-align: center;
      color: green;
      font-weight: bold;
      margin-bottom: 15px;
    }
    label {
      display: block;
      margin-top: 20px;
      font-weight: bold;
    }
    select, textarea {
      width: 100%;
      padding: 12px;
      margin-top: 6px;
      border-radius: 8px;
      border: 1px solid #ccc;
      font-size: 15px;
    }
    button {
      margin-top: 20px;
      padding: 10px 20px;
      background-color: #3498db;
      border: none;
      color: white;
      font-weight: bold;
      border-radius: 8px;
      cursor: pointer;
    }
    button:hover {
      background-color: #2980b9;
    }
    .back-btn {
      display: block;
      margin-top: 20px;
      text-align: center;
      text-decoration: none;
      color: #333;
      font-weight: bold;
    }
  </style>
</head>
<body>
<div class="container">
  <h2>✏️ Edit Feedback</h2>
  <?php if (!empty($message)): ?>
    <div class="message"><?= htmlspecialchars($message) ?></div>
  <?php endif; ?>
  <form method="POST">
    <label for="rating">Rating</label>
    <select name="rating" id="rating" required>
      <option value="1" <?= $feedback['rating'] == 1 ? 'selected' : '' ?>>⭐</option>
      <option value="2" <?= $feedback['rating'] == 2 ? 'selected' : '' ?>>⭐⭐</option>
      <option value="3" <?= $feedback['rating'] == 3 ? 'selected' : '' ?>>⭐⭐⭐</option>
      <option value="4" <?= $feedback['rating'] == 4 ? 'selected' : '' ?>>⭐⭐⭐⭐</option>
      <option value="5" <?= $feedback['rating'] == 5 ? 'selected' : '' ?>>⭐⭐⭐⭐⭐</option>
    </select>
    <label for="comment">Comment</label>
    <textarea name="comment" id="comment" rows="5" required><?= htmlspecialchars($feedback['comments']) ?></textarea>
    <button type="submit">Update Feedback</button>
  </form>
  <a href="my_feedback.php" class="back-btn">⬅ Back to My Feedback</a>
</div>
</body>
</html>
