<!-- aryaman -->
<?php
// Start the session
session_start();

// Check if admin is not logged in, redirect to admin login page
if (!isset($_SESSION['admin_id'])) {
    header("Location: adminlogin.php");
    exit();
}

// Include the database configuration
include 'config.php';

// Fetch all users from the User table
$sql_users = "SELECT * FROM User";
$result_users = $conn->query($sql_users);
$users = array();
if ($result_users->num_rows > 0) {
    while ($row = $result_users->fetch_assoc()) {
        $users[] = $row;
    }
}

// Function to handle deleting a user
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['delete_user'])) {
    $user_id = $_POST['user_id'];
    
    // Delete the user from the User table
    $sql_delete_user = "DELETE FROM User WHERE id='$user_id'";
    if ($conn->query($sql_delete_user) === TRUE) {
        // Redirect to refresh the page after deleting the user
        header("Location: adminhomepage.php");
        exit();
    } else {
        echo "Error: " . $sql_delete_user . "<br>" . $conn->error;
    }
}
?>
<!-- aryaman -->


<!-- shruti -->
<!DOCTYPE html>
<html lang="en">
    <head>
        <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Homepage</title>
    <style>
        body {
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            background-color: #f8f9fa;
            color: #333;
            margin: 0;
            padding: 0;
        }

        .container {
            max-width: 800px;
            margin: 20px auto;
            padding: 20px;
            background-color: #fff;
            border-radius: 8px;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
        }

        h2 {
            margin-top: 0;
            color: #007bff;
        }

        ul {
            list-style-type: none;
            padding: 0;
        }

        li {
            margin-bottom: 10px;
            padding: 10px;
            background-color: #f1f1f1;
            border-radius: 4px;
        }

        .user-info {
            display: flex;
            justify-content: space-between;
            align-items: center;
        }

        .user-actions button {
            padding: 5px 10px;
            background-color: #dc3545;
            color: white;
            border: none;
            border-radius: 4px;
            transition: background-color 0.3s ease;
        }

        .user-actions button:hover {
            background-color: #c82333;
        }

        button:hover{
            cursor: pointer;
        }

        .logout-form {
            margin-top: 20px;
        }

        .logout-form button {
            background-color: #007bff;
            color: white;
        }

        .logout-form button:hover {
            background-color: #0056b3;
        }

        .heading {
            margin: 20px 10px;
        }
        .heading img {
            border-radius: 50%;
            height: 80px;
            width: 80px;
            border: 2px solid #007bff;
        }
        .heading img:hover {
            border-color: #0056b3;
        }
        .heading h4 {
            font-weight: bold;
            font-size: 24px;
            color: #007bff;
            top: 0%;
            left: 8%;
        }
        .heading h4:hover {
            color: #0056b3;
        }
    </style>
</head>
<body>
<div class="heading">
        <img src="./logodark.jpg" alt="Listify Logo">
        <h4>LISTIFY</h4>
    </div>
    <div class="container">

        <h2>Welcome to the Admin Homepage</h2>
        
        <!-- List of users -->
        <h3>User List:</h3>
        <ul>
            <?php foreach ($users as $user): ?>
                <li>
                    <div class="user-info">
                        <?php echo $user['name']; ?> (<?php echo $user['email']; ?>)
                    </div>
                <form method="post" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]);?>">
                    <input type="hidden" name="user_id" value="<?php echo $user['id']; ?>">
                    <button type="submit" name="delete_user" class="user-actions">Delete</button>
                </form>
            </li>
            <?php endforeach; ?>
        </ul>
        
        <!-- Logout button -->
        <form method="post" action="logout.php" class="logout-form">
            <button type="submit" name="logout">Logout</button>
        </form>
    </div>
    </body>
    </html>
    <!-- shruti -->