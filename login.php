<?php
session_start();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Login</title>
    <style>
    /* General Styling */
    * {
        margin: 0;
        padding: 0;
        box-sizing: border-box;
    }
    body {
        font-family: 'Arial', sans-serif;
        background: #fff; /* Clean white background */
        height: 100vh;
        display: flex;
        justify-content: center;
        align-items: center;
        flex-direction: column;
        color: #333;
    }

    /* Header Section */
    header {
        width: 100%;
        position: relative;
        text-align: center;
        margin-bottom: 20px;
    }
    header img {
        width: 60%;
        border-radius: 10px;
        margin-bottom: 20px;
    }
    header .header-content h1 {
        font-size: 2rem;
        font-weight: bold;
        color: #FF6700; /* Orange theme */
        text-shadow: 1px 1px 5px rgba(255, 103, 0, 0.3);
    }

    /* Login Form Container */
    .container {
        width: 100%;
        max-width: 400px;
        background: #fff;
        border: 1px solid #FFD8A8; /* Subtle border for contrast */
        border-radius: 10px;
        box-shadow: 0px 5px 15px rgba(0, 0, 0, 0.1); /* Soft shadow */
        padding: 2.5rem;
        color: #333;
    }

    h2 {
        text-align: center;
        color: #FF6700;
        margin-bottom: 1.5rem;
        font-size: 1.6rem;
    }

    /* Form Group Styling */
    .form-group {
        margin-bottom: 1.5rem;
    }
    .form-group label {
        font-size: 1rem;
        margin-bottom: 0.5rem;
        color: #333;
        display: block;
    }
    .form-group input {
        width: 100%;
        padding: 0.8rem;
        font-size: 1.1rem;
        border: 1px solid #ddd;
        border-radius: 5px;
        outline: none;
        background-color: #fff;
        color: #333;
        transition: border-color 0.3s ease;
    }
    .form-group input:focus {
        border-color: #FF6700;
        box-shadow: 0px 0px 8px rgba(255, 103, 0, 0.3);
    }

    /* Button Styling */
    .btn {
        width: 100%;
        padding: 0.8rem;
        font-size: 1.2rem;
        background: #FF6700; /* Orange theme */
        color: #fff;
        border: none;
        border-radius: 5px;
        cursor: pointer;
        transition: background 0.3s ease;
        text-transform: uppercase;
        box-shadow: 0px 4px 6px rgba(0, 0, 0, 0.1); /* Button shadow for depth */
    }
    .btn:hover {
        background: #E65C00; /* Slightly darker orange */
    }

    /* Footer Section */
    .footer {
        text-align: center;
        margin-top: 1.5rem;
        font-size: 0.9rem;
        color: #666;
    }
    .footer a {
        color: #FF6700;
        text-decoration: none;
    }
    .footer a:hover {
        text-decoration: underline;
    }

    /* Responsive Design */
    @media (max-width: 600px) {
        .container {
            padding: 1.5rem;
            width: 90%;
        }
        header img {
            width: 80%;
        }
        h2 {
            font-size: 1.4rem;
        }
    }
</style>


</head>
<body>

    <!-- Header Section -->
    <header>
        <img src="images/temple.jpg" alt="Temple Image">
        <div class="header-content">
            <h1>Bala Datta Library Managment System</h1>
        </div>
    </header>

    <!-- Login Form Container -->
    <div class="container">
        <h2>Login</h2>
        <?php if (isset($_GET['error'])): ?>
            <p style="color: red; text-align: center;">Invalid username or password. Please try again.</p>
        <?php endif; ?>
        <form action="validate_login.php" method="post">
            <div class="form-group">
                <label for="username">Username:</label>
                <input type="text" name="username" id="username" required>
            </div>
            <div class="form-group">
                <label for="password">Password:</label>
                <input type="password" name="password" id="password" required>
            </div>
            <button type="submit" class="btn">Login</button>
        </form>
        </div>
    
</body>
</html>
