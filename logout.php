<!-- nikhil -->
<?php
// Start the session
session_start();

// Unset all session variables
$_SESSION = array();
// <!-- nikhil -->

// swapnil
// Destroy the session
session_destroy();

// Redirect to the login page
header("Location: signup.php");
exit();
// swapnil
?>