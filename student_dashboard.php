<?php
session_start();
include 'connect.php';
if (!isset($_SESSION['email']) || $_SESSION['role'] !== 'student') {
    header("Location: login.php");
    exit();
}
$user_email = $_SESSION['email'];
$user_id = $_SESSION['user_id']; // Ensure 'id' is set in session after login
$today = date('Y-m-d');
// Fetch upcoming events
$upcoming_result = $conn->query("SELECT * FROM events WHERE event_date >= CURDATE() ORDER BY event_date ASC LIMIT 5");
// Fetch today's events
$today_result = $conn->query("SELECT * FROM events WHERE event_date = '$today'");
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <title>Student Dashboard</title>
  <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@500&display=swap" rel="stylesheet">
  <style>
    * { box-sizing: border-box; margin: 0; padding: 0; }
    body {
      font-family: 'Poppins', sans-serif;
      display: flex;
      min-height: 100vh;
         background: linear-gradient(rgba(255, 255, 255, 0.4), rgba(255, 255, 255, 0.6)),
                url('https://i.postimg.cc/C1s63tdg/9b8e5cc5-0db7-4b11-81f0-adef8c67fe65.png') no-repeat center center fixed;
    background-size: cover;
    }
    .sidebar {
  width: 230px;
  background: linear-gradient(to bottom, #1D2671, #C33764);
      color: white;
      padding: 30px 20px;
      display: flex;
      flex-direction: column;
      align-items: center;
    }
    .profile {
      text-align: center;
      margin-bottom: 30px;
    }
    .profile img {
      width: 80px;
      height: 80px;
      border-radius: 50%;
      border: 3px solid white;
    }
    .profile p {
      margin-top: 10px;
      font-weight: 600;
    }
    .nav-link {
      width: 100%;
      text-decoration: none;
      color: white;
      padding: 12px;
      border-radius: 8px;
      margin: 6px 0;
      display: block;
      text-align: center;
      transition: 0.3s;
      background-color: rgba(255,255,255,0.1);
    }
    .nav-link:hover {
      background-color: rgba(255,255,255,0.2);
    }
    .main {
      flex: 1;
      padding: 40px;
    }
    .section {
      background: white;
      padding: 25px;
      border-radius: 12px;
      box-shadow: 0 4px 12px rgba(0,0,0,0.05);
      margin-bottom: 30px;
    }
    h2 {
      margin-bottom: 15px;
      color: #2c3e50;
    }
    ul {
      list-style: none;
    }
    li {
      padding: 8px 0;
      color: #34495e;
    }
    .event-title {
      font-weight: 600;
      color: #5b3cc4;
    }
    .event-date {
      color: #555;
      font-size: 14px;
    }
  </style>
</head>
<body>
  <div class="sidebar">
    <div class="profile">
      <img src="https://i.pravatar.cc/100?u=<?= $user_id ?>" alt="Avatar">
      <p><?= htmlspecialchars($user_email) ?></p>
    </div>
    <a class="nav-link" href="view_events.php">📅 Events</a>
    <a class="nav-link" href="events_register.php">📝 Register</a>
    <a class="nav-link" href="my_registrations.php">📋 My Registrations</a>
    <a class="nav-link" href="student_feedback.php">⭐ Feedback</a>
    <a class="nav-link" href="my_feedback.php">🧾 My Feedback</a>
    <a class="nav-link" href="logout.php" onclick="return confirm('Are You Sure Want to Logout?')">🚪 Logout</a>
  </div>
  <div class="main">
    <div class="section">
      <h2>👋 Welcome Back!</h2>
      <p>Use the sidebar to browse upcoming events, register for activities, and leave feedback after attending. Stay updated and involved!</p>
    </div>
    <div class="section">
      <h2>🔔 Upcoming Events</h2>
      <ul>
        <?php
        if ($upcoming_result && $upcoming_result->num_rows > 0) {
            while ($event = $upcoming_result->fetch_assoc()) {
                echo "<li><span class='event-title'>" . htmlspecialchars($event['title']) . "</span> — <span class='event-date'>" . date('M d, Y', strtotime($event['event_date'])) . "</span></li>";
            }
        } else {
            echo "<li>No upcoming events.</li>";
        }
        ?>
      </ul>
    </div>
    <div class="section">
      <h2>📅 Today's Events</h2>
      <ul>
        <?php
        if ($today_result && $today_result->num_rows > 0) {
            while ($event = $today_result->fetch_assoc()) {
                echo "<li><span class='event-title'>" . htmlspecialchars($event['title']) . "</span> at <span class='event-date'>" . htmlspecialchars($event['event_time']) . "</span> in " . htmlspecialchars($event['venue']) . "</li>";
            }
        } else {
            echo "<li>No events scheduled for today.</li>";
        }
        ?>
      </ul>
    </div>
  </div>
</body>
</html>
