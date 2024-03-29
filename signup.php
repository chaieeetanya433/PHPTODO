<!-- chaitanya -->
<?php
include 'config.php';

//for error messages
$nameErr = $emailErr = $passwordErr = "";
$name = $email = $password = "";

// Check if the form is submitted
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Validate input data
    if (empty($_POST["name"])) {
        $nameErr = "Name is required";
    } else {
        $name = test_input($_POST["name"]);
        // Check if name only contains letters and whitespace
        if (!preg_match("/^[a-zA-Z ]*$/",$name)) {
            $nameErr = "Only letters and white space allowed";
        }
    }

    if (empty($_POST["email"])) {
        $emailErr = "Email is required";
    } else {
        $email = test_input($_POST["email"]);
        // Check if email address is valid
        if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
            $emailErr = "Invalid email format";
        }
    }

    if (empty($_POST["password"])) {
        $passwordErr = "Password is required";
    } else {
        $password = test_input($_POST["password"]);
    }

    // Check if there are no errors, insert user into database
    if (empty($nameErr) && empty($emailErr) && empty($passwordErr)) {
        // Check if email already exists
        $sql = "SELECT * FROM User WHERE email='$email'";
        $result = $conn->query($sql);
        if ($result->num_rows > 0) {
            $emailErr = "Email already exists";
        } else {
            // Insert user into User table
            $hashed_password = password_hash($password, PASSWORD_DEFAULT); // Hash the password
            $sql = "INSERT INTO User (name, email, password) VALUES ('$name', '$email', '$hashed_password')";
            if ($conn->query($sql) === TRUE) {
                // Registration successful, redirect to login page
                header("Location: login.php");
                exit();
            } else {
                echo "Error: " . $sql . "<br>" . $conn->error;
            }
        }
    }
}

function test_input($data) {
    $data = trim($data);
    $data = stripslashes($data);
    $data = htmlspecialchars($data);
    return $data;
}
?>
<!-- chaitanya -->

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Registration</title>
    <link rel="stylesheet" href="signup.css">
    <style>
        .heading {
            margin: 20px auto;
            text-align: center;
            position: relative;
            animation: floating 2s infinite alternate;
        }
        @keyframes floating {
            0% {
                transform: translateY(0);
            }
            100% {
                transform: translateY(-10px);
            }
        }
        .heading img {
            border-radius: 50%;
            height: 80px;
            width: 80px;
            border: 2px solid #007bff;
            position: relative;
            z-index: 2;
            transition: border-color 0.3s ease-in-out;
        }
        .heading img:hover {
            border-color: #0056b3;
        }
        .heading h4 {
            padding: 10px;
            font-weight: bold;
            font-size: 24px;
            color: #007bff;
            position: absolute;
            top: 70%;
            left: 50%;
            transform: translate(-50%, -50%);
            border-radius: 5px;
            transition: color 0.3s ease-in-out;
            z-index: 3;
        }
        .heading h4:hover {
            color: #0056b3;
        }
        .team {
            font-weight: normal;
            font-size: 18px;
            color: #343a40;
        }
    </style>
</head>
<body>
    <div class="heading">
        <img src="./logodark.jpg" alt="Listify Logo">
        <h4>LISTIFY<span class="team">~ by team semicolons;</span></h4>
    </div>
    <!-- swapnil -->
    <div class="container">
        <h2>Registration</h2>
        <form method="post" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]);?>">
            Name: <input type="text" name="name" value="<?php echo $name;?>" placeholder="Enter name:">
            <span class="error"><?php echo $nameErr;?></span>
            <br><br>
            Email: <input type="text" name="email" value="<?php echo $email;?>" placeholder="Enter email:">
            <span class="error"><?php echo $emailErr;?></span>
            <br><br>
            Password: <input type="password" name="password" value="<?php echo $password;?>" placeholder="Enter password:">
            <span class="error"><?php echo $passwordErr;?></span>
            <br><br>
            <p>Have you already signed up? <a href="login.php">Login</a></p><br/>
            <p>Are you a admin? Login by clicking <a href="adminlogin.php">Login</a></p>
            <input type="submit" name="submit" value="Submit">
        </form>
    </div>
    <!-- swapnil -->
</body>
</html>