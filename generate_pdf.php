<?php
require('fpdf.php');

// Database connection
$conn = new mysqli("localhost", "root", "", "lms");

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Query to fetch details
$sql = "SELECT 
            s.first_name, 
            s.last_name, 
            s.grade,
            s.email, 
            b.book_name, 
            bt.checkout_ts, 
            bt.status
        FROM 
            book_trans bt
        JOIN 
            book b ON bt.book_id = b.book_id
        JOIN 
            students s ON bt.student_id = s.student_id
        WHERE 
            bt.status = 'closed'";

$result = $conn->query($sql);

// Create a new PDF instance
$pdf = new FPDF();
$pdf->AddPage();
$pdf->SetFont('Arial', 'B', 12);

// Add a title
$pdf->Cell(0, 10, 'Checked In Reports', 0, 1, 'C');

// Add a table header
$pdf->SetFont('Arial', 'B', 10);
$pdf->Cell(30, 10, 'First Name', 1);
$pdf->Cell(30, 10, 'Last Name', 1);
$pdf->Cell(20, 10, 'Grade', 1);
$pdf->Cell(50, 10, 'Email', 1);
$pdf->Cell(40, 10, 'Book Name', 1);
$pdf->Cell(30, 10, 'Checkout Date', 1);
$pdf->Ln();

// Fetch and display data
$pdf->SetFont('Arial', '', 10);
while ($row = $result->fetch_assoc()) {
    $pdf->Cell(30, 10, $row['first_name'], 1);
    $pdf->Cell(30, 10, $row['last_name'], 1);
    $pdf->Cell(20, 10, $row['grade'], 1);
    $pdf->Cell(50, 10, $row['email'], 1);
    $pdf->Cell(40, 10, $row['book_name'], 1);
    $pdf->Cell(30, 10, $row['checkout_ts'], 1);
    $pdf->Ln();
}

// Output PDF to download
$pdf->Output('D', 'Reports.pdf');

// Close connection
$conn->close();
?>
