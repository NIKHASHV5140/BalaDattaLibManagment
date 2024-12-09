<?php include 'header.php'; ?>

<?php
// Start session
session_start();

// Check if the user is logged in
if (!isset($_SESSION['username']) || empty($_SESSION['username'])) {
    header("Location: login.php");
    exit;
}

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <?php include 'leftpanel.php'; ?>

    <title>Upload Excel to MySQL</title>

    <!-- Include jQuery and DataTables -->
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <link rel="stylesheet" href="https://cdn.datatables.net/1.13.4/css/jquery.dataTables.min.css">
    <script src="https://cdn.datatables.net/1.13.4/js/jquery.dataTables.min.js"></script>

    <!-- Include SheetJS -->
    <script src="https://cdnjs.cloudflare.com/ajax/libs/xlsx/0.17.1/xlsx.full.min.js"></script>

    <style>
        /* General Layout */
        body {
            font-family: 'Arial', sans-serif;
            margin: 0;
            padding: 0;
            display: flex;
            background-color: #f8f9fa;
        }

        .left-panel {
        position: absolute;
        bottom: -70px; /* Adjust this value to move it higher */
        left: 0;
        width: 20%;
        height: calc(100vh - 120px); /* Full height minus header and footer height*/  
        }

        /* Main Content Area (to the right of left panel) */
        .container {
        margin-left: 400px;  /* Keeps space for the left panel */
        padding: 20px;
        width: 70%;  /* Adjust the width here to make it narrower */
        background-color: #ffffff;
        min-height: 100vh;
        padding-top: 30px;
        border-radius: 8px;
        box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
        }

        /* Page Header */
        .page-header {
            text-align: center;
            margin-bottom: 30px;
            color: #007bff;
        }

        /* Upload Section */
        .upload-section {
            background-color: #ffffff;
            padding: 30px;
            border-radius: 10px;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
            margin-bottom: 40px;
        }

        .upload-btn {
            background-color: #007bff;
            color: #fff;
            border: none;
            padding: 12px 20px;
            font-size: 16px;
            cursor: pointer;
            transition: background-color 0.3s;
            margin-top: 20px;
        }

        .upload-btn:hover {
            background-color: #0056b3;
        }

        .data-table-container {
            background-color: #ffffff;
            padding: 30px;
            border-radius: 10px;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
        }

        /* Table Styling */
        #dataTable {
            width: 10%;
            border-collapse: collapse;
        }

        #dataTable th,
        #dataTable td {
            padding: 12px;
            text-align: center;
            border: 1px solid #ddd;
        }

        #dataTable th {
            background-color: #007bff;
            color: white;
        }

        .file-input {
            margin-top: 20px;
            display: block;
        }

        .lead {
            color: #666;
        }

        .btn-file {
            background-color: #28a745;
            color: white;
            padding: 8px 15px;
            border-radius: 5px;
            cursor: pointer;
        }

        .btn-file:hover {
            background-color: #218838;
        }

        .file-name {
            margin-top: 10px;
            font-weight: bold;
            color: #555;
        }

        .success-message {
            display: none;
            background-color: #28a745;
            color: white;
            padding: 10px;
            border-radius: 5px;
            margin-top: 20px;
        }
    </style>
</head>

<body>

    <!-- Main Content Area -->
    <div class="container">
        <!-- Page Header -->
        <div class="page-header">
            <h1>Upload Excel File to MySQL</h1>
            <p class="lead">Upload your Excel file to transfer its data into the MySQL database.</p>
        </div>
            

            <label for="fileInput" class="btn-file btn-block">Choose Excel File</label>
            <input type="file" id="fileInput" class="file-input" style="display: none;" />
            <div class="file-name" id="fileName"></div>
            <button class="upload-btn" onclick="uploadExcel()">Upload and Transfer Data</button>

            <div class="success-message" id="successMessage">
                Your data has been successfully submitted!
            </div>
        </div>
    </div>

    <script>
        // Show selected file name
        document.getElementById('fileInput').addEventListener('change', function(event) {
            const fileName = event.target.files[0] ? event.target.files[0].name : 'No file selected';
            document.getElementById('fileName').innerText = `Selected file: ${fileName}`;
        });

        // Initialize DataTable
        const table = $('#dataTable').DataTable({
            "paging": true,
            "searching": true,
            "ordering": true
        });

        function uploadExcel() {
            const file = document.getElementById('fileInput').files[0];
            if (!file) {
                alert("Please upload an Excel file.");
                return;
            }

            const reader = new FileReader();
            reader.onload = function(e) {
                const data = e.target.result;
                const workbook = XLSX.read(data, { type: 'binary' });
                const sheet = workbook.Sheets[workbook.SheetNames[0]];
                const jsonData = XLSX.utils.sheet_to_json(sheet, { header: 1 });

                // Send data to server (PHP) to insert into MySQL
                $.ajax({
                    url: 'excelupload_backendbook.php',
                    type: 'POST',
                    data: { data: JSON.stringify(jsonData) },
                    success: function(response) {
                        // After inserting into the database, fetch the data from MySQL to populate DataTable
                        fetchData();
                        document.getElementById('successMessage').style.display = 'block'; // Show success message
                    },
                    error: function() {
                        alert("Error uploading data.");
                    }
                });
            };

            reader.readAsBinaryString(file);
        }

        // Fetch data from MySQL to populate DataTable
        function fetchData() {
            $.ajax({
                url: 'excelupload_backendbook.php',
                type: 'GET',
                success: function(data) {
                    const rows = JSON.parse(data);
                    table.clear();
                    rows.forEach(row => {
                        table.row.add(row);
                    });
                    table.draw();
                },
                error: function() {
                    alert("Error fetching data.");
                }
            });
        }
    </script>

</body>
</html>

<?php include 'footer.php'; ?>
