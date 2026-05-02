<?php
session_start();
include 'connect.php';
if (!isset($_SESSION['email']) || $_SESSION['role'] !== 'student') {
    header("Location: login.php");
    exit();
}
$user_id = $_SESSION['user_id'];
$popup = ""; // For triggering SweetAlert
// Register handler
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['event_id'])) {
    $event_id = $_POST['event_id'];
    // Get event name
    $titleStmt = $conn->prepare("SELECT title FROM events WHERE event_id = ?");
    $titleStmt->bind_param("i", $event_id);
    $titleStmt->execute();
    $titleStmt->bind_result($event_title);
    $titleStmt->fetch();
    $titleStmt->close();
    // Check if already registered
    $check = $conn->prepare("SELECT * FROM registrations WHERE user_id = ? AND event_id = ?");
    $check->bind_param("ii", $user_id, $event_id);
    $check->execute();
    $check->store_result();
    if ($check->num_rows > 0) {
        $popup = "already_registered";
    } else {
        $stmt = $conn->prepare("INSERT INTO registrations (user_id, event_id, registration_time) VALUES (?, ?, NOW())");
        $stmt->bind_param("ii", $user_id, $event_id);
        if ($stmt->execute()) {
            $popup = "success";
        } else {
            $popup = "failed";
        }
    }
    $check->close();
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <title>Register for Events</title>
  <link href="https://fonts.googleapis.com/css2?family=Nunito:wght@400;700&display=swap" rel="stylesheet">
  <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
  <style>
    body {
      font-family: 'Nunito', sans-serif;
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
    .event-grid {
      display: grid;
      grid-template-columns: repeat(auto-fit, minmax(280px, 1fr));
      gap: 20px;
      max-width: 1000px;
      margin: auto;
    }
    .event-card {
      background: white;
      border-radius: 12px;
      padding: 20px;
      box-shadow: 0 8px 20px rgba(0, 0, 0, 0.08);
      transition: transform 0.3s ease;
    }
    .event-card:hover {
      transform: translateY(-5px);
    }
    .event-card h3 {
      color: #2980b9;
      margin-bottom: 10px;
    }
    .event-card p {
      margin: 4px 0;
      color: #555;
    }
    .register-btn {
      margin-top: 10px;
      padding: 10px 16px;
      background: linear-gradient(to right, #56ccf2, #2f80ed);
      border: none;
      color: white;
      border-radius: 6px;
      font-weight: bold;
      cursor: pointer;
      transition: background 0.3s ease;
    }
    .register-btn:hover {
      background: linear-gradient(to right, #2f80ed, #56ccf2);
    }
    .back-btn {
      display: block;
      text-align: center;
      margin-top: 30px;
      padding: 12px 24px;
      background-color: #e67e22;
      color: white;
      text-decoration: none;
      border-radius: 8px;
      font-weight: bold;
      width: fit-content;
      margin-left: auto;
      margin-right: auto;
    }
    .back-btn:hover {
      background-color: #d35400;
    }
  </style>
</head>
<body>
<h2>🎯 Available Events to Register</h2>
<div class="event-grid">
  <?php
  $today = date("Y-m-d");
  $result = $conn->query("SELECT * FROM events WHERE event_date >= '$today' ORDER BY event_date ASC");
  if ($result && $result->num_rows > 0) {
      while ($row = $result->fetch_assoc()) {
          echo "<div class='event-card'>
                  <h3>" . htmlspecialchars($row['title']) . "</h3>
                  <p><strong>Date:</strong> " . date('M d, Y', strtotime($row['event_date'])) . "</p>
                  <p><strong>Time:</strong> " . date('h:i A', strtotime($row['event_time'])) . "</p>
                  <p><strong>Venue:</strong> " . htmlspecialchars($row['venue']) . "</p>
                  <form method='POST' action=''>
                    <input type='hidden' name='event_id' value='" . $row['event_id'] . "'>
                    <button type='submit' class='register-btn'>Register</button>
                  </form>
                </div>";
      }
  } else {
      echo "<p style='text-align:center;'>No upcoming events found.</p>";
  }
  ?>
</div>
<a href="student_dashboard.php" class="back-btn">⬅ Back to Dashboard</a>
<?php if (!empty($popup)): ?>
<script>
  <?php if ($popup == 'success'): ?>
    Swal.fire({
      icon: 'success',
      title: 'Registered Successfully!',
      text: 'You have successfully registered for: "<?= addslashes($event_title) ?>".',
      confirmButtonColor: '#2f80ed'
    });
  <?php elseif ($popup == 'already_registered'): ?>
    Swal.fire({
      icon: 'warning',
      title: 'Already Registered',
      text: 'You have already registered for: "<?= addslashes($event_title) ?>".',
      confirmButtonColor: '#f39c12'
    });
  <?php elseif ($popup == 'failed'): ?>
    Swal.fire({
      icon: 'error',
      title: 'Registration Failed',
      text: 'Something went wrong. Please try again.',
      confirmButtonColor: '#e74c3c'
    });
  <?php endif; ?>
</script>
<?php endif; ?>
</body>
</html>
