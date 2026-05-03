<?php  
session_start();  
include 'connect.php';

error_reporting(E_ALL);
ini_set('display_errors', 1);

if ($_SERVER['REQUEST_METHOD'] == 'POST') {   

    $email = trim($_POST['email']);  
    $password = md5(trim($_POST['password']));  
    $sql = "SELECT * FROM users WHERE email='$email' AND password='$password'";  
    $result = $conn->query($sql);  
    if ($result && $result->num_rows === 1) {  
        $user = $result->fetch_assoc();  

        $_SESSION['email']   = $user['email'];  
        $_SESSION['name']    = $user['name'];  
        $_SESSION['role']    = $user['role']; 
        $_SESSION['user_id'] = $user['user_id']; // or user_id (check DB!)

        if ($user['role'] === 'student') {  
            header("Location: student_dashboard.php");  
        } elseif ($user['role'] === 'admin')  {  
            header("Location: admin_dashboard.php");  
        }  
        exit();  

    } else {  
        echo "<script>alert('Invalid credentials'); 
window.location.href='login.php';</script>";  
    } 
}
?>