<?php
// Start session
session_start();

// Check if the user is logged in
if (!isset($_SESSION['username']) || empty($_SESSION['username'])) {
  header("Location: login.php");
  exit;
}
?>

<?php include 'header.php'; ?>

<div class="container" style="display: flex; flex: 1; padding-top: 1rem; background: linear-gradient(135deg, #b2e0e6, #a8c0d9); font-family: 'Poppins', sans-serif;">
  <?php include 'leftpanel.php'; ?>

  <!-- Main Content Area -->
  <div class="main-content" style="flex: 1; padding: 2rem; background-color: #ffffff; margin-left: 1rem; border-radius: 20px; box-shadow: 0px 15px 40px rgba(0, 0, 0, 0.1);">

    <style>
      /* General Page Styling */
      body {
        font-family: 'Poppins', sans-serif;
        background: linear-gradient(135deg, #b2e0e6, #a8c0d9);
        margin: 0;
        padding: 0;
        color: #333;
      }

      /* Book form styling */
      .book-form {
        background-color: #f9fbfd;
        padding: 2rem;
        border-radius: 12px;
        box-shadow: 0 10px 30px rgba(0, 0, 0, 0.08);
        max-width: 600px;
        margin: auto;
        transition: all 0.3s ease;
      }

      .book-form h2 {
        text-align: center;
        color: #6f8c8f;
        margin-bottom: 1rem;
        font-weight: bold;
        font-size: 2.5rem;
      }

      .book-form label {
        display: block;
        font-weight: bold;
        margin-bottom: 0.5rem;
        color: #587c7f;
      }

      .book-form input[type="text"],
      .book-form input[type="number"],
      .book-form select {
        width: 100%;
        padding: 1rem;
        margin-bottom: 1.2rem;
        border: 1px solid #b5d1d4;
        border-radius: 8px;
        font-size: 1rem;
        background-color: #f3f8f9;
        color: #4e5b5b;
      }

      .book-form button {
        width: 100%;
        padding: 1rem;
        background-color: #6f8c8f;
        color: white;
        border: none;
        border-radius: 8px;
        font-size: 1.1rem;
        cursor: pointer;
        transition: background-color 0.3s ease, transform 0.3s ease;
      }

      .book-form button:hover {
        background-color: #4a6e6b;
        transform: translateY(-5px);
      }

      .book-form button:active {
        transform: translateY(0);
      }
    </style>

    <script>
      function checkout(name) {
        document.getElementById("studentForm").submit_hidden.value = "submit";
        document.getElementById("studentForm").submit();
      }

      function handleTabKey(event, name) {
        if (event.key === "Tab") {
          event.preventDefault(); // Prevent default Tab behavior
          document.getElementById("studentForm").submit();
        }
      }
    </script>

    <body>
      <?php
      // Form submission handling
      if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['submit_hidden'])) {
        // Database connection
        $conn = new mysqli("localhost", "root", "", "lms");

        // Check connection
        if ($conn->connect_error) {
          die("Connection failed: " . $conn->connect_error);
        }

        // Retrieve and sanitize form data
        $student_id = $conn->real_escape_string($_POST['student_id']);
        $first_name = $conn->real_escape_string($_POST['first_name']);
        $middle_name = $conn->real_escape_string($_POST['middle_name']);
        $last_name = $conn->real_escape_string($_POST['last_name']);
        $email = $conn->real_escape_string($_POST['email']);
        $dob = $conn->real_escape_string($_POST['dob']);
        $address = $conn->real_escape_string($_POST['address']);
        $city = $conn->real_escape_string($_POST['city']);
        $state = $conn->real_escape_string($_POST['state']);
        $zip_code = $conn->real_escape_string($_POST['zip_code']);
        $phone = $conn->real_escape_string($_POST['phone']);
        $grade = $conn->real_escape_string($_POST['grade']);


        // Check if student already exists
        $sql = "SELECT * FROM students WHERE student_id = ?";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("s", $student_id);
        $stmt->execute();
        $result = $stmt->get_result();

        if ($result->num_rows > 0) {
          echo "<div class='alert alert-warning'><b>Student already exists with ID:</b> " . $student_id . "</div>";
        } else {
          // Insert student data into the database
          $sql = "INSERT INTO students (student_id, first_name, middle_name, last_name, email, dob, address, city, state, zipcode, phone,  grade) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)";
          $stmt = $conn->prepare($sql);
          $stmt->bind_param("ssssssssssss", $student_id, $first_name, $middle_name, $last_name, $email, $dob, $address, $city, $state, $zip_code, $phone,  $grade);

          if ($stmt->execute()) {
            echo "<div class='alert alert-success'><b>Student has been added successfully!</b></div>";
          } else {
            echo "<div class='alert alert-danger'><b>Error during student addition:</b> " . $stmt->error . "</div>";
          }
        }

        $stmt->close();
        $conn->close();
      }
      ?>

      <!-- Student Form Container -->
      <div class="book-form">
        <h2>Add Student Details</h2>
        <form id="studentForm" action="student_list.php" method="POST">
          <label for="student_id">Student Id</label>
          <input type="text" id="student_id" name="student_id" placeholder="Enter your Student Id" value="<?= $student_id ?? '' ?>" onkeydown="handleTabKey(event, 'student')">

          <label for="first_name">First Name</label>
          <input type="text" id="first_name" name="first_name" placeholder="Enter First Name" value="<?= $first_name ?? '' ?>">

          <label for="middle_name">Middle Name</label>
          <input type="text" id="middle_name" name="middle_name" placeholder="Enter Middle Name" value="<?= $middle_name ?? '' ?>">

          <label for="last_name">Last Name</label>
          <input type="text" id="last_name" name="last_name" placeholder="Enter Last Name" value="<?= $last_name ?? '' ?>">

          <label for="grade">Grade</label>
          <input type="text" id="grade" name="grade" placeholder="Enter Grade" value="<?= $grade ?? '' ?>">

          <input type="hidden" id="submit_hidden" name="submit_hidden">

          <button type="button" value="submit" onclick="javascript:checkout('submit')">Submit</button>
        </form>
      </div>
    </body>
  </div>
</div>

<?php include 'footer.php'; ?>
