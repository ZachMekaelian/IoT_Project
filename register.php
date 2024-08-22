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
    $firstName = $_POST['first_name'];
    $lastName = $_POST['last_name'];
    $username = $_POST['username'];
    $password = $_POST['password'];
    $confirmPassword = $_POST['confirm_password'];

    // Validate form data
    if (empty($firstName) || empty($lastName) || empty($username) || empty($password) || empty($confirmPassword)) {
        header("Location: register.php?error=Please fill in all fields");
        exit();
    } elseif ($password !== $confirmPassword) {
        header("Location: register.php?error=Passwords do not match, please try again.");
        exit();
    }

    // Hash the password
    $hashedPassword = password_hash($password, PASSWORD_DEFAULT);

    // Prepare and execute the SQL statement to insert the user into the database
    $stmt = $conn->prepare("INSERT INTO Users (First_Name, Last_Name, Username, Password) VALUES (?, ?, ?, ?)");
    $stmt->bindValue(1, $firstName);
    $stmt->bindValue(2, $lastName);
    $stmt->bindValue(3, $username);
    $stmt->bindValue(4, $hashedPassword);
    $stmt->execute();

    // Redirect to the login page with a success message
    header("Location: index.php?message=Registration successful. Please login.");
    exit();
}

// Close the database connection
$conn->close();
?>

<!DOCTYPE html>
<html>
<head>
    <title>Registration</title>
    <style>
        body {
            display: flex;
            justify-content: center;
            align-items: center;
            height: 100vh;
        }
        .register-form {
            width: 300px;
            padding: 20px;
            border: 1px solid #ccc;
            border-radius: 5px;
        }
        .register-form input[type="text"],
        .register-form input[type="password"] {
            width: 90%;
            padding: 10px;
            margin-bottom: 10px;
            border: 1px solid #ccc;
            border-radius: 3px;
        }
        .register-form button[type="submit"] {
            width: 98%;
            padding: 10px;
            background-color: #4CAF50;
            color: #fff;
            border: none;
            border-radius: 3px;
            cursor: pointer;
        }
        .register-form button[type="submit"]:hover {
            background-color: #45a049;
        }
        .register-form .back-link {
            font-size: 8px;
            transition: color 0.3s;
            cursor: pointer;
            text-decoration: none;
            color: #000000;
        }
        .register-form .back-link:hover {
            color: #4682b4;
        }
        .register-form .error-message {
            color: red;
            font-size: 11px;
        }
    </style>
</head>
<body>
    <div class="register-form">
        <h2>Register</h2>
        <?php if (isset($_GET['error'])) { ?>
            <p class="error-message"><?php echo $_GET['error']; ?></p>
        <?php } ?>
        <form action="register.php" method="POST">
            <input type="text" name="first_name" placeholder="First Name" required>
            <input type="text" name="last_name" placeholder="Last Name" required>
            <input type="text" name="username" placeholder="Username" required>
            <input type="password" name="password" placeholder="Password" required>
            <input type="password" name="confirm_password" placeholder="Confirm Password" required>
            <button type="submit">Register</button>
        </form>
        <a class="back-link" href="index.php">Back to home</a>
    </div>
</body>
</html>
