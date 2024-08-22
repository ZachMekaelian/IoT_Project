<?php
//display any errors at start
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

// Database connection settings
$dbPath = "IoT_Data.db"; // Path to your SQLite database file

// Create a new SQLite database connection
$conn = new SQLite3($dbPath);

// Check if the form is submitted
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Retrieve form data
    $username = $_POST['username'];
    $password = $_POST['password'];

    // Prepare and execute the SQL statement to retrieve the user from the database
    $stmt = $conn->prepare("SELECT * FROM Users WHERE Username = :username");
    $stmt->bindValue(':username', $username, SQLITE3_TEXT);
    $result = $stmt->execute();
    $row = $result->fetchArray();

    // Verify the password
    if ($row && password_verify($password, $row['Password'])) {
        // Password is correct, redirect to the dashboard page
        header("Location: dashboard.php");
        exit();
    } else {
        // Password is incorrect, redirect back to the login page with an error message
        header("Location: index.php?error=Invalid username or password");
        exit();
    }
}

// Close the database connection
$conn->close();
?>

<!DOCTYPE html>
<html>
<head>
    <title>Login Dashboard</title>
    <style>
        body {
            display: flex;
            justify-content: center;
            align-items: center;
            height: 100vh;
        }
        .login-form {
            width: 300px;
            padding: 20px;
            border: 1px solid #ccc;
            border-radius: 5px;
        }
        .login-form input[type="text"],
        .login-form input[type="password"] {
            width: 90%;
            padding: 10px;
            margin-bottom: 10px;
            border: 1px solid #ccc;
            border-radius: 3px;
        }
        .login-form button[type="submit"] {
            width: 98%;
            padding: 10px;
            background-color: #4CAF50;
            color: #fff;
            border: none;
            border-radius: 3px;
            cursor: pointer;
        }
        .login-form button[type="submit"]:hover {
            background-color: #45a049;
        }
        .login-form .login-register {
            display: flex;
            justify-content: space-between;
            align-items: center;
        }
        .login-form .register-link {
            font-size: 10px;
            transition: color 0.3s;
            cursor: pointer;
            text-decoration: none;
        }
        .login-form .register-link:hover {
            color: #45a049;
        }
    </style>
</head>
<body>
    <div class="login-form">
        <div class="login-register">
            <h2>Login</h2>
            <a class="register-link" href="register.php">Register account</a>
        </div>
        <form action="index.php" method="POST">
            <?php if (isset($_GET['error'])) { ?>
                <p style="color: red;"><?php echo $_GET['error']; ?></p>
            <?php } ?>
            <input type="text" name="username" placeholder="Username" required>
            <input type="password" name="password" placeholder="Password" required>
            <button type="submit">Login</button>
        </form>
    </div>
</body>
</html>
