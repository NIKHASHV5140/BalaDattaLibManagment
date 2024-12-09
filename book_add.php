<?php
// Start session
session_start();

// Check if the user is logged in
if (!isset($_SESSION['username']) || empty($_SESSION['username'])) {
    header("Location: login.php");
    exit;
}

// Database connection
$servername = "localhost";
$username = "root";
$password = "";
$database = "lms";

$conn = new mysqli($servername, $username, $password, $database);

// Check connection
if ($conn->connect_error) {
    die("Database connection failed: " . $conn->connect_error);
}

// Initialize message variable
$message = "";

// Form submission handling
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $book_id = $conn->real_escape_string(trim($_POST['book_id']));
    $book_title = $conn->real_escape_string(trim($_POST['book_title']));
    $language = $conn->real_escape_string(trim($_POST['language']));
    $genre = $conn->real_escape_string(trim($_POST['genre']));
    $publication = $conn->real_escape_string(trim($_POST['publication']));
    $price = $conn->real_escape_string(trim($_POST['price']));

    // Check if book already exists
    $check_query = "SELECT * FROM book WHERE book_id = '$book_id'";
    $result = $conn->query($check_query);

    if ($result->num_rows > 0) {
        $message = "Book ID already exists!";
    } else {
        // Insert book data
        $insert_query = "INSERT INTO book (book_id, book_name, language, genre, publication, price) 
                         VALUES ('$book_id', '$book_title', '$language', '$genre', '$publication', '$price')";

        if ($conn->query($insert_query) === TRUE) {
            $message = "Book added successfully!";
        } else {
            $message = "Error: " . $conn->error;
        }
    }
}
?>

<?php include 'header.php'; ?>

<div class="container" style="display: flex; flex: 1; padding-top: 2rem; background-color: #f8f9fa; font-family: 'Arial', sans-serif;">

    <?php include 'leftpanel.php'; ?>

    <!-- Button to navigate to another page -->
    <a href="excelupload_frontendbook.php" class="btn btn-primary" style="position: absolute; bottom: 450px; right: 20px; padding: 10px 15px; font-size: 16px; background-color: #28a745; color: white; text-decoration: none; border-radius: 5px;">
        Bulk Upload
    </a>

    <!-- Main Content Area -->
    <div class="main-content" style="flex: 1; padding: 2rem; background-color: #ffffff; margin-left: 2rem; border-radius: 8px; border: 1px solid #ddd;">

        <style>
            /* Form Styles */
            .form-container {
                background-color: #ffffff;
                padding: 2rem;
                border-radius: 8px;
                border: 1px solid #ddd;
                max-width: 650px;
                margin: auto;
            }

            .form-container h2 {
                text-align: center;
                color: #333;
                margin-bottom: 1.5rem;
                font-size: 1.8rem;
                font-weight: bold;
            }

            .form-container label {
                display: block;
                font-weight: bold;
                margin-bottom: 0.5rem;
                color: #555;
            }

            .form-container input[type="text"],
            .form-container input[type="number"],
            .form-container input[type="email"],
            .form-container input[type="date"],
            .form-container select {
                width: 100%;
                padding: 0.8rem;
                margin-bottom: 1rem;
                border: 1px solid #ccc;
                border-radius: 4px;
                font-size: 1rem;
                background: #f9f9f9;
            }

            .form-container button {
                width: 100%;
                padding: 0.8rem;
                background-color: #007bff;
                color: white;
                border: none;
                border-radius: 4px;
                font-size: 1rem;
                cursor: pointer;
            }

            .form-container button:hover {
                background-color: #0056b3;
            }

            .message {
                text-align: center;
                color: green;
                font-weight: bold;
            }
        </style>

        <div class="form-container">
            <h2>Book Information</h2>
            <?php if (!empty($message)): ?>
                <p class="message"><?php echo $message; ?></p>
            <?php endif; ?>
            <form action="" method="POST">
                <label for="book_id">Book Id</label>
                <input type="text" id="book_id" name="book_id" placeholder="Enter Book Id" required>

                <label for="book_title">Book Title</label>
                <input type="text" id="book_title" name="book_title" placeholder="Enter Book Title" required>

                <label for="language">Language</label>
                <select id="language" name="language">
                    <option value="" disabled selected>Select a Language</option>
                    <option value="tamil">Tamil</option>
                    <option value="telugu">Telugu</option>
                    <option value="hindi">Hindi</option>
                    <option value="others">Others</option>
                </select>

                <label for="genre">Genre</label>
                <input type="text" id="genre" name="genre" placeholder="Enter Genre" required>

                <label for="publication">Publication</label>
                <input type="text" id="publication" name="publication" placeholder="Enter Publication">

                <label for="price">Price</label>
                <input type="text" id="price" name="price" placeholder="Enter Price">

                <button type="submit">Submit</button>
            </form>
        </div>

    </div>
</div>

<?php include 'footer.php'; ?>
