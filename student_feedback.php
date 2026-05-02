<?php
session_start();
include 'connect.php';
if (!isset($_SESSION['email']) || $_SESSION['role'] !== 'student') {
    header("Location: login.php");
    exit();
}
$user_id = $_SESSION['user_id'];
$message = "";
// Handle feedback submission
if ($_SERVER["REQUEST_METHOD"] === "POST" && isset($_POST['event_id'])) {
    $event_id = $_POST['event_id'];
    $rating = $_POST['rating'];
    $comments = trim($_POST['comments']);
    // Prevent duplicate feedback
    $check = $conn->prepare("SELECT * FROM feedback WHERE event_id = ? AND user_id = ?");
    $check->bind_param("ii", $event_id, $user_id);
    $check->execute();
    $check->store_result();
    if ($check->num_rows > 0) {
        $message = "⚠️ Feedback already submitted.";
    } else {
        $stmt = $conn->prepare("INSERT INTO feedback (event_id, user_id, rating, comments, submitted_at) VALUES (?, ?, ?, ?, NOW())");
        $stmt->bind_param("iiis", $event_id, $user_id, $rating, $comments);
        if ($stmt->execute()) {
            $message = "✅ Feedback submitted!";
        } else {
            $message = "❌ Submission failed.";
        }
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <title>Event Feedback</title>
  <link href="https://fonts.googleapis.com/css2?family=Rubik:wght@400;600&display=swap" rel="stylesheet">
  <style>
    body {
      font-family: 'Rubik', sans-serif;
         background: linear-gradient(rgba(255, 255, 255, 0.4), rgba(255, 255, 255, 0.6)),
                url('https://i.postimg.cc/C1s63tdg/9b8e5cc5-0db7-4b11-81f0-adef8c67fe65.png') no-repeat center center fixed;
    background-size: cover;
      padding: 40px;
      margin: 0;
    }
    h2 {
      text-align: center;
      color: #2c3e50;
      margin-bottom: 30px;
    }
    .message {
      text-align: center;
      color: green;
      font-weight: bold;
      margin-bottom: 20px;
    }
    .feedback-card {
      background: #fff;
      border-radius: 12px;
      padding: 20px;
      margin-bottom: 20px;
      box-shadow: 0 6px 15px rgba(0,0,0,0.1);
    }
    .feedback-card h3 {
      margin: 0 0 10px;
      color: #2980b9;
    }
    .feedback-card p {
      margin: 5px 0;
      color: #555;
    }
    textarea {
      width: 100%;
      height: 80px;
      margin-top: 10px;
      padding: 10px;
      border-radius: 6px;
      border: 1px solid #ccc;
      resize: none;
    }
    .star-rating {
      direction: rtl;
      display: inline-flex;
      font-size: 24px;
      margin-top: 10px;
    }
    .star-rating input {
      display: none;
    }
    .star-rating label {
      color: #ccc;
      cursor: pointer;
    }
    .star-rating input:checked ~ label,
    .star-rating label:hover,
    .star-rating label:hover ~ label {
      color: #f39c12;
    }
    .submit-btn {
      margin-top: 10px;
      background: #27ae60;
      color: white;
      padding: 10px 18px;
      border: none;
      border-radius: 6px;
      font-weight: bold;
      cursor: pointer;
    }
    .submit-btn:hover {
      background: #219150;
    }
    .back-btn {
      display: block;
      text-align: center;
      margin-top: 25px;
      background-color: #e67e22;
      color: white;
      text-decoration: none;
      padding: 10px 20px;
      border-radius: 8px;
      width: fit-content;
      margin-left: auto;
      margin-right: auto;
    }
  </style>
</head>
<body>
<h2>🌟 Submit Feedback for Attended Events</h2>
<?php if (!empty($message)): ?>
  <div class="message"><?= htmlspecialchars($message) ?></div>
<?php endif; ?>
<?php
$query = "
  SELECT e.event_id, e.title, e.event_date
  FROM registrations r
  JOIN events e ON r.event_id = e.event_id
  WHERE r.user_id = $user_id
    AND e.event_date < CURDATE()
    AND NOT EXISTS (
        SELECT 1 FROM feedback f WHERE f.event_id = e.event_id AND f.user_id = $user_id
    )
  ORDER BY e.event_date DESC
";
$result = $conn->query($query);
if ($result && $result->num_rows > 0) {
    while ($row = $result->fetch_assoc()) {
        echo "<div class='feedback-card'>
                <h3>" . htmlspecialchars($row['title']) . "</h3>
                <p><strong>Date:</strong> " . date('M d, Y', strtotime($row['event_date'])) . "</p>
                <form method='POST' action=''>
                  <input type='hidden' name='event_id' value='" . $row['event_id'] . "'>

                  <div class='star-rating'>
                    <input type='radio' name='rating' id='star5-{$row['event_id']}' value='5'><label for='star5-{$row['event_id']}'>★</label>
                    <input type='radio' name='rating' id='star4-{$row['event_id']}' value='4'><label for='star4-{$row['event_id']}'>★</label>
                    <input type='radio' name='rating' id='star3-{$row['event_id']}' value='3'><label for='star3-{$row['event_id']}'>★</label>
                    <input type='radio' name='rating' id='star2-{$row['event_id']}' value='2'><label for='star2-{$row['event_id']}'>★</label>
                    <input type='radio' name='rating' id='star1-{$row['event_id']}' value='1'><label for='star1-{$row['event_id']}'>★</label>
                  </div>

                  <textarea name='comments' placeholder='Write your feedback here...'></textarea>
                  <button type='submit' class='submit-btn'>Submit Feedback</button>
                </form>
              </div>";
    }
} else {
    echo "<p style='text-align:center;'>No past events available for feedback.</p>";
}
?>
<a href="student_dashboard.php" class="back-btn">⬅ Back to Dashboard</a>
</body>
</html>
