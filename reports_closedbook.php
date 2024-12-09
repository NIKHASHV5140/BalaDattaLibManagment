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
<div class="container" style="display: flex; flex: 1; padding-top: 1rem; position: relative;">
  <?php include 'leftpanel.php'; ?>

  <!-- Main Content Area -->
  <div class="main-content" style="flex: 1; padding: 2rem; background-color: #ffffff; margin-left: 1rem; border-radius: 12px; box-shadow: 0px 10px 20px rgba(0, 0, 0, 0.1);">

    <h2 style="text-align: center; margin-bottom: 1.5rem; color: #333; font-size: 2rem; font-weight: 700;">Checked In Reports</h2>

    <!-- Add the two buttons in the top right corner -->
    <div style="position: absolute; top: 20px; right: 20px;">
      <a href="reports.php" class="btn" style="margin-left: 10px;">Checked Out Report</a>
      <a href="reports_closedbook.php" class="btn" style="margin-left: 10px;">Checked in Report</a>
    </div>

    <form method="POST" action="generate_pdf.php">
      <button type="submit" class="btn" style="margin-bottom: 20px;">Download as PDF</button>
    </form>

    <style>
      /* Global Styles */
      body {
        font-family: 'Arial', sans-serif;
        background: #f4f4f9; /* Soft gray background */
        margin: 0;
        padding: 0;
      }

      .main-content {
        background: #ffffff; /* White background for the content */
        border-radius: 12px;
        box-shadow: 0 10px 20px rgba(0, 0, 0, 0.1);
        padding: 3rem;
        transition: all 0.3s ease;
      }

      .main-content:hover {
        transform: translateY(-10px);
        box-shadow: 0px 20px 30px rgba(0, 0, 0, 0.2);
      }

      .btn {
        background-color: #007BFF; /* Soft blue buttons */
        color: white;
        padding: 12px 20px;
        border: none;
        border-radius: 8px;
        cursor: pointer;
        font-size: 1.2rem;
        transition: background-color 0.3s ease, transform 0.3s ease;
        text-decoration: none;
        display: inline-block;
      }

      .btn:hover {
        background-color: #0056b3; /* Darker blue on hover */
        transform: scale(1.05);
      }

      table {
        width: 100%;
        border-collapse: collapse;
        margin-top: 20px;
        font-size: 1.1rem;
        text-align: left;
        border-radius: 10px;
        overflow: hidden;
      }

      table th, table td {
        padding: 16px;
        border: 1px solid #ddd;
      }

      table th {
        background-color: #f4f4f9;
        font-weight: bold;
      }

      table tr:nth-child(even) {
        background-color: #fafafa; /* Light gray for alternating rows */
      }

      table tr:hover {
        background-color: #f1f1f1;
        transform: scale(1.02);
        transition: transform 0.2s ease-in-out;
      }

      .form-container {
        background: linear-gradient(45deg, #e0e0e0, #ffffff); /* Light gray gradient */
        padding: 3rem;
        border-radius: 15px;
        box-shadow: 0px 15px 30px rgba(0, 0, 0, 0.1);
        width: 100%;
        max-width: 800px;
        transition: transform 0.3s ease, box-shadow 0.3s ease;
        backdrop-filter: blur(8px);
        color: #333;
      }

      .form-container:hover {
        transform: translateY(-10px);
        box-shadow: 0px 20px 40px rgba(0, 0, 0, 0.2);
      }

      .back-button {
        text-align: center;
        margin-top: 2rem;
      }

      .back-button a {
        text-decoration: none;
        color: #007BFF;
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

    // Query to fetch details from book_trans, books, and students tables
    $sql = "SELECT 
                s.first_name, 
                s.last_name, 
                s.grade,
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
                bt.status = 'closed'";

    $result = $conn->query($sql);

    // Check if any checked-in transactions are found
    if ($result->num_rows > 0) {
        echo "<table>
                <tr>
                    <th>Student Id</th>
                    <th>Student First Name</th>
                    <th>Student Last Name</th>
                    <th>Grade</th>
                    <th>Book ID</th>
                    <th>Book Name</th>
                    <th>Checkout Date</th>
                    <th>Status</th>
                </tr>";

        // Loop through and display each record
        while ($row = $result->fetch_assoc()) {
            echo "<tr>
                    <td><center>" . htmlspecialchars($row['student_id']) . "</center></td>
                    <td><center>" . htmlspecialchars($row['first_name']) . "</center></td>
                    <td><center>" . htmlspecialchars($row['last_name']) . "</center></td>
                    <td><center>" . htmlspecialchars($row['grade']) . "</center></td>
                    <td><center>" . htmlspecialchars($row['book_id']) . "</center></td>
                    <td><center>" . htmlspecialchars($row['book_name']) . "</center></td>
                    <td><center>" . htmlspecialchars($row['checkout_ts']) . "</center></td>
                    <td><center>" . htmlspecialchars($row['status']) . "</center></td>
                  </tr>";
        }

        echo "</table>";
    } else {
        echo "<center><b>No checked-in reports found.</b></center>";
    }

    // Close the database connection
    $conn->close();
    ?>

  </div>
</div>
<?php include 'footer.php'; ?>
