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
<div class="container" style="display: flex; flex: 1; padding-top: 1rem; position: relative; background-color: #f4f4f4;">
  <?php include 'leftpanel.php'; ?>

  <!-- Main Content Area -->
  <div class="main-content" style="flex: 1; padding: 2rem; background-color: #ffffff; margin-left: 1rem; border-radius: 8px; box-shadow: 0px 10px 15px rgba(0, 0, 0, 0.1);">

    <h2 style="text-align: center; margin-bottom: 1.5rem; color: #333; font-size: 2rem;">Checked Out Reports</h2>

    <!-- Add the two buttons in the top right corner -->
    <div style="position: absolute; top: 20px; right: 20px;">
      <a href="reports.php" class="btn" style="margin-left: 10px;">Checked Out Report</a>
      <a href="reports_closedbook.php" class="btn" style="margin-left: 10px;">Checked In Report</a>
    </div>

    <form method="POST" action="generate_pdf.php">
      <button type="submit" class="btn" style="margin-bottom: 20px;">Download as PDF</button>
    </form>

    <style>
      /* Global Styles */
      body {
        font-family: 'Arial', sans-serif;
        background-color: #f4f4f4;
        margin: 0;
        padding: 0;
      }

      .main-content {
        background: #ffffff;
        border-radius: 8px;
        box-shadow: 0 10px 15px rgba(0, 0, 0, 0.1);
        padding: 2rem;
        position: relative;
      }

      .btn {
        background-color: #007bff;
        color: white;
        padding: 10px 15px;
        border: none;
        border-radius: 8px;
        cursor: pointer;
        font-size: 1.2rem;
        transition: background-color 0.3s ease;
        text-decoration: none;
      }

      .btn:hover {
        background-color: #0056b3;
      }

      table {
        width: 100%;
        border-collapse: collapse;
        margin-top: 20px;
        font-size: 1.1rem;
        text-align: left;
      }

      table th, table td {
        padding: 12px;
        border: 1px solid #ddd;
        text-align: center;
      }

      table th {
        background-color: #f4f4f4;
        font-weight: bold;
      }

      table tr:nth-child(even) {
        background-color: #f9f9f9;
      }

      table tr:hover {
        background-color: #f1f1f1;
      }

      .form-container {
        background: linear-gradient(45deg, #ff6f91, #ffac41);
        padding: 3rem;
        border-radius: 15px;
        box-shadow: 0px 15px 30px rgba(0, 0, 0, 0.2);
        width: 100%;
        max-width: 800px;
        transition: transform 0.3s ease, box-shadow 0.3s ease;
        backdrop-filter: blur(8px);
        color: #fff;
      }

      .form-container:hover {
        transform: translateY(-10px);
        box-shadow: 0px 20px 40px rgba(0, 0, 0, 0.3);
      }

      .form-container h2 {
        text-align: center;
        font-size: 2.5rem;
        font-weight: 700;
        margin-bottom: 2rem;
        text-transform: uppercase;
        letter-spacing: 2px;
      }

      .back-button {
        text-align: center;
        margin-top: 2rem;
      }

      .back-button a {
        text-decoration: none;
        color: #007bff;
        font-weight: bold;
        font-size: 1.2rem;
        padding: 0.8rem 2rem;
        background-color: rgba(0, 0, 0, 0.1);
        border-radius: 8px;
        transition: background-color 0.3s ease;
      }

      .back-button a:hover {
        background-color: rgba(0, 0, 0, 0.2);
      }
    </style>

    <?php  
    // Database connection
    $conn = new mysqli("localhost", "root", "", "lms");

    // Check connection
    if ($conn->connect_error) {
        die("Connection failed: " . $conn->connect_error);
    }

    // Handle bulk update
    if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['update_status'])) {
        if (!empty($_POST['checked_books'])) {
            $checked_books = $_POST['checked_books'];

            foreach ($checked_books as $book_id) {
                $query = "UPDATE book_trans SET checkin_ts = CURRENT_TIMESTAMP, status = 'closed' WHERE book_id = ?";
                if ($stmt = $conn->prepare($query)) {
                    $stmt->bind_param("i", $book_id);
                    $stmt->execute();
                }
            }
            echo "<p>Selected books have been checked in successfully!</p>";
        } else {
            echo "<p>No books selected for check-in.</p>";
        }
    }

    // Query to fetch details from book_trans, books, and students tables
    $sql = "SELECT 
                s.first_name, 
                s.last_name, 
                s.grade,
                s.phone,
                s.email, 
                b.book_id,
                b.book_name, 
                bt.checkout_ts, 
                bt.status,
                bt.book_id,
                bt.student_id
            FROM 
                book_trans bt
            JOIN 
                book b ON bt.book_id = b.book_id
            JOIN 
                students s ON bt.student_id = s.student_id
            WHERE 
                bt.status = 'Open'";

    $result = $conn->query($sql);

    // Check if any open transactions are found
    if ($result->num_rows > 0) {
        echo "<form method='POST'>";
        echo "<table>
                <tr>
                    <th>Student Id</th>
                    <th>Student First Name</th>
                    <th>Student Last Name</th>
                    <th>Grade</th>
                    <th>Phone Number</th>
                    <th>Email Address</th>
                    <th>Book ID</th>
                    <th>Book Name</th>
                    <th>Checkout Date</th>
                    <th>Status</th>
                    <th>Select</th>
                </tr>";

        // Loop through and display each record
        while ($row = $result->fetch_assoc()) {
            echo "<tr>
                    <td>" . htmlspecialchars($row['student_id']) . "</td>
                    <td>" . htmlspecialchars($row['first_name']) . "</td>
                    <td>" . htmlspecialchars($row['last_name']) . "</td>
                    <td>" . htmlspecialchars($row['grade']) . "</td>
                    <td>" . htmlspecialchars($row['phone']). "</td>
                    <td>" . htmlspecialchars($row['email']) . "</td>
                    <td>" . htmlspecialchars($row['book_id']) . "</td>                 
                    <td>" . htmlspecialchars($row['book_name']) . "</td>
                    <td>" . htmlspecialchars($row['checkout_ts']) . "</td>
                    <td>" . htmlspecialchars($row['status']) . "</td>
                    <td><input type='checkbox' name='checked_books[]' value='" . htmlspecialchars($row['book_id']) . "'></td>
                  </tr>";
        }

        echo "</table>";
        echo "<button type='submit' name='update_status' class='btn'>Update</button>";
        echo "</form>";
    } else {
        echo "<center><b>No open transactions found.</b></center>";
    }

    // Close the database connection
    $conn->close();
    ?>

  </div>
</div>
<?php include 'footer.php'; ?>
