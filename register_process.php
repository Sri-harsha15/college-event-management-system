<?php
session_start();
include 'connect.php';
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $name     = trim($_POST['name']);
    $email    = trim($_POST['email']);
    $password = $_POST['password'];
    $role     = $_POST['role'];
    // Validate inputs
    if (empty($name) || empty($email) || empty($password) || empty($role)) {
        $_SESSION['message'] = "⚠️ All fields are required.";
        header("Location: register.php");
        exit();
    }
    // Validate email format
    if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $_SESSION['message'] = "❌ Invalid email format.";
        header("Location: register.php");
        exit();
    }
    // Check if email already exists
    $stmt = $conn->prepare("SELECT * FROM users WHERE email = ?");
    $stmt->bind_param("s", $email);
    $stmt->execute();
    $stmt->store_result();
    if ($stmt->num_rows > 0) {
        $_SESSION['message'] = "⚠️ Email already registered.";
        header("Location: register.php");
        exit();
    }
    // Hash the password and insert user
    $hashedPassword = password_hash($password, PASSWORD_DEFAULT);
    $stmt = $conn->prepare("INSERT INTO users (name, email, password, role) VALUES (?, ?, ?, ?)");
    $stmt->bind_param("ssss", $name, $email, $hashedPassword, $role);
    if ($stmt->execute()) {
        $_SESSION['message'] = "✅ Registration successful! Please log in.";
        header("Location: login.php");
        exit();
    } else {
        $_SESSION['message'] = "❌ Something went wrong. Try again.";
        header("Location: register.php");
        exit();
    }
} else {
    header("Location: register.php");
    exit();
}
?>
