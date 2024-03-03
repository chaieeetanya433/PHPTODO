<?php
// Start the session
// jigyasa
session_start();

// Check if user is not logged in, redirect to login page
if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit();
}

// Include the database configuration
include 'config.php';

// Initialize an empty array to store tasks
$tasks = array();

// Fetch tasks from the Task table for the logged-in user
$user_id = $_SESSION['user_id'];
$sql = "SELECT * FROM Task WHERE user_id='$user_id'";
$result = $conn->query($sql);
if ($result->num_rows > 0) {
    // Loop through each row and store task details in the tasks array
    while ($row = $result->fetch_assoc()) {
        $tasks[] = $row;
    }
}

// Function to handle adding a new task
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['add_task'])) {
    $task_name = $_POST['task_name'];
    
    // Insert the new task into the Task table
    $sql_add_task = "INSERT INTO Task (name, user_id) VALUES ('$task_name', '$user_id')";
    if ($conn->query($sql_add_task) === TRUE) {
        // Redirect to refresh the page and prevent duplicate form submissions
        header("Location: homepage.php");
        exit();
    } else {
        echo "Error: " . $sql_add_task . "<br>" . $conn->error;
    }
}
// jigyasa

// chaitanya
// Function to handle deleting a task
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['delete_task'])) {
    $task_id = $_POST['task_id'];

    // Delete the task from the Task table
    $sql_delete_task = "DELETE FROM Task WHERE id='$task_id' AND user_id='$user_id'";
    if ($conn->query($sql_delete_task) === TRUE) {
        // Redirect to refresh the page after deleting the task
        header("Location: homepage.php");
        exit();
    } else {
        echo "Error: " . $sql_delete_task . "<br>" . $conn->error;
    }
}

// Function to handle updating a task name
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['update_task'])) {
    $task_id = $_POST['task_id'];
    $new_task_name = $_POST['new_task_name'];
    
    // Update the task name in the Task table
    $sql_update_task = "UPDATE Task SET name='$new_task_name' WHERE id='$task_id' AND user_id='$user_id'";
    if ($conn->query($sql_update_task) === TRUE) {
        // Redirect to refresh the page after updating the task
        header("Location: homepage.php");
        exit();
    } else {
        echo "Error: " . $sql_update_task . "<br>" . $conn->error;
    }
}
// chaitanya
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Homepage</title>
    <style>
        /* Paste the provided CSS styles here */
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

        form {
            margin-bottom: 20px;
        }

        input[type="text"] {
            width: calc(100% - 100px);
            padding: 10px;
            margin-right: 10px;
            border: 1px solid #ced4da;
            border-radius: 4px;
            box-sizing: border-box;
        }

        button {
            padding: 10px 20px;
            margin: 6px;
            background-color: #007bff;
            color: white;
            border: none;
            border-radius: 4px;
            cursor: pointer;
            transition: background-color 0.3s ease;
        }

        button:hover {
            background-color: #0056b3;
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

        .task {
            display: flex;
            justify-content: space-between;
            align-items: center;
        }

        .task-name {
            flex-grow: 1;
            margin-right: 10px;
        }

        .task-actions {
            display: flex;
            align-items: center;
        }

        .edit-form {
            display: none;
        }

        .logout-form {
            margin-top: 20px;
        }

        .logout-form button {
            background-color: #dc3545;
        }

        .logout-form button:hover {
            background-color: #c82333;
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
        <h2>Welcome to the Homepage</h2>
        <p>Logged in as User ID: <?php echo $_SESSION['user_id']; ?></p>
        
        <!-- jigyasa -->
        <!-- Form to add a new task -->
    <form class="task-actions" method="post" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]);?>">
        <input type="text" name="task_name" placeholder="Enter task name" required>
        <button type="submit" name="add_task">Add Task</button>
    </form>

    <!-- List of tasks -->
    <h3>Your Tasks:</h3>
    <ul>
        <?php foreach ($tasks as $task): ?>
            <li>
                <span id="task_<?php echo $task['id']; ?>">
                    <?php echo $task['name']; ?>
                </span>
                <!-- jigyasa -->
                <!-- chaitanya -->
                <form class="edit-form" id="edit_form_<?php echo $task['id']; ?>" style="display: none;" method="post" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]);?>">
                    <input type="text" name="new_task_name" value="<?php echo $task['name']; ?>">
                    <input type="hidden" name="task_id" value="<?php echo $task['id']; ?>">
                    <button type="submit" name="update_task">Update</button>
                </form>
                <!-- <div class="task-actions"> -->
                    <button onclick="toggleEditForm('<?php echo $task['id']; ?>')">Edit</button>
                    <form class="task-actions" method="post" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]);?>">
                        <input type="hidden" name="task_id" value="<?php echo $task['id']; ?>">
                        <button type="submit" name="delete_task">Delete</button>
                    </form>
                <!-- </div> -->
                <!-- chaitanya -->
            </li>
            <?php endforeach; ?>
        </ul>
        
        
        <!-- Logout button -->
        <form method="post" action="logout.php" class="logout-form">
            <button type="submit" name="logout">Logout</button>
        </form>
    </div>
        <!-- shruti -->
        <script>
            function toggleEditForm(taskId) {
                var taskSpan = document.getElementById('task_' + taskId);
                var editForm = document.getElementById('edit_form_' + taskId);
                
                if (taskSpan.style.display === 'none') {
                    taskSpan.style.display = 'inline';
                    editForm.style.display = 'none';
                } else {
                    taskSpan.style.display = 'none';
                    editForm.style.display = 'inline';
                }
            }
            </script>
<!-- shruti -->
</body>
</html>