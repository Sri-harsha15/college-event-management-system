<?php
session_start();
include 'connect.php';
if (!isset($_SESSION['email']) || $_SESSION['role'] !== 'student') {
    header("Location: login.php");
    exit();
}
$user_id = $_SESSION['user_id'];
$today = date('Y-m-d');
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <title>My Feedback</title>
  <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;600&display=swap" rel="stylesheet">
  <style>
    body {
      font-family: 'Poppins', sans-serif;
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
    table {
      width: 100%;
      border-collapse: collapse;
      background: white;
      box-shadow: 0 2px 8px rgba(0,0,0,0.1);
    }
    th, td {
      padding: 14px;
      text-align: left;
      border-bottom: 1px solid #ddd;
    }
    th {
      background-color: #6a11cb;
      color: white;
    }
    tr:nth-child(even) {
      background-color: #f9f9f9;
    }
    .btn {
      padding: 8px 14px;
      border-radius: 6px;
      color: white;
      font-weight: bold;
      text-decoration: none;
      margin-right: 6px;
      display: inline-block;
    }
    .btn-edit {
      background-color: #3498db;
    }
    .btn-edit:hover {
      background-color: #2980b9;
    }
    .btn-delete {
      background-color: #e74c3c;
    }
    .btn-delete:hover {
      background-color: #c0392b;
    }
    .toast {
      position: fixed;
      top: 20px;
      right: 30px;
      background: #27ae60;
      color: #fff;
      padding: 14px 24px;
      border-radius: 6px;
      box-shadow: 0 4px 12px rgba(0,0,0,0.15);
      font-weight: bold;
      z-index: 9999;
      animation: fadeOut 10s ease forwards;
    }
    @keyframes fadeOut {
      0% { opacity: 1; }
      75% { opacity: 1; }
      100% { opacity: 0; transform: translateY(-10px); }
    }
    .back-btn {
      display: block;
      text-align: center;
      margin-top: 30px;
      padding: 12px 24px;
      background-color: #6c5ce7;
      color: white;
      text-decoration: none;
      border-radius: 8px;
      font-weight: bold;
      width: fit-content;
      margin-left: auto;
      margin-right: auto;
    }
    .back-btn:hover {
      background-color: #4834d4;
    }
  </style>
</head>
<body>

<?php if (isset($_SESSION['toast'])): ?>
  <div class="toast"><?= $_SESSION['toast'] ?></div>
  <?php unset($_SESSION['toast']); ?>
<?php endif; ?>

<h2>📝 My Feedback</h2>
<table>
  <tr>
    <th>Event Title</th>
    <th>Rating</th>
    <th>Comments</th>
    <th>Date</th>
    <th>Actions</th>
  </tr>
  <?php
  $sql = "SELECT f.*, e.title FROM feedback f 
          JOIN events e ON f.event_id = e.event_id 
          WHERE f.user_id = ? 
          ORDER BY f.submitted_at DESC";
  $stmt = $conn->prepare($sql);
  $stmt->bind_param("i", $user_id);
  $stmt->execute();
  $result = $stmt->get_result();

  if ($result && $result->num_rows > 0) {
      while ($row = $result->fetch_assoc()) {
          $submitted_date = date('Y-m-d', strtotime($row['submitted_at']));
          echo "<tr>
                  <td>" . htmlspecialchars($row['title']) . "</td>
                  <td>" . str_repeat("⭐", $row['rating']) . "</td>
                  <td>" . htmlspecialchars($row['comments']) . "</td>
                  <td>" . date('M d, Y', strtotime($row['submitted_at'])) . "</td>
                  <td>";
          if ($submitted_date == $today) {
              echo "<a class='btn btn-edit' href='edit_feedback.php?id={$row['feedback_id']}'>Edit</a>";
          }
          echo "<a class='btn btn-delete' href='delete_feedback.php?id={$row['feedback_id']}' onclick=\"return confirm('Are you sure you want to delete this feedback?')\">Delete</a>
                </td>
                </tr>";
      }
  } else {
      echo "<tr><td colspan='5'>No feedback submitted yet.</td></tr>";
  }
  ?>
</table>
<a href="student_dashboard.php" class="back-btn">⬅ Back to Dashboard</a>
</body>
</html>
