<?php
session_start();
include 'connect.php';
if (!isset($_SESSION['email']) || $_SESSION['role'] !== 'admin') {
    header("Location: login.php");
    exit();
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <title>View Feedback</title>
  <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;600&display=swap" rel="stylesheet">
  <style>
   body   {
    font-family: Arial, sans-serif;
    margin: 0;
    height: 100vh;
    display: flex;
    justify-content: center;
    align-items: center;
    background: linear-gradient(rgba(255, 255, 255, 0.4), rgba(255, 255, 255, 0.6)),
                url('https://i.postimg.cc/C1s63tdg/9b8e5cc5-0db7-4b11-81f0-adef8c67fe65.png') no-repeat center center fixed;
    background-size: cover;
    }
    .container {
      max-width: 1000px;
      margin: auto;
      background: #fff;
      padding: 25px 30px;
      border-radius: 12px;
      box-shadow: 0 8px 20px rgba(0,0,0,0.1);
    }
    h2 {
      text-align: center;
      color: #2c3e50;
      margin-bottom: 30px;
    }
    table {
      width: 100%;
      border-collapse: collapse;
      font-size: 15px;
    }
    th, td {
      padding: 14px;
      border-bottom: 1px solid #e0e0e0;
      text-align: left;
    }
    th {
      background-color: #2980b9;
      color: white;
      text-transform: uppercase;
    }
    tr:nth-child(even) {
      background-color: #f9f9f9;
    }
    .stars {
      color: #f39c12;
      font-size: 18px;
    }
    .back-btn {
      display: inline-block;
      margin-top: 25px;
      padding: 10px 20px;
      background: #e67e22;
      color: white;
      text-decoration: none;
      border-radius: 8px;
      font-weight: bold;
      transition: 0.3s ease;
    }
    .back-btn:hover {
      background-color: #d35400;
    }
  </style>
</head>
<body>
<div class="container">
  <h2>📊 All Feedback Received</h2>
  <table>
    <thead>
      <tr>
        <th>Event Title</th>
        <th>Student</th>
        <th>Rating</th>
        <th>Comments</th>
        <th>Submitted At</th>
      </tr>
    </thead>
    <tbody>
      <?php
      $query = "
        SELECT f.rating, f.comments, f.submitted_at, 
               e.title AS event_title, u.name AS student_name
        FROM feedback f
        JOIN events e ON f.event_id = e.event_id
        JOIN users u ON f.user_id = u.user_id
        ORDER BY f.submitted_at DESC
      ";

      $result = $conn->query($query);
      if ($result && $result->num_rows > 0) {
          while ($row = $result->fetch_assoc()) {
              echo "<tr>
                      <td>" . htmlspecialchars($row['event_title']) . "</td>
                      <td>" . htmlspecialchars($row['student_name']) . "</td>
                      <td class='stars'>" . str_repeat("⭐", $row['rating']) . "</td>
                      <td>" . htmlspecialchars($row['comments']) . "</td>
                      <td>" . date('M d, Y H:i', strtotime($row['submitted_at'])) . "</td>
                    </tr>";
          }
      } else {
          echo "<tr><td colspan='5'>No feedback available yet.</td></tr>";
      }
      ?>
    </tbody>
  </table>
  <a href="admin_dashboard.php" class="back-btn">⬅ Back to Dashboard</a>
</div>
</body>
</html>
