<!-- chaitanya -->
<?php
include 'config.php';
session_start();

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
        $sql = "SELECT * FROM User WHERE email='$email'";
        $result = $conn->query($sql);
        if ($result->num_rows > 0) {
            $row = $result->fetch_assoc();
            // Debugging: Check if hashed password stored in database is correct
            echo "Hashed Password from DB: " . $row['password'] . "<br>";
            echo "Plain Password: " . $password . "<br>";
            if (password_verify($password, $row['password'])) {
                // Authentication successful, create session
                $_SESSION['user_id'] = $row['id'];
                header("Location: homepage.php");
                exit();
            } else {
                $passwordErr = "Incorrect password";
            }
        } else {
            $emailErr = "Email not found";
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
<!-- chaitanya -->

<!-- nikhil -->
<!DOCTYPE html>
<html lang="en">
    <head>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login</title>
    <link rel="stylesheet" href="login.css">
</head>
<body>
    <div class="container">
        <h2>Login</h2>
        <form method="post" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]);?>">
            Email: <input type="text" name="email" value="<?php echo $email;?>">
            <span class="error"><?php echo $emailErr;?></span>
            <br><br>
            Password: <input type="password" name="password" value="<?php echo $password;?>">
            <span class="error"><?php echo $passwordErr;?></span>
            <br><br>
            <p>Haven't you registered yet? <a href="signup.php">Register</a></p><br/>
            <input type="submit" name="submit" value="Login">
        </form>
    </div>
</body>
</html>
<!-- nikhil -->