<?php
session_start();
include 'connect.php';
if (!isset($_SESSION['email']) || $_SESSION['role'] !== 'admin') {
    header("Location: login.php");
    exit();
}
$success = "";
$error = "";
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $title = $_POST['title'];
    $description = $_POST['description'];
    $event_date = $_POST['event_date'];
    $event_time = $_POST['event_time'];
    $venue = $_POST['venue'];
    if ($title && $event_date && $event_time && $venue) {
        $stmt = $conn->prepare("INSERT INTO events (title, description, event_date, event_time, venue) VALUES (?, ?, ?, ?, ?)");
        $stmt->bind_param("sssss", $title, $description, $event_date, $event_time, $venue);
        if ($stmt->execute()) {
            $success = "🎉 Event created successfully!";
        } else {
            $error = "❌ Failed to create event.";
        }
    } else {
        $error = "⚠ Please fill all required fields.";
    }
}
?>
<!DOCTYPE html>
<html>
<head>
  <title>Create Event</title>
  <link href="https://fonts.googleapis.com/css2?family=Roboto:wght@400;600&display=swap" rel="stylesheet">
  <style>
    * { box-sizing: border-box; }
    body {
      font-family: 'Roboto', sans-serif;
         background: linear-gradient(rgba(255, 255, 255, 0.4), rgba(255, 255, 255, 0.6)),
                url('https://i.postimg.cc/C1s63tdg/9b8e5cc5-0db7-4b11-81f0-adef8c67fe65.png') no-repeat center center fixed;
    background-size: cover;
      padding: 0;
      margin: 0;
      font-size: 14px;
    }
    .container {
      max-width: 500px;
      margin: 50px auto;
      background: linear-gradient(to right, #ffffff, #f1f8ff);
      padding: 25px 20px;
      border-radius: 15px;
      box-shadow: 0 8px 24px rgba(0, 0, 0, 0.1);
    }
    h2 {
      text-align: center;
      font-size: 20px;
      color: #ff6f61;
      margin-bottom: 20px;
    }
    label {
      display: block;
      margin-top: 14px;
      margin-bottom: 4px;
      font-weight: bold;
      font-size: 13px;
      color: #333;
    }
    input, textarea {
      width: 100%;
      padding: 8px 10px;
      font-size: 13px;
      border: 1px solid #ccc;
      border-radius: 6px;
      background: #fff8f5;
      transition: border 0.3s ease;
    }
    input:focus, textarea:focus {
      border-color: #ff6f61;
      background: #fff;
      outline: none;
    }
    textarea {
      resize: vertical;
      height: 70px;
    }
    .btn {
      margin-top: 20px;
      width: 100%;
      padding: 10px;
      font-size: 14px;
      background: linear-gradient(90deg, #ff9a9e, #fad0c4);
      color: #333;
      border: none;
      border-radius: 8px;
      cursor: pointer;
      font-weight: 600;
      box-shadow: 0 4px 12px rgba(255, 105, 135, 0.3);
      transition: background 0.3s ease;
    }
    .btn:hover {
      background: linear-gradient(90deg, #ff758c, #ff7eb3);
      color: white;
    }
    .message {
      margin-top: 15px;
      padding: 10px;
      font-size: 13px;
      text-align: center;
      border-radius: 6px;
    }
    .success {
      background: #d4edda;
      color: #155724;
    }
    .error {
      background: #f8d7da;
      color: #721c24;
    }
    .back-link {
      display: inline-block;
      margin-top: 18px;
      text-decoration: none;
      color: #007bff;
      font-size: 13px;
    }
    .back-link:hover {
      text-decoration: underline;
    }
    @media (max-width: 500px) {
      .container {
        margin: 30px 15px;
        padding: 20px;
      }
    }
  </style>
</head>
<body>
<div class="container">
  <h2>✨ Create a New Event</h2>
  <?php if ($success): ?>
    <div class="message success"><?= $success ?></div>
  <?php elseif ($error): ?>
    <div class="message error"><?= $error ?></div>
  <?php endif; ?>
  <form method="POST" action="">
    <label for="title">Event Title *</label>
    <input type="text" name="title" id="title" required>
    <label for="description">Description</label>
    <textarea name="description" id="description"></textarea>
    <label for="event_date">Date *</label>
    <input type="date" name="event_date" id="event_date" required>
    <label for="event_time">Time *</label>
    <input type="time" name="event_time" id="event_time" required>
    <label for="venue">Venue *</label>
    <input type="text" name="venue" id="venue" required>
    <button type="submit" class="btn">📌 Create Event</button>
  </form>
  <a href="admin_dashboard.php" class="back-link">⬅ Back to Dashboard</a>
</div>
</body>
</html>
