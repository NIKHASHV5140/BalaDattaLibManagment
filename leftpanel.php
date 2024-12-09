<style>
  /* Left Panel Styling */
  .left-panel {
  width: 20%;
  background-color: #f27f38; /* Updated to orange color */
  padding: 1rem;
  border: 1px solid #ddd;
  font-family: 'Poppins', sans-serif;
  position: relative;
}


  .left-panel ul {
    list-style: none;
    padding: 0;
    margin: 0;
  }

  .left-panel ul li {
    margin-bottom: 0.5rem;
  }

  .left-panel ul li a {
    display: block;
    text-decoration: none;
    color: black; /* White text */
    background-color: #ffffff; /* Orange background */
    padding: 0.8rem 1rem;
    border-radius: 4px;
    font-weight: bold;
    font-size: 1rem;
    text-align: center; /* Center-align text */
  }

  .left-panel ul li a.active {
    background-color: #ffffff; /* Slightly darker orange for active */
  }
</style>



<!-- Left Panel -->
<div class="left-panel">
  <ul>
    <li><a href="main.php" class="active">Home</a></li>
    <li><a href="student_add.php">Add Students</a></li>
    <li><a href="book_add.php">Add Books</a></li>
    <li><a href="book_checkout.php">Book CheckOut</a></li>
    <li><a href="book_checkin.php">Book CheckIn</a></li>
    <li><a href="reports.php">Reports</a></li>
    <li><a href="logout.php">Logout</a></li>
  </ul>
</div>

