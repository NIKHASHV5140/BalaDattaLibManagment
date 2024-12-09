<?php
// Database connection settings
$host = 'localhost'; // Database host
$dbname = 'lms'; // Your database name
$username = 'root'; // Your MySQL username
$password = ''; // Your MySQL password

try {
    // Create a PDO instance
    $pdo = new PDO("mysql:host=$host;dbname=$dbname", $username, $password);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

    // Function to convert Excel date or formatted date string to MySQL date format
    function convertExcelDate($excelDate) {
        if (is_numeric($excelDate)) {
            $unixTimestamp = ($excelDate - 25569) * 86400; 
            return date('Y-m-d', $unixTimestamp); 
        } elseif (DateTime::createFromFormat('m/d/Y', $excelDate) !== false) {
            
            $date = DateTime::createFromFormat('m/d/Y', $excelDate);
            return $date->format('Y-m-d'); 
        } elseif (DateTime::createFromFormat('Y-m-d', $excelDate) !== false) {
        
            return $excelDate; 
        } elseif (DateTime::createFromFormat('d/m/Y', $excelDate) !== false) {
            
            $date = DateTime::createFromFormat('d/m/Y', $excelDate);
            return $date->format('Y-m-d'); 
        }
        return $excelDate;
    }

    // Handle POST request for uploading and inserting Excel data
    if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['data'])) {
        $data = json_decode($_POST['data'], true);

        // Assuming the first row contains the headers
        $headers = $data[0];

        // Identify potential date columns by analyzing header names
        $dateColumns = array_filter($headers, function ($header) {
            // Adjust this logic based on your column naming convention
            return stripos($header, 'date') !== false || stripos($header, 'dob') !== false;
        });

        // Prepare the SQL query for inserting data
        $tableName = 'book'; // Replace with your table name
        $placeholders = implode(',', array_fill(0, count($headers), '?'));
        $sql = "INSERT INTO $tableName (" . implode(',', $headers) . ") VALUES ($placeholders)";
        $stmt = $pdo->prepare($sql);

        // Loop through the rows (skip the first row which is headers)
        foreach (array_slice($data, 1) as $row) {
            // Convert all potential date fields
            foreach ($row as $key => $value) {
                if (in_array($headers[$key], $dateColumns)) {
                    $row[$key] = convertExcelDate($value);
                }
            }

            // Execute the insert for each row
            $stmt->execute($row);
        }

        echo "Data inserted successfully!";
    }

    if ($_SERVER['REQUEST_METHOD'] == 'GET') {
        $sql = "SELECT * FROM book"; 
        $stmt = $pdo->query($sql);

        // Fetch all rows as an associative array
        $data = $stmt->fetchAll(PDO::FETCH_ASSOC);

        // Return the data as JSON
        echo json_encode($data);
    }
} catch (PDOException $e) {
    echo "Error: " . $e->getMessage();
}
?>
 