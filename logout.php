<?php
session_start();
// Clear session data
session_unset();
session_destroy();
// Redirect to login with a message
header("Location: login.php?message=Are You Sure Want to Logout");
exit();
?>
