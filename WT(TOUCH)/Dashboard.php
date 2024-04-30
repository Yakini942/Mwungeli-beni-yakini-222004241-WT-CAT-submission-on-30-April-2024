<?php
session_start(); // Start session (if not already started)

// Check if the user is logged in
if (!isset($_SESSION['user'])) {
    // Redirect user to login page if not logged in
    header("Location: login.php");
    exit();
}

// Retrieve user information from session
$user = $_SESSION['user'];

// Retrieve student data from session if available
$student_data = isset($_SESSION['student_data']) ? $_SESSION['student_data'] : null;

// Retrieve student major from session if available
$student_major = isset($_SESSION['student_major']) ? $_SESSION['student_major'] : null;

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

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="style1.css">
    <link rel="stylesheet" href="new.css">
    <title>Learning Platform Dashboard</title>
</head>
<body>
    <header>
        <div class="topnav">
            <a href="Dashboard.html" class="active">Dashboard</a>
            <a href="#about">About</a>
            <a href="#contact">Contact</a>
            <a href="profile.html" class="split"><i class="fa fa-fw fa-user"></i>Profile</a>
        </div>
    </header>
    <h1>
    <div class="welcome" style="padding-top:40px;">
      <?php
        // Display welcome message with user's username and major
        echo "Welcome, " . $user['username'] . "  majoring in: " . ($student_data ? $student_data['major'] : "");
        ?>
    </div>
    My Learning Dashboard</h1>
    <main>

        <section class="upcoming-events">
            <h2>Upcoming Events</h2>
            <ul>
                <li>
                    <span class="event-date">May 6</span>
                    <a href="https://short.url/at/goTVD">MySQL - Replication Recording</a>
                </li>
                <!-- Add more event items here -->
            </ul>
            <a href="#" class="btn">view all</a>
        </section>

        <section class="reports">
            <h2>Reports</h2>
            <a href="#">1 report</a>
        </section>

        <section class="peer-learning">
            <div class="course-section">
                <?php
                // Function to fetch courses based on major
                function getCoursesByMajor($conn, $major) {
                    // Your SQL query to fetch courses based on major
                    $sql = "SELECT * FROM courses WHERE major = ?";

                    // Prepare the SQL statement
                    $stmt = $conn->prepare($sql);

                    // Bind the parameter
                    $stmt->bind_param("s", $major);

                    // Execute the query
                    $stmt->execute();

                    // Get the result
                    $result = $stmt->get_result();

                    // Fetch courses as an associative array
                    $courses = $result->fetch_all(MYSQLI_ASSOC);

                    // Close the statement
                    $stmt->close();

                    // Return the courses
                    return $courses;
                }

                // Example usage:
                $courses = getCoursesByMajor($conn, $student_major); // Get courses for the specified major

                // Display the courses
                if (!empty($courses)) {
                    echo "<h2>Courses for $student_major</h2>";
                    echo "<ul>";
                    foreach ($courses as $course) {
                        echo "<li>{$course['course_code']} - {$course['course_name']}</li>";
                    }
                    echo "</ul>";
                } else {
                    echo "No courses found for $student_major";
                }
                ?>
            </div>
            <a href="#" class="btn">view all</a>
        </section>

        <section class="scores">
            <h2>Scores</h2>
            <table>
                <thead>
                <th>In progress</th>
                </thead>
                <tbody>
                <tr>
                    <td>course</td>
                    <td>marks</td>
                </tr>
                <tr>
                    <td>Python Hello, World</td>
                    <td>86.00%</td>
                </tr>
                <!-- Add more month scores here -->
                </tbody>
            </table>
            <a href="#" class="btn">view all</a>
        </section>

    </main>
</body>
</html>
