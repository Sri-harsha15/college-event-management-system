<?php
session_start();
include 'connect.php';
if (!isset($_SESSION['email']) || $_SESSION['role'] !== 'student') {
    header("Location: login.php");
    exit();
}
$today = date('Y-m-d');
$query = "SELECT * FROM events ORDER BY event_date ASC";
$result = $conn->query($query);
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <title>View Events</title>
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
      max-width: 1000px;
      margin: auto;
      background: white;
      padding: 30px;
      border-radius: 16px;
      box-shadow: 0 10px 25px rgba(0, 0, 0, 0.08);
    }
    h2 {
      text-align: center;
      color: #2c3e50;
      margin-bottom: 25px;
    }
    table {
      width: 100%;
      border-collapse: collapse;
      font-size: 15px;
    }
    th, td {
      padding: 14px 16px;
      border-bottom: 1px solid #eee;
      text-align: left;
    }
    th {
      background: #7f8ff4;
      color: #fff;
      text-transform: uppercase;
    }
    tr:nth-child(even) {
      background-color: #f9f9f9;
    }
    tr:hover {
      background-color: #eef3fc;
    }
    .status {
      padding: 6px 10px;
      border-radius: 6px;
      font-size: 13px;
      color: white;
      display: inline-block;
    }
    .upcoming {
      background-color: #2ecc71;
    }
    .past {
      background-color: #e74c3c;
    }
    .back-btn {
      display: inline-block;
      margin-top: 25px;
      padding: 10px 20px;
      background: linear-gradient(to right, #ff6a00, #ee0979);
      color: white;
      text-decoration: none;
      border-radius: 8px;
      font-weight: bold;
    }
    .back-btn:hover {
      opacity: 0.9;
    }
  </style>
</head>
<body>
<div class="container">
  <h2>🎯 All Events</h2>
  <table>
    <thead>
      <tr>
        <th>Title</th>
        <th>Date</th>
        <th>Time</th>
        <th>Venue</th>
        <th>Status</th>
      </tr>
    </thead>
    <tbody>
      <?php
      if ($result && $result->num_rows > 0) {
          while ($event = $result->fetch_assoc()) {
              $status = (strtotime($event['event_date']) >= strtotime($today)) ? "upcoming" : "past";
              echo "<tr>
                <td>" . htmlspecialchars($event['title']) . "</td>
                <td>" . date("M d, Y", strtotime($event['event_date'])) . "</td>
                <td>" . htmlspecialchars($event['event_time']) . "</td>
                <td>" . htmlspecialchars($event['venue']) . "</td>
                <td><span class='status $status'>" . ucfirst($status) . "</span></td>
              </tr>";
          }
      } else {
          echo "<tr><td colspan='5'>No events available.</td></tr>";
      }
      ?>
    </tbody>
  </table>
  <a href="student_dashboard.php" class="back-btn">⬅ Back to Dashboard</a>
</div>
</body>
</html>
