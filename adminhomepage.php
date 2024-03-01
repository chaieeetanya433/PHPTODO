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

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Homepage</title>
</head>
<body>
    <h2>Welcome to the Admin Homepage</h2>
    
    <!-- List of users -->
    <h3>User List:</h3>
    <ul>
        <?php foreach ($users as $user): ?>
            <li>
                <?php echo $user['name']; ?> (<?php echo $user['email']; ?>)
                <form method="post" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]);?>">
                    <input type="hidden" name="user_id" value="<?php echo $user['id']; ?>">
                    <button type="submit" name="delete_user">Delete</button>
                </form>
            </li>
        <?php endforeach; ?>
    </ul>

    <!-- Logout button -->
    <form method="post" action="logout.php">
        <button type="submit" name="logout">Logout</button>
    </form>
</body>
</html>