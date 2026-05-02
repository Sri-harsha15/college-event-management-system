<?php 
session_start(); 
include 'connect.php'; // ✅ Database connection 
if ($_SERVER['REQUEST_METHOD'] == 'POST') { 
    $email = trim($_POST['email']); 
    $password = md5(trim($_POST['password'])); 
    $role = $_POST['role']; 
    $sql = "SELECT * FROM users WHERE email='$email' AND password='$password' AND role='$role'"; 
    $result = $conn->query($sql); 
    if ($result && $result->num_rows === 1) { 
        $user = $result->fetch_assoc(); 
        // ✅ Store data in session 
        $_SESSION['email'] = $user['email']; 
        $_SESSION['name'] = $user['name'];  // <-- This line is important! 
        $_SESSION['role'] = $user['role'];
        $_SESSION['user_id'] = $user['user_id'];  // ✅ save actual user ID (like 1 or 2)
        // ✅ Redirect to correct dashboard 
        if ($role === 'student') { 
            header("Location: student_dashboard.php"); 
        } elseif ($role === 'admin') { 
            header("Location: admin_dashboard.php"); 
        } 
        exit(); 
    } else { 
        echo "<script>alert('Invalid credentials'); window.location.href='login.php';</script>"; 
    } 
} 
?> 