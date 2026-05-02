<?php
session_start();
include 'connect.php';
if (!isset($_SESSION['email']) || $_SESSION['role'] !== 'admin') {
    header("Location: login.php");
    exit();
}
if (!isset($_GET['id'])) {
    echo "Invalid request!";
    exit();
}
$event_id = intval($_GET['id']);
$sql = "SELECT * FROM events WHERE event_id = $event_id";
$result = $conn->query($sql);
if ($result->num_rows !== 1) {
    echo "Event not found!";
    exit();
}
$event = $result->fetch_assoc();
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $title = trim($_POST['title']);
    $description = trim($_POST['description']);
    $event_date = $_POST['event_date'];
    $event_time = $_POST['event_time'];
    $venue = trim($_POST['venue']);
    $stmt = $conn->prepare("UPDATE events SET title=?, description=?, event_date=?, event_time=?, venue=? WHERE event_id=?");
    $stmt->bind_param("sssssi", $title, $description, $event_date, $event_time, $venue, $event_id);
    if ($stmt->execute()) {
        echo "<script>alert('Event updated successfully!'); window.location.href='manage_events.php';</script>";
        exit();
    } else {
        echo "Error: " . $conn->error;
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8" />
  <title>Edit Event</title>
  <link href="https://fonts.googleapis.com/css2?family=Rubik:wght@400;600&display=swap" rel="stylesheet">
  <style>
    body {
      font-family: 'Rubik', sans-serif;
         background: linear-gradient(rgba(255, 255, 255, 0.4), rgba(255, 255, 255, 0.6)),
                url('https://i.postimg.cc/C1s63tdg/9b8e5cc5-0db7-4b11-81f0-adef8c67fe65.png') no-repeat center center fixed;
    background-size: cover;
      margin: 0;
      padding: 50px;
    }
    .form-container {
      background: white;
      max-width: 520px;
      margin: auto;
      padding: 30px;
      border-radius: 15px;
      box-shadow: 0 10px 25px rgba(0,0,0,0.1);
    }
    h2 {
      text-align: center;
      color: #0077b6;
      margin-bottom: 25px;
    }
    input, textarea {
      width: 100%;
      padding: 12px;
      margin: 10px 0 20px;
      border-radius: 8px;
      border: 1px solid #ccc;
      font-size: 15px;
      background: #f9fcff;
    }
    input:focus, textarea:focus {
      border-color: #0077b6;
      outline: none;
    }
    button {
      background: linear-gradient(to right, #00b894, #00cec9);
      color: white;
      padding: 12px;
      width: 100%;
      border: none;
      border-radius: 8px;
      font-size: 16px;
      font-weight: bold;
      cursor: pointer;
      transition: background 0.3s;
    }
    button:hover {
      background: linear-gradient(to right, #00cec9, #00b894);
    }
    .back {
      display: block;
      text-align: center;
      margin-top: 18px;
      text-decoration: none;
      color: #0077b6;
      font-weight: bold;
    }
    .back:hover {
      text-decoration: underline;
    }
    @media (max-width: 600px) {
      body {
        padding: 20px;
      }
      .form-container {
        padding: 20px;
      }
    }
  </style>
</head>
<body>
  <div class="form-container">
    <h2>Edit Event ✍️</h2>
    <form method="POST">
      <input type="text" name="title" placeholder="Event Title" value="<?= htmlspecialchars($event['title']) ?>" required>
      <textarea name="description" rows="4" placeholder="Event Description" required><?= htmlspecialchars($event['description']) ?></textarea>
      <input type="date" name="event_date" value="<?= $event['event_date'] ?>" required>
      <input type="time" name="event_time" value="<?= $event['event_time'] ?>" required>
      <input type="text" name="venue" placeholder="Venue" value="<?= htmlspecialchars($event['venue']) ?>" required>
      <button type="submit">✅ Update Event</button>
    </form>
    <a class="back" href="manage_events.php">← Back to Manage Events</a>
  </div>
</body>
</html>
