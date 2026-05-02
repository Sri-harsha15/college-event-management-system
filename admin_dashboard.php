<?php
session_start();
include 'connect.php';
if (!isset($_SESSION['email']) || $_SESSION['role'] !== 'admin') {
    header("location: login.php");
    exit();
}
$name = $_SESSION['name'];
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8" />
  <title>Admin Dashboard</title>
  <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;600&display=swap" rel="stylesheet" />
  <style>
    * {
      box-sizing: border-box;
      margin: 0;
      padding: 0;
    }
    body {  
      font-family: 'Poppins', sans-serif;
      background: #f0f4f8;
      color: #333;
    }
    .container {
      display: flex;
      min-height: 100vh;
    }
    .sidebar {
      width: 250px;
      background: linear-gradient(180deg, #8e2de2, #4a00e0);
      color: white;
      padding: 30px 20px;
      box-shadow: 2px 0 10px rgba(0, 0, 0, 0.1);
    }
    .sidebar h2 {
      font-size: 24px;
      text-align: center;
      margin-bottom: 40px;
    }
    .sidebar ul {
      list-style: none;
      padding: 0;
    }
    .sidebar ul li {
      margin: 20px 0;
    }
    .sidebar ul li a {
      color: white;
      text-decoration: none;
      font-weight: 600;
      display: block;
      padding: 10px 15px;
      border-radius: 8px;
      transition: 0.3s;
    }
    .sidebar ul li a:hover {
      background: rgba(255, 255, 255, 0.2);
      color: #ffeb3b;
    }
    .main {
      flex: 1;
      padding: 40px;
      background: #fff;
      overflow-y: auto;
    }
    .logout-btn {
      float: right;
      background: #e74c3c;
      color: white;
      border: none;
      padding: 10px 20px;
      font-weight: bold;
      border-radius: 6px;
      cursor: pointer;
      transition: background 0.3s;
    }
    .logout-btn:hover {
      background: #c0392b;
    }
    .main h1 {
      font-size: 28px;
      margin-bottom: 30px;
      color: #333;
    }
    .stats {
      display: flex;
      gap: 20px;
      margin-bottom: 40px;
      flex-wrap: wrap;
    }
    .card {
      flex: 1;
      min-width: 200px;
      background: linear-gradient(to right, #00c6ff, #0072ff);
      color: white;
      padding: 30px;
      border-radius: 15px;
      box-shadow: 0 4px 15px rgba(0, 0, 0, 0.1);
      text-align: center;
      font-size: 20px;
      font-weight: bold;
      transition: transform 0.3s;
    }
    .card:hover {
      transform: translateY(-5px);
    }
    .box {
      background: #f9f9f9;
      padding: 25px;
      border-radius: 10px;
      box-shadow: 0 3px 10px rgba(0,0,0,0.05);
    }
    .box h2 {
      margin-bottom: 20px;
      font-size: 22px;
      color: #2c3e50;
    }
    table {
      width: 100%;
      border-collapse: collapse;
    }
    table th, table td {
      padding: 12px;
      text-align: left;
      border-bottom: 1px solid #ddd;
    }
    table th {
      background: #eaf1f8;
      color: #34495e;
    }
    table tr:hover {
      background-color: #f2f6fa;
    }
    @media (max-width: 768px) {
      .container {
        flex-direction: column;
      }
      .sidebar {
        width: 100%;
        text-align: center;
      }
      .stats {
        flex-direction: column;
      }
    }
  </style>
</head>
<body>
  <div class="container">
    <aside class="sidebar">
      <h2>Admin Panel</h2>
      <ul>
        <li><a href="#">Dashboard</a></li>
        <li><a href="manage_events.php">📅Manage Events</a></li>
        <li><a href="view_registrations.php">📋 View Registrations</a></li>
        <li><a href="view_feedback.php">🧾 View Feedback</a></li>
        <li><a class="nav-link" href="logout.php" onclick="return confirm('Are You Sure Want to Logout?')">📴 Logout</a></li>
      </ul>
    </aside>
    <main class="main">
      <h1>Welcome, Admin <?php echo htmlspecialchars($name); ?> 👨‍💼</h1>
      <div class="stats">
        <div class="card">
          <?php
            $res = $conn->query("SELECT COUNT(*) as total FROM users WHERE role='student'");
            $row = $res->fetch_assoc();
            echo "Students: " . $row['total'];
          ?>
        </div>
        <div class="card" style="background: linear-gradient(to right, #fc4a1a, #f7b733);">
          <?php
            $res = $conn->query("SELECT COUNT(*) as total FROM events");
            $row = $res->fetch_assoc();
            echo "Total Events: " . $row['total'];
          ?>
        </div>
        <div class="card" style="background: linear-gradient(to right, #56ab2f, #a8e063);">
          <?php
            $res = $conn->query("SELECT COUNT(*) as total FROM registrations");
            $row = $res->fetch_assoc();
            echo "Registrations: " . $row['total'];
          ?>
        </div>
        <div class="card" style="background: linear-gradient(to right, #ee0979, #ff6a00);">
          <?php
            $res = $conn->query("SELECT COUNT(*) as total FROM feedback");
            $row = $res->fetch_assoc();
            echo "Feedback: " . $row['total'];
          ?>
        </div>
      </div>
      <div class="box">
        <h2>🗓️ Recent Events</h2>
        <table>
          <tr><th>Title</th><th>Date</th><th>Venue</th></tr>
          <?php
            $result = $conn->query("SELECT title, event_date, venue FROM events ORDER BY event_date DESC LIMIT 5");
            if ($result && $result->num_rows > 0) {
              while ($row = $result->fetch_assoc()) {
                echo "<tr>
                        <td>" . htmlspecialchars($row['title']) . "</td>
                        <td>" . date('M d, Y', strtotime($row['event_date'])) . "</td>
                        <td>" . htmlspecialchars($row['venue']) . "</td>
                      </tr>";
              }
            } else {
              echo "<tr><td colspan='3'>No recent events</td></tr>";
            }
          ?>
        </table>
      </div>
    </main>
  </div>
</body>
</html>
