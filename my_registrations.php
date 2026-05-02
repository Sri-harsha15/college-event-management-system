<?php
session_start();
include 'connect.php';
if (!isset($_SESSION['email']) || $_SESSION['role'] !== 'student') {
    header("Location: login.php");
    exit();
}
$user_id = $_SESSION['user_id'];
// Cancel registration handler
if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['cancel_id'])) {
    $cancel_id = $_POST['cancel_id'];
    $stmt = $conn->prepare("DELETE FROM registrations WHERE registration_id = ? AND user_id = ?");
    $stmt->bind_param("ii", $cancel_id, $user_id);
    if ($stmt->execute()) {
        $message = "✅ Registration cancelled successfully.";
    } else {
        $message = "❌ Unable to cancel registration.";
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <title>My Event Registrations</title>
  <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;600&display=swap" rel="stylesheet">
  <style>
    body {
      font-family: 'Poppins', sans-serif;
        background: linear-gradient(rgba(255, 255, 255, 0.4), rgba(255, 255, 255, 0.6)),
                url('https://i.postimg.cc/C1s63tdg/9b8e5cc5-0db7-4b11-81f0-adef8c67fe65.png') no-repeat center center fixed;
    background-size: cover;
      margin: 0;
      padding: 40px 20px;
    }
    .container {
      max-width: 950px;
      margin: auto;
      background: white;
      padding: 30px;
      border-radius: 16px;
      box-shadow: 0 8px 24px rgba(0, 0, 0, 0.08);
    }
    h2 {
      text-align: center;
      color: #2c3e50;
      margin-bottom: 20px;
    }
    table {
      width: 100%;
      border-collapse: collapse;
      background: #fff;
      margin-top: 10px;
    }
    th, td {
      padding: 14px 16px;
      border-bottom: 1px solid #eee;
      text-align: left;
    }
    th {
      background: #2c3e50;
      color: #fff;
      text-transform: uppercase;
    }
    tr:nth-child(even) {
      background-color: #f9f9f9;
    }
    tr:hover {
      background-color: #f0f8ff;
    }
    .cancel-btn {
      background: #e74c3c;
      color: white;
      border: none;
      padding: 8px 12px;
      border-radius: 6px;
      cursor: pointer;
      transition: 0.3s ease;
    }
    .cancel-btn:hover {
      background: #c0392b;
    }
    .print-btn, .back-btn {
      margin: 25px 5px 0;
      padding: 10px 20px;
      color: white;
      text-decoration: none;
      border-radius: 8px;
      font-weight: bold;
      border: none;
      cursor: pointer;
    }
    .print-btn {
      background: #3498db;
      float: right;
    }
    .print-btn:hover {
      background: #2980b9;
    }
    .back-btn {
      background: #e67e22;
    }
    .back-btn:hover {
      background: #d35400;
    }
    .message {
      text-align: center;
      font-weight: bold;
      color: #27ae60;
      margin-top: 10px;
    }
  </style>
</head>
<body>
<div class="container">
  <h2>📋 My Event Registrations</h2>
  <?php if (isset($message)): ?>
    <div class="message"><?= htmlspecialchars($message) ?></div>
  <?php endif; ?>
  <table>
    <thead>
      <tr>
        <th>Title</th>
        <th>Date</th>
        <th>Venue</th>
        <th>Registered At</th>
        <th>Action</th>
      </tr>
    </thead>
    <tbody>
      <?php
      $query = "
        SELECT r.registration_id, e.title, e.event_date, e.venue, r.registration_time
        FROM registrations r
        JOIN events e ON r.event_id = e.event_id
        WHERE r.user_id = $user_id
        ORDER BY r.registration_time DESC
      ";
      $result = $conn->query($query);
      if ($result && $result->num_rows > 0) {
          while ($row = $result->fetch_assoc()) {
              echo "<tr>
                      <td>" . htmlspecialchars($row['title']) . "</td>
                      <td>" . date('M d, Y', strtotime($row['event_date'])) . "</td>
                      <td>" . htmlspecialchars($row['venue']) . "</td>
                      <td>" . date('M d, Y H:i', strtotime($row['registration_time'])) . "</td>
                      <td>
                        <form method='POST' onsubmit='return confirm(\"Cancel this registration?\")'>
                          <input type='hidden' name='cancel_id' value='" . $row['registration_id'] . "'>
                          <button type='submit' class='cancel-btn'>Cancel</button>
                        </form>
                      </td>
                    </tr>";
          }
      } else {
          echo "<tr><td colspan='5'>You have not registered for any events yet.</td></tr>";
      }
      ?>
    </tbody>
  </table>
  <a href="student_dashboard.php" class="back-btn">⬅ Back to Dashboard</a>
  <button class="print-btn" onclick="window.print()">🖨️ Print</button>
</div>
</body>
</html>
