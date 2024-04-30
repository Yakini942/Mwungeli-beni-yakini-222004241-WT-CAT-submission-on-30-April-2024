<?php
// Database connection parameters
$host = 'localhost';
$dbname = 'touchweb';
$dbUsername = 'root';
$dbPassword = '';

$conn = new mysqli($host, $dbUsername, $dbPassword, $dbname);

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Prepare SQL statement for inserting into Users table
$sql_registration = "INSERT INTO admin (username, email, full_name, role) VALUES (?, ?, ?, ?)";
$stmt_registration  = $conn->prepare($sql_registration);
$stmt_registration->bind_param("ssss", $username, $email, $full_name, $role);

// Set parameters for Users table
$username = $_POST['username'];
$email = $_POST['email'];
$full_name = $_POST['fullname'];
$role = "moderator";


// Execute INSERT into Users table
$stmt_registration->execute();

// Prepare SQL statement for inserting into Registration table
$sql_user= "INSERT INTO users (username, password, role) VALUES (?, ?, ?)";
$stmt_user= $conn->prepare($sql_user);
$stmt_user->bind_param("sss", $username, $password, $role);

// Set parameters for Registration table
$username = $_POST['username'];
$password = $_POST['password']; // Hash password for security
$role = "moderator"; // Or whatever role you want to assign

// Execute INSERT into Registration table
$stmt_user->execute();

if ($stmt_registration->affected_rows > 0 && $stmt_user->affected_rows > 0) {
    echo "<script> alert('Sign up was a success'); </script>";
    header("Location: signup.html");
    exit();
} else {
    echo "Error: " . $conn->error;
}

// Close statements and connection
$stmt_registration->close();
$stmt_user->close();
$conn->close();
?>
