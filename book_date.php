<!-- Navbar -->
<nav class="navbar navbar-expand-lg navbar-dark bg-dark mb-4">
  <div class="container">
    <a class="navbar-brand" href="#">Library Admin</a>
    <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav">
      <span class="navbar-toggler-icon"></span>
    </button>
    <div class="collapse navbar-collapse" id="navbarNav">
      <ul class="navbar-nav ms-auto">
        <li class="nav-item"><a class="nav-link" href="addbook.php">Home</a></li>
        <li class="nav-item"><a class="nav-link active" href="update_book.php">Add book</a></li>
        <li class="nav-item"><a class="nav-link" href="popular_books.php">Add popular Book</a></li>
        <li class="nav-item"><a class="nav-link" href="orders.php">Books bought</a></li>
        <li class="nav-item"><a class="nav-link" href="book_date.php">Fines</a></li>
        <li class="nav-item"><a class="nav-link" href="accounts.php">account create</a></li>
        <li class="nav-item"><a class="nav-link" href="Our Students Say.php">comment manege</a></li>
      </ul>
    </div>
  </div>
</nav>

<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);

// Database connection
$servername = "localhost";
$username   = "root";
$password   = "";
$dbname     = "library_db";
$conn = new mysqli($servername, $username, $password, $dbname);
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

$message = "";

// Handle Delete
if (isset($_GET['delete_id'])) {
    $delete_id = intval($_GET['delete_id']);
    $sql = "DELETE FROM book_orders WHERE id = $delete_id";
    if ($conn->query($sql) === TRUE) {
        $message = "Order deleted successfully.";
    } else {
        $message = "Error deleting order: " . $conn->error;
    }
}

// Fetch all overdue orders (more than 20 days)
$overdue_orders_result = $conn->query("
    SELECT *, DATEDIFF(CURDATE(), book_date) AS days_diff 
    FROM book_orders 
    WHERE DATEDIFF(CURDATE(), book_date) > 20
    ORDER BY book_date ASC
");
$conn->close();
?>
<!DOCTYPE html>
<html>
<head>
    <link href="add.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <title>Overdue Book Orders</title>
    
</head>
<body>

<h1>Overdue Book Orders (Fine Applied)</h1>

<?php if ($message): ?>
    <p><strong><?php echo htmlspecialchars($message); ?></strong></p>
<?php endif; ?>

<table>
    <thead>
        <tr>
            <th>ID</th>
            <th>Student Name</th>
            <th>Book Name</th>
            <th>Book Category</th>
            <th>Book No</th>
            <th>Contact</th>
            <th>Email</th>
            <th>Book Date</th>
            <th>Days Overdue</th>
            <th>Fine (Rs.)</th>
            <th>Action</th>
        </tr>
    </thead>
    <tbody>
    <?php if ($overdue_orders_result && $overdue_orders_result->num_rows > 0): ?>
        <?php while ($row = $overdue_orders_result->fetch_assoc()): 
            $days_overdue = $row['days_diff'] - 20;
            $fine = $days_overdue * 100;
        ?>
        <tr>
            <td><?php echo $row['id']; ?></td>
            <td><?php echo htmlspecialchars($row['student_name']); ?></td>
            <td><?php echo htmlspecialchars($row['book_name']); ?></td>
            <td><?php echo htmlspecialchars($row['book_category']); ?></td>
            <td><?php echo htmlspecialchars($row['book_number']); ?></td>
            <td><?php echo htmlspecialchars($row['contact_number']); ?></td>
            <td><?php echo htmlspecialchars($row['email']); ?></td>
            <td><?php echo htmlspecialchars($row['book_date']); ?></td>
            <td><?php echo $days_overdue; ?></td>
            <td><?php echo number_format($fine, 2); ?></td>
            <td>
                <a href="?delete_id=<?php echo $row['id']; ?>" onclick="return confirm('Delete this overdue order?')">Delete</a>
            </td>
        </tr>
        <?php endwhile; ?>
    <?php else: ?>
        <tr><td colspan="11">No overdue orders found.</td></tr>
    <?php endif; ?>
    </tbody>
</table>

</body>
</html>
