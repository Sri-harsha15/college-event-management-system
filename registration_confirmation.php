<?php
session_start();
if (!isset($_SESSION['email']) || $_SESSION['role'] !== 'student') {
    header("Location: login.php");
    exit();
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <title>Registration Confirmation</title>
  <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;600&display=swap" rel="stylesheet">
  <style>
    body {
      font-family: 'Poppins', sans-serif;
      background: linear-gradient(to right, #d3cce3, #e9e4f0);
      display: flex;
      justify-content: center;
      align-items: center;
      height: 100vh;
      margin: 0;
    }
    .confirmation-box {
      background: #fff;
      padding: 40px 30px;
      border-radius: 14px;
      box-shadow: 0 12px 24px rgba(0,0,0,0.1);
      text-align: center;
      max-width: 500px;
    }
    .confirmation-box h2 {
      color: #27ae60;
      font-size: 26px;
      margin-bottom: 15px;
    }
    .confirmation-box p {
      font-size: 16px;
      color: #2c3e50;
      margin-bottom: 30px;
    }
    .btn {
      display: inline-block;
      text-decoration: none;
      padding: 10px 20px;
      font-weight: bold;
      color: #fff;
      border-radius: 8px;
      transition: 0.3s ease;
      margin: 5px;
    }
    .btn-dashboard {
      background: #3498db;
    }
    .btn-events {
      background: #2ecc71;
    }
    .btn:hover {
      transform: scale(1.05);
      opacity: 0.9;
    }
  </style>
</head>
<body>
<div class="confirmation-box">
  <h2>✅ Registration Successful!</h2>
  <p>Thank you for registering. You can now view your registration or explore other events.</p>
  <a href="my_registrations.php" class="btn btn-dashboard">📋 My Registrations</a>
  <a href="view_events.php" class="btn btn-events">🎉 View Events</a>
</div>
</body>
</html>
