<!-- header.php -->
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Library Managment System</title>
  <style>
    /* General Styling */
    * {
      margin: 0;
      padding: 0;
      box-sizing: border-box;
    }
    body {
      font-family: Arial, sans-serif;
      display: flex;
      flex-direction: column;
      min-height: 100vh;
    }
    header {
      width: 100%;
      overflow: hidden;
      position: relative;
    }
    header img {
      width: 100%;
      height: auto;
      display: block;
    }
    header .header-content {
      position: absolute;
      top: 50%;
      left: 50%;
      transform: translate(-50%, -50%);
      color: white;
      font-size: 1.5rem;
      text-align: center;
    }
  </style>
</head>
<body>
  <!-- Header Section with Full-Width Image -->
  <header>
    <!-- Replace "header-image.jpg" with the path to your image file -->
    <img src="images/temple.jpg" alt="Header Image">
    <div class="header-content">
      <h1>Bala Datta Library Managment System</h1>
    </div>
  </header>
