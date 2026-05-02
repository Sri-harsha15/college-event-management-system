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
  <title>View Registrations</title>
  <link href="https://fonts.googleapis.com/css2?family=Nunito:wght@400;700&display=swap" rel="stylesheet">
  <style>
    body {
      font-family: 'Nunito', sans-serif;
      background: linear-gradient(rgba(255, 255, 255, 0.4), rgba(255, 255, 255, 0.6)),
                url('https://i.postimg.cc/C1s63tdg/9b8e5cc5-0db7-4b11-81f0-adef8c67fe65.png') no-repeat center center fixed;
    background-size: cover;
      margin: 0;
      padding: 40px 20px;
    }
    .container {
      max-width: 1000px;
      margin: auto;
      background: rgba(255, 255, 255, 0.7);
      padding: 30px;
      border-radius: 20px;
      box-shadow: 0 15px 30px rgba(0, 0, 0, 0.15);
    }
    h2 {
      text-align: center;
      color: #2d3436;
      margin-bottom: 30px;
      font-size: 28px;
      letter-spacing: 1px;
    }
    table {
      width: 100%;
      border-collapse: collapse;
      font-size: 16px;
    }
    th {
      background: linear-gradient(to right, #00b09b, #96c93d);
      color: white;
      padding: 14px;
      text-align: left;
      text-transform: uppercase;
      letter-spacing: 0.5px;
    }
    td {
      padding: 12px 16px;
      border-bottom: 1px solid #ddd;
      background-color: #fdfdfd;
    }
    tr:nth-child(even) td {
      background-color: #f1f8f9;
    }
    tr:hover td {
      background-color: #ffeaa7;
      transition: background 0.3s ease;
    }
    .back-btn {
      display: inline-block;
      margin-top: 25px;
      padding: 12px 24px;
      background: linear-gradient(to right, #ff6a00, #ee0979);
      color: white;
      text-decoration: none;
      font-weight: bold;
      border-radius: 8px;
      transition: all 0.3s ease;
      float: left;
    }
    .back-btn:hover {
      transform: translateY(-2px);
      background: linear-gradient(to right, #ee0979, #ff6a00);
    }
    @media (max-width: 768px) {
      table, thead, tbody, th, td, tr {
        display: block;
      }
      thead tr {
        display: none;
      }
      tr {
        margin-bottom: 15px;
        border: 1px solid #ddd;
        border-radius: 8px;
        overflow: hidden;
      }
      td {
        position: relative;
        padding-left: 50%;
        text-align: right;
      }
      td::before {
        position: absolute;
        left: 16px;
        top: 14px;
        font-weight: bold;
        color: #555;
        text-align: left;
        width: 45%;
        white-space: nowrap;
      }
      td:nth-of-type(1)::before { content: "Student Name"; }
      td:nth-of-type(2)::before { content: "Event Title"; }
      td:nth-of-type(3)::before { content: "Registered At"; }
    }
  </style>
</head>
<body>
<div class="container">
  <h2>📜 All Event Registrations</h2>
  <table>
    <thead>
      <tr>
        <th>Student Name</th>
        <th>Event Title</th>
        <th>Registered At</th>
      </tr>
    </thead>
    <tbody>
      <?php
        $sql = "
          SELECT r.registration_time, u.name AS student_name, e.title AS event_title
          FROM registrations r
          JOIN users u ON r.user_id = u.user_id
          JOIN events e ON r.event_id = e.event_id
          ORDER BY r.registration_time DESC
        ";
        $result = $conn->query($sql);
        if ($result && $result->num_rows > 0) {
            while ($row = $result->fetch_assoc()) {
                echo "<tr>
                        <td>" . htmlspecialchars($row['student_name']) . "</td>
                        <td>" . htmlspecialchars($row['event_title']) . "</td>
                        <td>" . date('M d, Y H:i', strtotime($row['registration_time'])) . "</td>
                      </tr>";
            }
        } else {
            echo "<tr><td colspan='3'>No registrations found.</td></tr>";
        }
      ?>
    </tbody>
  </table>
  <a href="admin_dashboard.php" class="back-btn">⬅ Back to Dashboard</a>
</div>
</body>
</html>
