<?php
session_start(); // Start session (if not already started)

// Database connection parameters
$servername = "localhost";
$username = "root";
$password = "";
$database = "touchweb";

// Create connection
$conn = new mysqli($servername, $username, $password, $database);

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Function to validate form data
function validateFormData($data) {
    // Perform additional validation if needed
    return htmlspecialchars(stripslashes(trim($data)));
}

// Retrieve form data
$username = validateFormData($_POST['username']);
$password = validateFormData($_POST['password']);

// Validate form data
if(empty($username) || empty($password)) {
    echo "Username and password are required.";
    exit();
}

// Hash the password for comparison with the hashed password stored in the database

// Prepare SQL statement to select user based on username and password
$sql = "SELECT * FROM users WHERE username = ? AND password = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("ss", $username, $password);

// Execute the prepared statement
$stmt->execute();

// Get the result
$result = $stmt->get_result();

// Check if the user exists
if ($result->num_rows == 1) {
    // User exists, retrieve user details
    $user = $result->fetch_assoc();

    // Store user information in session for later use
    $_SESSION['user'] = $user;

    // Redirect user based on role
    switch ($user['role']) {
        case 'admin':
            header("Location: admindash.html");
            break;
        case 'moderator':
            header("Location: moddashboard.html");
            break;
        case 'user':
            // If the user's role is "user", retrieve the student's major from the "students" table
            $student_sql = "SELECT major FROM students WHERE username = ?";
            $student_stmt = $conn->prepare($student_sql);
            $student_stmt->bind_param("s", $user['username']);
            $student_stmt->execute();
            $student_result = $student_stmt->get_result();
            $student_data = $student_result->fetch_assoc();

            // Store student data in session for later use
            $_SESSION['student_data'] = $student_data;

            // Redirect to the dashboard
            header("Location: dashboard.php");
            break;
        default:
            echo "Invalid role.";
            break;
    }
} else {
    // User does not exist or credentials are incorrect
    echo "Invalid username or password.";
}

// Close statements and connection
$stmt->close();
$conn->close();
?>
