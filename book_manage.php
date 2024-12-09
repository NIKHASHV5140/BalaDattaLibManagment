<?php
// Start session
session_start();

// Include header and left panel
include 'header.php';
include 'leftpanel.php';

// Database connection
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "lms";

$conn = new mysqli($servername, $username, $password, $dbname);

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Fetch data from your table
$sql = "SELECT book_id, book_name, price, genre, publication, language FROM book"; // Adjust columns as needed
$result = $conn->query($sql);
?>

<div style="display: flex; margin-top: 1rem; align-items: flex-start;">

    <!-- Table Container -->
    <div style="flex: 1; padding: 1rem; margin-left: 1rem; width: 80%; max-width: 900px; box-shadow: 0px 4px 8px rgba(0, 0, 0, 0.1); border-radius: 8px; background-color: #ffffff; position: relative; top: -20px;">
        <h2 style="text-align: center; margin-bottom: 1rem; color: #333;">Library Books</h2>
        <div style="overflow-x: auto;">
            <table style="width: 100%; border-collapse: collapse; box-shadow: 0px 4px 8px rgba(0, 0, 0, 0.1);">
                <thead>
                    <tr style="background-color: #f8f9fa; color: #333; text-align: left;">
                        <th style="padding: 12px; border: 1px solid #ddd;">Book_ID</th>
                        <th style="padding: 12px; border: 1px solid #ddd;">Book Name</th>
                        <th style="padding: 12px; border: 1px solid #ddd;">Price</th>
                        <th style="padding: 12px; border: 1px solid #ddd;">Genre</th>
                        <th style="padding: 12px; border: 1px solid #ddd;">Publication</th>
                        <th style="padding: 12px; border: 1px solid #ddd;">Language</th>
                    </tr>
                </thead>
                <tbody>
                    <?php if ($result->num_rows > 0): ?>
                        <?php while ($row = $result->fetch_assoc()): ?>
                            <tr style="background-color: #fff; border-bottom: 1px solid #ddd;">
                                <td style="padding: 12px; border: 1px solid #ddd;"><?php echo htmlspecialchars($row['book_id']); ?></td>
                                <td style="padding: 12px; border: 1px solid #ddd;"><?php echo htmlspecialchars($row['book_name']); ?></td>
                                <td style="padding: 12px; border: 1px solid #ddd;"><?php echo htmlspecialchars($row['price']); ?></td>
                                <td style="padding: 12px; border: 1px solid #ddd;"><?php echo htmlspecialchars($row['genre']); ?></td>
                                <td style="padding: 12px; border: 1px solid #ddd;"><?php echo htmlspecialchars($row['publication']); ?></td>
                                <td style="padding: 12px; border: 1px solid #ddd;"><?php echo htmlspecialchars($row['language']); ?></td>
                            </tr>
                        <?php endwhile; ?>
                    <?php else: ?>
                        <tr>
                            <td colspan="6" style="padding: 12px; text-align: center;">No data found.</td>
                        </tr>
                    <?php endif; ?>
                </tbody>
            </table>
        </div>
    </div>
</div>

<?php
// Close connection
$conn->close();

// Include footer
include 'footer.php';
?>
