<?php
session_start();
include 'connect.php';
if (!isset($_SESSION['email']) || $_SESSION['role'] !== 'admin') {
    header("Location: login.php");
    exit();
}
$searchTitle = isset($_GET['title']) ? $_GET['title'] : '';
$searchDate = isset($_GET['date']) ? $_GET['date'] : '';
$sql = "SELECT * FROM events WHERE 1=1";
if (!empty($searchTitle)) {
    $sql .= " AND title LIKE '%" . $conn->real_escape_string($searchTitle) . "%'";
}
if (!empty($searchDate)) {
    $sql .= " AND event_date = '" . $conn->real_escape_string($searchDate) . "'";
}
$sql .= " ORDER BY event_date ASC";
$result = $conn->query($sql);
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <title>Manage Events</title>
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">
  <style>
    body {
      background: linear-gradient(rgba(255, 255, 255, 0.4), rgba(255, 255, 255, 0.6)),
                url('https://i.postimg.cc/C1s63tdg/9b8e5cc5-0db7-4b11-81f0-adef8c67fe65.png') no-repeat center center fixed;
    background-size: cover;
      font-family: 'Segoe UI', sans-serif;
      padding: 40px;
      margin: 0;
    }
    h2 {
      text-align: center;
      color: #2c3e50;
    }
    .filter-form {
      text-align: center;
      margin-bottom: 30px;
    }
    input[type="text"], input[type="date"] {
      padding: 10px;
      margin: 0 10px;
      border-radius: 6px;
      border: 1px solid #ccc;
    }
    .filter-btn {
      padding: 10px 20px;
      background-color: #2ecc71;
      border: none;
      color: white;
      border-radius: 6px;
      cursor: pointer;
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
      background-color: #3498db;
      color: white;
    }
    tr:hover {
      background-color: #f0f8ff;
    }
    .action-btn {
      background-color: #3498db;
      color: white;
      padding: 8px 10px;
      border: none;
      border-radius: 6px;
      margin-right: 8px;
      cursor: pointer;
      text-decoration: none;
      display: inline-block;
      transition: 0.3s ease;
    }
    .delete-btn {
      background-color: #e74c3c;
    }
    .action-btn i {
      margin-right: 4px;
    }
    .action-btn:hover {
      opacity: 0.9;
    }
    .add-event {
      display: inline-block;
      margin-top: 20px;
      padding: 10px 20px;
      background: linear-gradient(to right, #36d1dc, #5b86e5);
      color: white;
      text-decoration: none;
      border-radius: 8px;
      font-weight: bold;
    }
    .back-btn {
      display: inline-block;
      margin-top: 30px;
      padding: 10px 20px;
      background-color: #f39c12;
      color: white;
      text-decoration: none;
      border-radius: 6px;
      font-weight: bold;
    }
    .back-btn:hover {
      background-color: #d68910;
    }
  </style>
</head>
<body>
  <?php if (isset($_GET['message'])): ?>
  <div style="text-align:center; margin-bottom:20px; color:
    <?= $_GET['message'] === 'deleted' ? 'green' : 'red' ?>;">
    <?php
      if ($_GET['message'] === 'deleted') echo "✅ Event deleted successfully.";
      elseif ($_GET['message'] === 'error') echo "❌ Failed to delete the event.";
      elseif ($_GET['message'] === 'invalid') echo "⚠️ Invalid event ID.";
    ?>
  </div>
<?php endif; ?>

  <h2>📅 Manage Events</h2>
  <form class="filter-form" method="GET" action="">
    <input type="text" name="title" placeholder="Search by title" value="<?= htmlspecialchars($searchTitle) ?>">
    <input type="date" name="date" value="<?= htmlspecialchars($searchDate) ?>">
    <button type="submit" class="filter-btn">🔍 Filter</button>
  </form>
  <table>
    <tr>
      <th>Title</th>
      <th>Description</th>
      <th>Date</th>
      <th>Time</th>
      <th>Venue</th>
      <th>Actions</th>
    </tr>
    <?php
    if ($result && $result->num_rows > 0) {
        while ($row = $result->fetch_assoc()) {
            echo "<tr>
              <td>" . htmlspecialchars($row['title']) . "</td>
              <td>" . htmlspecialchars($row['description']) . "</td>
              <td>" . htmlspecialchars($row['event_date']) . "</td>
              <td>" . htmlspecialchars($row['event_time']) . "</td>
              <td>" . htmlspecialchars($row['venue']) . "</td>
              <td>
                <a href='edit_event.php?id=" . $row['event_id'] . "' class='action-btn' title='Edit'>
                  <i class='fas fa-edit'></i> Edit
                </a>
                <a href='delete_event.php?id=" . $row['event_id'] . "' class='action-btn delete-btn' title='Delete' onclick='return confirm(\"Are you sure want to delete?\")'>
                  <i class='fas fa-trash-alt'></i> Delete
                </a>
              </td>
            </tr>";
        }
    } else {
        echo "<tr><td colspan='6'>No events found.</td></tr>";
    }
    ?>
  </table>
  <div style="text-align:center;">
    <a href="create_event.php" class="add-event">➕ Add New Event</a><br><br>
    <a href="admin_dashboard.php" class="back-btn">⬅ Back to Dashboard</a>
  </div>
</body>
</html>
