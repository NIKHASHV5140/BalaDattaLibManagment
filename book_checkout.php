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

<div class="container" style="display: flex; flex: 1; padding-top: 1rem; background-color: #f4f6fc; font-family: 'Poppins', sans-serif;">
  <?php include 'leftpanel.php'; ?>

  <!-- Main Content Area -->
  <div class="main-content" style="flex: 1; padding: 2rem; background-color: #ffffff; margin-left: 1rem; border-radius: 8px; border: 1px solid #ddd; box-shadow: 0px 5px 15px rgba(0, 0, 0, 0.1);">

    <style>
      /* General Page Styling */
      body {
        font-family: 'Poppins', sans-serif;
        background-color: #f4f6fc;
        margin: 0;
        padding: 0;
      }

      a {
        color: #1abc9c;
        text-decoration: none;
      }

      a:hover {
        text-decoration: underline;
      }

      /* Simplified Book Form */
      .book-form {
        background-color: #ffffff;
        padding: 2rem;
        border-radius: 8px;
        border: 1px solid #ddd;
        max-width: 600px;
        margin: auto;
        box-shadow: 0 5px 15px rgba(0, 0, 0, 0.1);
      }

      .book-form h2 {
        text-align: center;
        color: #6a11cb;
        margin-bottom: 1rem;
        font-family: 'Arial', sans-serif;
        font-weight: bold;
        font-size: 2rem;
      }

      .book-form label {
        display: block;
        font-weight: bold;
        margin-bottom: 0.5rem;
        color: #3498db;
        font-size: 1.1rem;
      }

      .book-form input[type="text"],
      .book-form input[type="number"],
      .book-form select {
        width: 100%;
        padding: 1rem;
        margin-bottom: 1rem;
        border: 1px solid #3498db;
        border-radius: 4px;
        font-size: 1rem;
        background-color: #ecf5fb;
        color: #34495e;
        transition: border-color 0.3s ease;
      }

      .book-form input[type="text"]:focus,
      .book-form input[type="number"]:focus,
      .book-form select:focus {
        border-color: #6a11cb;
        outline: none;
      }

      .book-form button {
        width: 100%;
        padding: 1rem;
        background-color: #6a11cb;
        color: white;
        border: none;
        border-radius: 4px;
        font-size: 1.1rem;
        cursor: pointer;
        transition: background-color 0.3s ease;
      }

      .book-form button:hover {
        background-color: #2575fc;
      }

      /* Form Info Panels */
      .student-info, .book-info {
        margin-top: 1rem;
        padding: 1rem;
        background-color: #d7f3ff;
        border-radius: 8px;
        box-shadow: 0 2px 6px rgba(0, 0, 0, 0.1);
        color: #34495e;
        font-size: 1.1rem;
      }

      .divider {
        margin: 1rem 0;
        border-top: 1px solid #3498db;
      }

      /* Alert Messages */
      .alert-success {
        background-color: #2ecc71;
        color: white;
        padding: 1rem;
        border-radius: 8px;
        margin-bottom: 1rem;
        text-align: center;
        font-weight: bold;
      }

      .alert-danger {
        background-color: #e74c3c;
        color: white;
        padding: 1rem;
        border-radius: 8px;
        margin-bottom: 1rem;
        text-align: center;
        font-weight: bold;
      }

      .alert-warning {
        background-color: #f39c12;
        color: white;
        padding: 1rem;
        border-radius: 8px;
        margin-bottom: 1rem;
        text-align: center;
        font-weight: bold;
      }
    </style>

<script>
  // JavaScript function to handle Tab key press
  function checkout(name) {
    document.getElementById("studentForm").submit_hidden.value = "submit"; // Set hidden input value
    document.getElementById("studentForm").submit(); // Submit the form

    // Delay redirection by 2 seconds
    setTimeout(function() {
      window.location.href = "main.php"; // Redirect to main.php after 2 seconds
    }, 2000); // 2000 milliseconds = 2 seconds
  }

  function handleTabKey(event, name) {
    if (event.key === "Tab") {
      event.preventDefault(); // Prevent default Tab behavior
      document.getElementById("studentForm").submit(); // Submit the form on Tab key press
    }
  }
