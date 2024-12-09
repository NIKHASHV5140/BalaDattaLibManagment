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

<div class="container" style="display: flex; flex: 1; padding-top: 1rem; background-color: #f8f9fa; font-family: 'Poppins', sans-serif;">
  <?php include 'leftpanel.php'; ?>

  <!-- Main Content Area -->
  <div class="main-content" style="flex: 1; padding: 2rem; background-color: #ffffff; margin-left: 1rem; border-radius: 20px; box-shadow: 0px 15px 40px rgba(0, 0, 0, 0.1);">
    
    <style>
      /* General Page Styling */
      body {
        font-family: 'Poppins', sans-serif;
        background-color: #f8f9fa;
        margin: 0;
        padding: 0;
      }

      /* Button Styling */
      .btn {
        background-color: #007bff;
        color: white;
        padding: 14px 24px;
        border: none;
        border-radius: 12px;
        cursor: pointer;
        font-size: 1.1rem;
        text-decoration: none;
        transition: background-color 0.3s ease, transform 0.2s ease;
        margin-top: 20px;
        box-shadow: 0px 8px 20px rgba(0, 0, 0, 0.1);
      }

      .btn:hover {
        background-color: #0056b3;
        transform: translateY(-2px);
      }

      .btn-secondary {
        background-color: #28a745;
      }

      .btn-secondary:hover {
        background-color: #218838;
        transform: translateY(-2px);
      }

      /* Book Form Styling */
      .book-form {
        background-color: #f4f6fc;
        padding: 2.5rem;
        border-radius: 15px;
        box-shadow: 0 15px 35px rgba(0, 0, 0, 0.1);
        max-width: 500px;
        margin: 20px auto;
        transition: box-shadow 0.3s ease;
      }

      .book-form h2 {
        text-align: center;
        color: #333;
        margin-bottom: 1rem;
        font-family: 'Arial', sans-serif;
        font-weight: bold;
      }

      .book-form label {
        display: block;
        font-weight: bold;
        margin-bottom: 0.5rem;
        color: #007bff;
      }

      .book-form input[type="text"] {
        width: 100%;
        padding: 1rem;
        margin-bottom: 1.5rem;
        border: 2px solid #007bff;
        border-radius: 10px;
        font-size: 1rem;
        background-color: #f8f9fa;
        color: #495057;
        transition: border-color 0.3s ease, box-shadow 0.3s ease;
      }

      .book-form input[type="text"]:focus {
        border-color: #0056b3;
        box-shadow: 0 0 8px rgba(0, 91, 179, 0.3);
        outline: none;
      }

      .book-form button {
        width: 100%;
        padding: 1rem;
        background-color: #007bff;
        color: white;
        border: none;
        border-radius: 12px;
        font-size: 1.2rem;
        cursor: pointer;
        transition: background-color 0.3s ease, transform 0.2s ease;
      }

      .book-form button:hover {
        background-color: #0056b3;
        transform: translateY(-2px);
      }

      /* Table Styling */
      table {
        width: 100%;
        margin-top: 2rem;
        border-collapse: collapse;
        font-size: 1rem;
        box-shadow: 0px 8px 15px rgba(0, 0, 0, 0.1);
      }

      table th, table td {
        padding: 14px;
        text-align: left;
        border: 1px solid #ddd;
        vertical-align: middle;
      }

      table th {
        background-color: #f4f4f4;
        color: #333;
        font-weight: bold;
      }

      table td {
        background-color: #fff;
        color: #555;
      }

      table tr:nth-child(even) {
        background-color: #f9f9f9;
      }

      table tr:hover {
        background-color: #f1f1f1;
      }

      /* Centering Table */
      table {
        margin-left: auto;
        margin-right: auto;
      }
    </style>

    <!-- Student Form Container -->
    <div class="book-form">
      <h2>Book Check-In</h2>
      <form method="POST">
        <label for="student_id">Student ID</label>
        <input type="text" id="student_id" name="student_id" placeholder="Enter Student ID" required>
        <button type="submit" value="submit" name="load_student">Load</button>
      </form>
    </div>

    <!-- Buttons below the Load button (centered) -->
    <div style="text-align: center; margin-top: 20px;">
      <a href="book_checkin.php" class="btn">Use Student ID</a>
      <a href="book_check_in.php" class="btn btn-secondary">Use Book ID</a>
    </div>

    <!-- Display Books Checked Out -->
    <?php
    // Database connection
    $conn = new mysqli("localhost", "root", "", "lms");

    if ($conn->connect_error) {
        die("Connection failed: " . $conn->connect_error);
    }

    // Check if student_id is submitted
    if (isset($_POST['load_student']) && !empty($_POST['student_id'])) {
        $input = $_POST['student_id'];

        // Check if the input is numeric (student ID)
        if (is_numeric($input)) {
            // Query the database for the student and their checked-out books
            $query = "SELECT s.student_id, s.first_name,s.grade, b.book_name, c.checkout_ts, c.book_id
                      FROM students s
                      JOIN book_trans c ON s.student_id = c.student_id
                      JOIN book b ON b.book_id = c.book_id
                      WHERE s.student_id = ? AND c.status != 'closed'";

            $stmt = $conn->prepare($query);
            $stmt->bind_param("i", $input);
        } else {
            // If the input is not numeric, treat it as invalid input
            echo "<p>No records found for the entered Student ID.</p>";
            exit;
        }

        // Execute the query and check if there are any results
        $stmt->execute();
        $result = $stmt->get_result();

        // Check if there are any results
        if ($result->num_rows > 0) {
            // Display the results (books checked out by the student)
            echo "<h3>Books Checked Out</h3>";
            echo "<table>
                    <tr>
                        <th>Student ID</th>
                        <th>Student Name</th>
                        <th>Grade</th>
                        <th>Book Name</th>
                        <th>Checkout Date</th>
                        <th>Action</th>
                    </tr>";

            while ($row = $result->fetch_assoc()) {
                echo "<tr>
                        <td>" . htmlspecialchars($row['student_id']) . "</td>
                        <td>" . htmlspecialchars($row['first_name']) . "</td>
                        <td>" . htmlspecialchars($row['grade']) . "</td>
                        <td>" . htmlspecialchars($row['book_name']) . "</td>
                        <td>" . htmlspecialchars($row['checkout_ts']) . "</td>
                        <td>
                            <form method='POST' style='display:inline;'>
                                <input type='hidden' name='book_id' value='" . htmlspecialchars($row['book_id']) . "'>
                                <button type='submit' name='checkin' class='btn btn-secondary'>CheckIn</button>
                            </form>
                        </td>
                      </tr>";
            }
            echo "</table>";
        } else {
            // No records found for the student ID
            echo "<p>No records found for the entered Student ID.</p>";
        }
        $stmt->close();
    }

    // Handle book check-in
    if (isset($_POST['checkin']) && !empty($_POST['book_id'])) {
        $book_id = $_POST['book_id'];
        $query = "UPDATE book_trans SET status = 'closed', checkin_ts = CURRENT_TIMESTAMP WHERE book_id = ? AND status != 'closed'";
        $stmt = $conn->prepare($query);
        $stmt->bind_param("i", $book_id);

        if ($stmt->execute()) {
            echo "<p>Book successfully checked in!</p>";
        } else {
            echo "<p>Error checking in the book.</p>";
        }
        $stmt->close();
    }

    $conn->close();
    ?>
  </div>
</div>

<?php include 'footer.php'; ?>
