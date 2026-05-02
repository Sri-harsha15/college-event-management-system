<?php
session_start();
if (isset($_SESSION['email'])) {
    header("Location: student_dashboard.php");
    exit();
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <title>Register</title>
  <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;600&display=swap" rel="stylesheet">
  <style>
    body {
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

    .register-box {
      background-color: white;
      padding: 40px;
      border-radius: 12px;
      box-shadow: 0 10px 30px rgba(0,0,0,0.1);
      width: 100%;
      max-width: 400px;
       transition: transform 0.3s ease, box-shadow 0.3s ease;
    }
/* Hover & Touch effect */
.register-box:hover,
.register-box:active {
  transform: translateY(-6px);
  box-shadow: 0 14px 35px rgba(0, 0, 0, 0.15);
}

    h2 {
      text-align: center;
      color: #4a47a3;
      margin-bottom: 30px;
    }

    input, select {
      width: 100%;
      padding: 12px;
      margin-bottom: 18px;
      border: 1px solid #ccc;
      border-radius: 6px;
      font-size: 15px;
    }

    button {
      width: 100%;
      background: linear-gradient(to right, #667eea, #764ba2);
      color: white;
      padding: 12px;
      border: none;
      border-radius: 6px;
      font-size: 16px;
      font-weight: bold;
      cursor: pointer;
    }

    button:hover {
      opacity: 0.9;
    }

    .login-section {
      text-align: center;
      margin-top: 24px;
      font-size: 14px;
      color: #555;
    }

    .login-link {
      color: #4a47a3;
      text-decoration: none;
      font-weight: 500;
      margin-left: 6px;
    }

    .login-link:hover {
      text-decoration: underline;
    }
  </style>
</head>
<body>

  <div class="register-box">
    <h2>📝 Create an Account</h2>
    <form method="POST" action="register_process.php">
      <input type="text" name="name" placeholder="Full Name" required>
      <input type="email" name="email" placeholder="Email Address" required>
      <input type="password" name="password" placeholder="Password" required>
      <select name="role" required>
        <option value="">Select Role</option>
        <option value="student">Student</option>
        <option value="admin">Admin</option>
      </select>
      <button type="submit">Register</button>
    </form>

    <div class="login-section">
      Already have an account?
      <a class="login-link" href="login.php">Login</a>
    </div>
  </div>

</body>
</html>
