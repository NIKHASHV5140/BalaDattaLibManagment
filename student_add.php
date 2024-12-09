<?php
// Start session
session_start();

// Check if the user is logged in
if (!isset($_SESSION['username']) || empty($_SESSION['username'])) {
    header("Location: login.php");
    exit;
}

// Database connection
$servername = "localhost";
$username = "root";
$password = "";
$database = "lms";

$conn = new mysqli($servername, $username, $password, $database);

// Check connection
if ($conn->connect_error) {
    die("Database connection failed: " . $conn->connect_error);
}

// Initialize message variable
$message = "";

// Form submission handling
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $student_id = $conn->real_escape_string(trim($_POST['student_id']));
    $first_name = $conn->real_escape_string(trim($_POST['first_name']));
    $middle_name = $conn->real_escape_string(trim($_POST['middle_name']));
    $last_name = $conn->real_escape_string(trim($_POST['last_name']));
    $dob = $conn->real_escape_string(trim($_POST['dob']));
    $grade = $conn->real_escape_string(trim($_POST['grade']));
    $address = $conn->real_escape_string(trim($_POST['address']));
    $city = $conn->real_escape_string(trim($_POST['city']));
    $state = $conn->real_escape_string(trim($_POST['state']));
    $zipcode = $conn->real_escape_string(trim($_POST['zipcode']));
    $phone = $conn->real_escape_string(trim($_POST['phone']));
    $email = $conn->real_escape_string(trim($_POST['email']));

    // Check if student already exists
    $check_query = "SELECT * FROM students WHERE student_id = '$student_id'";
    $result = $conn->query($check_query);

    if ($result->num_rows > 0) {
        $message = "Student ID already exists!";
    } else {
        // Insert student data
        $insert_query = "INSERT INTO students (student_id, first_name, middle_name, last_name, dob, grade, address, city, state, zip_code, phone, email) 
                         VALUES ('$student_id', '$first_name', '$middle_name', '$last_name', '$dob', '$grade', '$address', '$city', '$state', '$zipcode', '$phone', '$email')";

        if ($conn->query($insert_query) === TRUE) {
            $message = "Student added successfully!";
        } else {
            $message = "Error: " . $conn->error;
        }
    }
}
?>

<?php include 'header.php'; ?>

<div class="container" style="display: flex; flex: 1; padding-top: 2rem; background-color: #f8f9fa; font-family: 'Arial', sans-serif; position: relative;"> <!-- Add position: relative to position button -->

    <?php include 'leftpanel.php'; ?>

    <!-- Button to navigate to another page -->
    <a href="excelupload_frontend.php" class="btn btn-primary" style="position: absolute; bottom: 1230px; right: 20px; padding: 10px 15px; font-size: 16px; background-color: #28a745; color: white; text-decoration: none; border-radius: 5px;">
        Bulk Upload
    </a>

    <!-- Main Content Area -->
    <div class="main-content" style="flex: 1; padding: 2rem; background-color: #ffffff; margin-left: 2rem; border-radius: 8px; border: 1px solid #ddd;">

        <style>
            /* Student Form Styles */
            .student-form {
                background-color: #ffffff;
                padding: 2rem;
                border-radius: 8px;
                border: 1px solid #ddd;
                max-width: 600px;
                margin: auto;
            }

            .student-form h2 {
                text-align: center;
                color: #333;
                margin-bottom: 1.5rem;
                font-size: 1.5rem;
                font-weight: bold;
            }

            .student-form label {
                display: block;
                font-weight: bold;
                margin-bottom: 0.5rem;
                color: #555;
            }

            .student-form input[type="text"],
            .student-form input[type="number"],
            .student-form input[type="email"],
            .student-form input[type="date"],
            .student-form select {
                width: 100%;
                padding: 0.8rem;
                margin-bottom: 1rem;
                border: 1px solid #ccc;
                border-radius: 4px;
                font-size: 1rem;
                background: #f9f9f9;
            }

            .student-form button {
                width: 100%;
                padding: 0.8rem;
                background-color: #007bff;
                color: white;
                border: none;
                border-radius: 4px;
                font-size: 1rem;
                cursor: pointer;
            }

            .student-form button:hover {
                background-color: #0056b3;
            }
        </style>

        <div class="student-form">
            <h2>Student Registration</h2>
            <?php if (!empty($message)): ?>
                <p style="color: green; font-weight: bold; text-align: center;"><?php echo $message; ?></p>
            <?php endif; ?>
            <form action="" method="POST">
                <label for="student_id">Student ID</label>
                <input type="text" id="student_id" name="student_id" placeholder="Enter Student ID" required>

                <label for="first_name">First Name</label>
                <input type="text" id="first_name" name="first_name" placeholder="Enter First Name" required>

                <label for="middle_name">Middle Name</label>
                <input type="text" id="middle_name" name="middle_name" placeholder="Enter Middle Name">

                <label for="last_name">Last Name</label>
                <input type="text" id="last_name" name="last_name" placeholder="Enter Last Name" required>

                <label for="dob">Date of Birth</label>
                <input type="date" id="dob" name="dob" required>

                <label for="grade">Grade</label>
                <select id="grade" name="grade" required>
                    <option value="" disabled selected>Select Grade</option>
                    <option value="0">Level 0</option>
                    <option value="1">Level 1</option>
                    <option value="2">Level 2</option>
                    <option value="3">Level 3</option>
                    <option value="4">Level 4</option>
                    <option value="5">Level 5</option>
                    <option value="6">Level 6</option>
                </select>

                <label for="address">Address</label>
                <input type="text" id="address" name="address" placeholder="Enter Address" required>

                <label for="city">City</label>
                <input type="text" id="city" name="city" placeholder="Enter City" required>

                <label for="state">State</label>
                <input type="text" id="state" name="state" placeholder="Enter State" required>

                <label for="zipcode">Zip Code</label>
                <input type="text" id="zipcode" name="zipcode" placeholder="Enter Zip Code">

                <label for="phone">Phone</label>
                <input type="text" id="phone" name="phone" placeholder="Enter Phone Number" required>

                <label for="email">Email</label>
                <input type="email" id="email" name="email" placeholder="Enter Email" required>

                <button type="submit">Submit</button>
            </form>
        </div>

    </div>
</div>

<?php include 'footer.php'; ?>
