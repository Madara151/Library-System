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
$edit_order = null;

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

// Handle Search
$search_query = "";
if (isset($_GET['search']) && !empty($_GET['search'])) {
    $search_value = $conn->real_escape_string($_GET['search']);
    $search_query = "WHERE student_name LIKE '%$search_value%' OR book_name LIKE '%$search_value%' OR book_category LIKE '%$search_value%'";
}

// Handle Edit (fetch data for update form)
if (isset($_GET['edit_id'])) {
    $edit_id = intval($_GET['edit_id']);
    $result = $conn->query("SELECT * FROM book_orders WHERE id = $edit_id");
    if ($result && $result->num_rows > 0) {
        $edit_order = $result->fetch_assoc();
    }
}

// Handle Add/Update Order
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $student_name   = $conn->real_escape_string($_POST['student_name']);
    $book_name      = $conn->real_escape_string($_POST['book_name']);
    $book_category  = $conn->real_escape_string($_POST['book_category']);
    $book_number    = $conn->real_escape_string($_POST['book_number']);
    $contact_number = $conn->real_escape_string($_POST['contact_number']);
    $email          = $conn->real_escape_string($_POST['email']);
    $book_date      = $conn->real_escape_string($_POST['book_date']);

    if ($_POST['action'] == "add") {
        $sql = "INSERT INTO book_orders (student_name, book_name, book_category, book_number, contact_number, email, book_date)
                VALUES ('$student_name', '$book_name', '$book_category', '$book_number', '$contact_number', '$email', '$book_date')";
        $message = $conn->query($sql) ? "Order added successfully." : "Error adding order: " . $conn->error;
    } elseif ($_POST['action'] == "update" && isset($_POST['id'])) {
        $id = intval($_POST['id']);
        $sql = "UPDATE book_orders 
                SET student_name='$student_name', book_name='$book_name', book_category='$book_category', 
                    book_number='$book_number', contact_number='$contact_number', email='$email', book_date='$book_date'
                WHERE id = $id";
        $message = $conn->query($sql) ? "Order updated successfully." : "Error updating order: " . $conn->error;
    }
}

// Fetch Orders
$orders_result = $conn->query("SELECT * FROM book_orders $search_query ORDER BY id DESC");
$conn->close();
?>
<!DOCTYPE html>
<html>
<head>
    <link href="add.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <title>Book Orders</title>
</head>
<body>

<h1>Book Orders</h1>

<?php if ($message): ?>
    <p><strong><?php echo htmlspecialchars($message); ?></strong></p>
<?php endif; ?>

<!-- Search Form -->
<form method="GET">
    <input type="text" name="search" placeholder="Search by name, book, or category" value="<?php echo isset($_GET['search']) ? htmlspecialchars($_GET['search']) : ''; ?>">
    <button type="submit">Find</button>
    <a href="yourpage.php">Reset</a>
</form>

<!-- Orders Table -->
<table>
    <thead>
        <tr>
            <th>ID</th>
            <th>Student Name</th>
            <th>Book Name</th>
            <th>Category</th>
            <th>Book No</th>
            <th>Contact</th>
            <th>Email</th>
            <th>Book Date</th>
            <th>Action</th>
        </tr>
    </thead>
    <tbody>
    <?php if ($orders_result && $orders_result->num_rows > 0): ?>
        <?php while ($row = $orders_result->fetch_assoc()): ?>
        <tr>
            <td><?php echo $row['id']; ?></td>
            <td><?php echo htmlspecialchars($row['student_name']); ?></td>
            <td><?php echo htmlspecialchars($row['book_name']); ?></td>
            <td><?php echo htmlspecialchars($row['book_category']); ?></td>
            <td><?php echo htmlspecialchars($row['book_number']); ?></td>
            <td><?php echo htmlspecialchars($row['contact_number']); ?></td>
            <td><?php echo htmlspecialchars($row['email']); ?></td>
            <td><?php echo htmlspecialchars($row['book_date']); ?></td>
            <td>
                <a href="?edit_id=<?php echo $row['id']; ?>">Update</a> |
                <a href="?delete_id=<?php echo $row['id']; ?>" onclick="return confirm('Delete this order?')">Delete</a>
            </td>
        </tr>
        <?php endwhile; ?>
    <?php else: ?>
        <tr><td colspan="9">No orders found.</td></tr>
    <?php endif; ?>
    </tbody>
</table>

<hr>

<!-- Add/Update Order Form -->
<h2><?php echo $edit_order ? "Update Order" : "Add New Order"; ?></h2>
<form method="POST">
    <?php if ($edit_order): ?>
        <input type="hidden" name="id" value="<?php echo $edit_order['id']; ?>">
    <?php endif; ?>

    <label>Student Name:</label><br>
    <input type="text" name="student_name" value="<?php echo $edit_order['student_name'] ?? ''; ?>" required><br><br>

    <label>Book Name:</label><br>
    <input type="text" name="book_name" value="<?php echo $edit_order['book_name'] ?? ''; ?>" required><br><br>

    <label>Book Category:</label><br>
    <input type="text" name="book_category" value="<?php echo $edit_order['book_category'] ?? ''; ?>" required><br><br>

    <label>Book Number:</label><br>
    <input type="text" name="book_number" value="<?php echo $edit_order['book_number'] ?? ''; ?>" required><br><br>

    <label>Contact Number:</label><br>
    <input type="text" name="contact_number" value="<?php echo $edit_order['contact_number'] ?? ''; ?>" required><br><br>

    <label>Email:</label><br>
    <input type="email" name="email" value="<?php echo $edit_order['email'] ?? ''; ?>" required><br><br>

    <label>Book Date:</label><br>
    <input type="date" name="book_date" value="<?php echo $edit_order['book_date'] ?? ''; ?>" required><br><br>

    <button type="submit" name="action" value="<?php echo $edit_order ? 'update' : 'add'; ?>">
        <?php echo $edit_order ? 'Update Order' : 'Add Order'; ?>
    </button>
</form>

</body>
</html>