</script>

    <body>
      <?php
      if (($_SERVER["REQUEST_METHOD"] == "POST") && ($_POST['submit_hidden'] != null)) {
        // Database connection
        $conn = new mysqli("localhost", "root", "", "lms");

        // Check connection
        if ($conn->connect_error) {
          die("Connection failed: " . $conn->connect_error);
        }

        // Retrieve and sanitize form data
        $student_id = $conn->real_escape_string($_POST['student_id']);
        $book_id = $conn->real_escape_string($_POST['book_id']);
        $status = "Open";

        // Check if the book is available
        $sql = "SELECT * FROM book_trans WHERE status='Open' and book_id = ? ";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("s", $book_id);
        $stmt->execute();
        $result = $stmt->get_result();

        if ($result->num_rows > 0) {
          echo "<div class='alert alert-danger'><b>Book not available:</b> " . $book_id . "</div>";
        } else {
          $sql = "INSERT INTO book_trans (student_id, book_id, status) VALUES (?, ?, ?)";
          $stmt = $conn->prepare($sql);
          $stmt->bind_param("sss", $student_id, $book_id, $status);

          if ($stmt->execute()) {
            echo "<div class='alert alert-success'><b>Book has been checked out successfully!</b></div>";
          } else {
            echo "<div class='alert alert-danger'><b>Error during checkout:</b> " . $stmt->error . "</div>";
          }
        }

        $stmt->close();
        $conn->close();
      }
      ?>

      <?php
      $student_id = "";
      $first_name = "";
      $middle_name = "";
      $last_name = "";
      $grade = "";

      if (($_SERVER["REQUEST_METHOD"] == "POST") && ($_POST['student_id'] != null)) {
        $conn = new mysqli("localhost", "root", "", "lms");

        if ($conn->connect_error) {
          die("Connection failed: " . $conn->connect_error);
        }

        $student_id = $conn->real_escape_string($_POST['student_id']);

        $sql = "SELECT * FROM students WHERE student_id = ?";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("s", $student_id);
        $stmt->execute();
        $result = $stmt->get_result();

        if ($result->num_rows > 0) {
          $student = $result->fetch_assoc();
          $first_name = $student['first_name'];
          $middle_name = $student['middle_name'];
          $last_name = $student['last_name'];
          $grade = $student['grade'];
        } else {
          echo "<div class='alert alert-warning'><b>No student found with ID:</b> " . $student_id . "</div>";
        }

        $stmt->close();
        $conn->close();
      }
      ?>

      <?php
      $book_id = "";
      $book_name = "";
      $language = "";
      $genre = "";

      if (($_SERVER["REQUEST_METHOD"] == "POST") && ($_POST['book_id'] != null)) {
        $conn = new mysqli("localhost", "root", "", "lms");

        if ($conn->connect_error) {
          die("Connection failed: " . $conn->connect_error);
        }

        $book_id = $conn->real_escape_string($_POST['book_id']);

        $sql = "SELECT * FROM book WHERE book_id = ?";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("s", $book_id);
        $stmt->execute();
        $result = $stmt->get_result();

        if ($result->num_rows > 0) {
          $book = $result->fetch_assoc();
          $book_name = $book['book_name'];
          $language = $book['language'];
          $genre = $book['genre'];
        } else {
          echo "<div class='alert alert-warning'><b>No book found with ID:</b> " . $book_id . "</div>";
        }

        $stmt->close();
        $conn->close();
      }
      ?>

      <!-- Book Form Container -->
      <div class="book-form">
        <h2>Book CheckOut</h2>
        <form id="studentForm" action="book_checkout.php" method="POST">
          <label for="student_id">Student Id</label>
          <input type="text" id="student_id" name="student_id" placeholder="Enter your Student Id" value="<?= $student_id ?>" onkeydown="handleTabKey(event, 'student')">

          <div class="divider"></div>

          <div class="student-info">
            <b>First Name:</b> <?= $first_name ?><br>
            <b>Middle Name:</b> <?= $middle_name ?><br>
            <b>Last Name:</b> <?= $last_name ?><br>
            <b>Grade:</b> <?= $grade ?><br>
          </div>

          <div class="divider"></div>

          <label for="book_id">Book Id</label>
          <input type="text" id="book_id" name="book_id" placeholder="Enter Book Id" value="<?= $book_id ?>" onkeydown="handleTabKey(event, 'book')">

          <div class="divider"></div>

          <div class="book-info">
            <b>Book Title:</b> <?= $book_name ?><br>
            <b>Language:</b> <?= $language ?><br>
            <b>Genre:</b> <?= $genre ?><br>
          </div>

          <div class="divider"></div>
          <input type="hidden" id="submit_hidden" name="submit_hidden">
          <button type="button" value="submit" onclick="javascript:checkout('submit')">CheckOut</button>
        </form>
      </div>
    </body>
  </div>
</div>

<?php include 'footer.php'; ?>
