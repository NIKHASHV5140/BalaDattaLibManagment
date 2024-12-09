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

<div class="container" style="display: flex; height: calc(100vh - 60px); font-family: 'Poppins', sans-serif; margin-top: 3rem;">

  <?php include 'leftpanel.php'; ?>

  <!-- Main Content Area -->
  <div class="main-content" style="flex: 1; padding: 3rem; background-color: #f9f9f9; margin-left: 20%; border-radius: 20px; box-shadow: 0px 15px 40px rgba(0, 0, 0, 0.1); overflow: hidden; position: relative; z-index: 10;">

    <!-- Welcome Section with Background Image -->
    <div style="text-align: center; margin-bottom: 3rem; position: relative; z-index: 10;">
      <h2 style="font-size: 3.5rem; color: #4f4f4f; font-weight: bold; letter-spacing: 1px; text-transform: uppercase;">Welcome to Bala Datta Library!</h2>
      <p style="font-size: 1.2rem; color: #6f6f6f; line-height: 1.8; font-weight: 500;">Explore, manage, and dive into a world of knowledge. It's your gateway to limitless learning and adventure. Begin your journey with us!</p>
    </div>

    <!-- Action Buttons with Icons and Hover Effects -->
    <div style="display: flex; justify-content: center; gap: 3rem; z-index: 10;">
      <a href="book_checkout.php" style="text-decoration: none; width: 220px;">
        <button style="width: 100%; padding: 20px; font-size: 1.4rem; background-color: #6c8fbf; color: white; border: none; border-radius: 50px; cursor: pointer; box-shadow: 0 10px 30px rgba(108, 143, 191, 0.2); transition: transform 0.3s ease, box-shadow 0.3s ease-in-out; position: relative; overflow: hidden;">
          <i class="fa fa-book" style="margin-right: 10px;"></i><strong>Checkout</strong>
        </button>
      </a>
      <a href="book_checkin.php" style="text-decoration: none; width: 220px;">
        <button style="width: 100%; padding: 20px; font-size: 1.4rem; background-color: #ff8c94; color: white; border: none; border-radius: 50px; cursor: pointer; box-shadow: 0 10px 30px rgba(255, 140, 148, 0.2); transition: transform 0.3s ease, box-shadow 0.3s ease-in-out; position: relative; overflow: hidden;">
          <i class="fa fa-check-circle" style="margin-right: 10px;"></i><strong>Checkin</strong>
        </button>
      </a>
    </div>

  </div>
</div>

<?php include 'footer.php'; ?>

<style>
  /* Ensure the left panel stays below the header and closer to the top */
  .left-panel {
    position: absolute;
    top: -30px; /* Adjust this value to move it higher */
    left: 0;
    width: 20%;
    height: calc(100vh - 120px); /* Full height minus header and footer height*/  
  }

  /* Main Content Area */
  .main-content {
    flex: 1;
    padding: 3rem;
    background-color: #f9f9f9; /* Soft light gray background */
    margin-left: 20%; /* Add margin-left to avoid overlapping with the left panel */
    border-radius: 20px;
    box-shadow: 0px 15px 40px rgba(0, 0, 0, 0.1);
    overflow: hidden;
    position: relative;
    z-index: 10;
  }

  /* Custom Font */
  body {
    font-family: 'Poppins', sans-serif;
    background-color: #e8f4f8; /* Light blue-gray background */
    color: #333;
  }

  h2 {
    color: #4f4f4f; /* Soft gray for the header */
    text-shadow: 2px 2px 5px rgba(0, 0, 0, 0.05); /* Subtle shadow for depth */
  }

  p {
    color: #6f6f6f; /* Softer gray for the description */
    text-shadow: 1px 1px 3px rgba(0, 0, 0, 0.05); /* Soft shadow for text */
  }

  .main-content button {
    font-size: 1.3rem;
    text-transform: uppercase;
    letter-spacing: 0.5px;
  }

  .main-content button i {
    font-size: 1.5rem;
  }

  .container {
    background-color: #ffffff;
    display: flex;
    justify-content: center;
    align-items: flex-start;
    min-height: 100vh;
    position: relative;
    margin-top: -3rem;
  }

  /* Button hover transitions */
  .main-content button:hover {
    transform: scale(1.05);
    box-shadow: 0px 15px 35px rgba(0, 0, 0, 0.1);
  }

  /* Subtle button background hover effects */
  .main-content a button:hover {
    background-color: #a3c4f3;
  }

  /* Soft shadow effect for the main content */
  .main-content {
    background-color: #ffffff;
    border-radius: 20px;
    padding: 4rem;
    position: relative;
    color: #333;
    box-shadow: 0px 15px 30px rgba(0, 0, 0, 0.1);
  }

  /* Action Button Styling */
  .main-content button {
    font-size: 1.3rem;
    text-transform: uppercase;
  }
  
  /* Button icon styling */
  .main-content button i {
    font-size: 1.5rem;
  }
</style>
