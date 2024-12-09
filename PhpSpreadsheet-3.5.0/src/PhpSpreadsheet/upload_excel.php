<?php
// Include the PHPSpreadsheet autoload file
require 'autoload.php'; // Path to your autoload.php file


use PhpOffice\PhpSpreadsheet\IOFactory;

// Database connection
$host = 'localhost';
$dbname = 'lms';
$username = 'root'; // Replace with your DB username
$password = ''; // Replace with your DB password

try {
    // Connect to the database
    $pdo = new PDO("mysql:host=$host;dbname=$dbname;charset=utf8mb4", $username, $password);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch (PDOException $e) {
    die("Database connection failed: " . $e->getMessage());
}

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    if (isset($_FILES['excel_file']) && $_FILES['excel_file']['error'] == 0) {
        $targetDir = "uploads/";
        $fileName = basename($_FILES["excel_file"]["name"]);
        $targetFilePath = $targetDir . $fileName;

        // Create directory if not exists
        if (!is_dir($targetDir)) {
            mkdir($targetDir, 0777, true);
        }

        // Move uploaded file to server
        if (move_uploaded_file($_FILES["excel_file"]["tmp_name"], $targetFilePath)) {
            try {
                // Load the Excel file
                $spreadsheet = IOFactory::load($targetFilePath);
                $sheet = $spreadsheet->getActiveSheet();
                $data = $sheet->toArray();

                // Prepare SQL statement
                $stmt = $pdo->prepare("INSERT INTO students (student_id, first_name, middle_name, last_name, email, dob, address, city, state, zip_code, phone, grade) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)");

                // Loop through Excel data and insert into database
                foreach ($data as $index => $row) {
                    // Skip the header row
                    if ($index == 0) continue;

                    // Adjust based on your Excel structure
                    $student_id = $row[0] ?? null;
                    $first_name = $row[1] ?? null;
                    $middle_name = $row[2] ?? null;
                    $last_name = $row[1] ?? null;
                    $email = $row[1] ?? null;
                    $dob = $row[1] ?? null;
                    $address = $row[1] ?? null;
                    $city = $row[1] ?? null;
                    $state = $row[1] ?? null;
                    $zip_code = $row[1] ?? null;
                    $phone = $row[1] ?? null;
                    $grade = $row[1] ?? null;

                    $stmt->execute([$student_id, $first_name, $middle_name, $last_name, $email, $dob, $address, $city, $state, $zip_code, $phone, $grade]);
                }

                echo "Data imported successfully into the database!";
            } catch (Exception $e) {
                echo "Error processing file: " . $e->getMessage();
            }
        } else {
            echo "File upload failed.";
        }
    } else {
        echo "No file uploaded or there was an error.";
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Upload Excel File</title>
</head>
<body>
    <h1>Upload Bulk Excel File</h1>
    <form action="upload_excel.php" method="post" enctype="multipart/form-data">
        <label for="excel_file">Choose an Excel File:</label>
        <input type="file" name="excel_file" id="excel_file" accept=".xls,.xlsx" required>
        <br><br>
        <button type="submit">Upload and Store</button>
    </form>
</body>
</html>
