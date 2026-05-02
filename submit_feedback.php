<?php
session_start();
include 'connect.php';
if (!isset($_SESSION['email']) || $_SESSION['role'] !== 'student') {
    header("Location: login.php");
    exit();
}
$user_id = $_SESSION['user_id'];
// If form is submitted
if ($_SERVER["REQUEST_METHOD"] === "POST") {
    $event_id = intval($_POST['event_id']);
    $rating = intval($_POST['rating']);
    $comments = trim($_POST['comments']);
    // Validate rating
    if ($rating < 1 || $rating > 5) {
        $error = "Invalid rating value.";
    } else {
        $stmt = $conn->prepare("INSERT INTO feedback (event_id, user_id, rating, comments, submitted_at) VALUES (?, ?, ?, ?, NOW())");
        $stmt->bind_param("iiis", $event_id, $user_id, $rating, $comments);
        if ($stmt->execute()) {
            $success = "🎉 Feedback submitted successfully!";
        } else {
            $error = "Something went wrong while saving your feedback.";
        }
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <title>Submit Feedback</title>
  <link href="https://fonts.googleapis.com/css2?family=Roboto:wght@400;600&display=swap" rel="stylesheet">
  <style>
    body {
      font-family: 'Roboto', sans-serif;
      background: linear-gradient(to right, #f8ffae, #43c6ac);
      margin: 0;
      padding: 0;
    }
    .container {
      max-width: 600px;
      margin: 60px auto;
      background: #fff;
      padding: 30px;
      border-radius: 12px;
      box-shadow: 0 10px 25px rgba(0,0,0,0.1);
    }
    h2 {
      text-align: center;
      color: #2c3e50;
    }
    label {
      display: block;
      margin-top: 15px;
      font-weight: 600;
      color: #34495e;
    }
    select, textarea, input[type="submit"] {
      width: 100%;
      padding: 10px;
      margin-top: 8px;
      border-radius: 6px;
      border: 1px solid #ccc;
      font-size: 15px;
    }
    input[type="submit"] {
      background: linear-gradient(to right, #36d1dc, #5b86e5);
      color: white;
      font-weight: bold;
      cursor: pointer;
      margin-top: 20px;
      border: none;
    }
    input[type="submit"]:hover {
      background: linear-gradient(to right, #5b86e5, #36d1dc);
    }
    .message {
      padding: 10px;
      margin: 15px 0;
      border-radius: 6px;
      text-align: center;
    }
    .success {
      background-color: #d4edda;
      color: #155724;
      border: 1px solid #c3e6cb;
    }
    .error {
      background-color: #f8d7da;
      color: #721c24;
      border: 1px solid #f5c6cb;
    }
  </style>
</head>
<body>
  <div class="container">
    <h2>📝 Submit Feedback</h2>
    <?php if (!empty($success)) echo "<div class='message success'>$success</div>"; ?>
    <?php if (!empty($error)) echo "<div class='message error'>$error</div>"; ?>
    <form method="POST" action="submit_feedback.php">
      <!-- Replace this value with dynamic event ID -->
      <input type="hidden" name="event_id" value="<?php echo isset($_GET['event_id']) ? intval($_GET['event_id']) : 1; ?>">
      <label for="rating">Rating (1 to 5):</label>
      <select name="rating" id="rating" required>
        <option value="">-- Select Rating --</option>
        <option value="1">1 - Very Bad</option>
        <option value="2">2 - Bad</option>
        <option value="3">3 - Average</option>
        <option value="4">4 - Good</option>
        <option value="5">5 - Excellent</option>
      </select>
      <label for="comments">Comments:</label>
      <textarea name="comments" id="comments" rows="5" placeholder="Write your feedback..." required></textarea>
      <input type="submit" value="Submit Feedback">
    </form>
  </div>
</body>
</html>
