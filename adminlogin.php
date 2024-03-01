<?php
// Start the session
session_start();

// Include the database configuration
include 'config.php';

// Initialize variables for error messages
$emailErr = $passwordErr = "";
$email = $password = "";

// Check if the form is submitted
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Validate input data
    if (empty($_POST["email"])) {
        $emailErr = "Email is required";
    } else {
        $email = test_input($_POST["email"]);
    }

    if (empty($_POST["password"])) {
        $passwordErr = "Password is required";
    } else {
        $password = test_input($_POST["password"]);
    }

    // Check if there are no errors, verify user credentials
    if (empty($emailErr) && empty($passwordErr)) {
        // Verify user credentials
        $sql_admin = "SELECT * FROM Admin WHERE email='$email'";
        $result_admin = $conn->query($sql_admin);
        if ($result_admin->num_rows == 1) {
            $admin_row = $result_admin->fetch_assoc();
            if ($password === $admin_row['password']) {
                // Authentication successful, create session
                $_SESSION['admin_id'] = $admin_row['id'];
                header("Location: adminhomepage.php");
                exit();
            } else {
                $passwordErr = "Incorrect password";
            }
        } else {
            $emailErr = "Admin email not found";
        }
    }
}

// Function to sanitize input data
function test_input($data) {
    $data = trim($data);
    $data = stripslashes($data);
    $data = htmlspecialchars($data);
    return $data;
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Login</title>
</head>
<body>
    <h2>Admin Login</h2>
    <form method="post" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]);?>">
        Email: <input type="text" name="email" value="<?php echo $email;?>">
        <span class="error"><?php echo $emailErr;?></span>
        <br><br>
        Password: <input type="password" name="password" value="<?php echo $password;?>">
        <span class="error"><?php echo $passwordErr;?></span>
        <br><br>
        <input type="submit" name="submit" value="Login">
    </form>
</body>
</html>
