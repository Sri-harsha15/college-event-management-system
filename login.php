<?php session_start(); ?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <title>Login</title>
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
  .login-container {
    background-color: rgba(244, 241, 241, 0.95);
    padding: 35px 25px;
    border-radius: 12px;
    box-shadow: 0 8px 20px rgba(0, 0, 0, 0.3);
    width: 350px;
    text-align: center;
  }
  .login-container h2 {
    margin-bottom: 20px;
    color: #ff6f91;
  }
  .login-container input[type="email"],
  .login-container input[type="password"] {
    width: 90%;
    padding: 12px;
    margin: 10px 0;
    border-radius: 6px;
    border: 1px solid #ccc;
    font-size: 14px;
    background-color: #f9f9f9;
  }
  .login-container input[type="email"]:focus,
  .login-container input[type="password"]:focus {
    border-color: #ff6f91;
    outline: none;
    background-color: #fff;
  }
  .role-select {
    margin: 15px 0;
    text-align: left;
    color: #333;
    font-size: 14px;
  }
  .role-select label {
    display: inline-block;
    margin: 5px 10px 5px 0;
    cursor: pointer;
  }
  .login-container button {
    background-color: #ff6f91;
    border: none;
    color: white;
    padding: 12px;
    width: 100%;
    border-radius: 6px;
    font-size: 16px;
    cursor: pointer;
    transition: background-color 0.3s ease;
  }
  .login-container button:hover {
    background-color: #e05b7f;
  }
  .login-container p {
    margin-top: 15px;
    font-size: 14px;
  }
  .login-container a {
    color: #ff6f91;
    text-decoration: none;
    font-weight: bold;
  }
  .login-container a:hover {
    text-decoration: underline;
  }
</style>
</head>
<body>
  <div class="login-container">
    <h2>Login</h2>
    <form action="login_process.php" method="POST">
      <input type="email" name="email" placeholder="Email" required><br>
      <input type="password" name="password" placeholder="Password" required><br>
      <div class="role-select">
        <label><center><input type="radio" name="role" value="student" required> Student</label>
        <label><input type="radio" name="role" value="admin" required> Admin</center></label>
      </div>
      <button type="submit">Login</button>
    </form>
    <p>Don't have an account? <a href="register.php">Register</a></p>
  </div>
</body>
</html>
