<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="add.css" rel="stylesheet">
    <title>add and update book</title>
</head>
<body>

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
// Show all errors for debugging
error_reporting(E_ALL);
ini_set('display_errors', 1);

$servername = "localhost";
$username   = "root";
$password   = "";
$dbname     = "library_db";

$conn = new mysqli($servername, $username, $password, $dbname);
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

function uploadImage($file) {
    $target_dir = "uploads/";
    if (!is_dir($target_dir)) mkdir($target_dir, 0777, true);

    $file_name = time() . "_" . basename($file["name"]);
    $target_file = $target_dir . $file_name;

    $file_type = strtolower(pathinfo($target_file, PATHINFO_EXTENSION));
    $allowed_types = ["jpg", "jpeg", "png", "gif"];

    if (in_array($file_type, $allowed_types)) {
        if (move_uploaded_file($file["tmp_name"], $target_file)) {
            return $file_name;
        }
    }
    return null;
}

$message = "";
$book = [
    'id' => '',
    'book_name' => '',
    'book_category' => '',
    'book_number' => '',
    'books_available' => 'Yes',
    'book_image' => ''
];

// Load book for editing if requested
if (isset($_GET['edit_id'])) {
    $edit_id = intval($_GET['edit_id']);
    $res = $conn->query("SELECT * FROM books WHERE id = $edit_id LIMIT 1");
    if ($res && $res->num_rows > 0) {
        $book = $res->fetch_assoc();
    } else {
        $message = "Book not found with ID = $edit_id";
    }
}

// Handle form submission (add or update)
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $action = $_POST['action'] ?? '';
    $book_name = $conn->real_escape_string($_POST['book_name']);
    $book_category = $conn->real_escape_string($_POST['book_category']);
    $book_number = $conn->real_escape_string($_POST['book_number']);
    $books_available = $conn->real_escape_string($_POST['books_available']);
    $id = isset($_POST['id']) ? intval($_POST['id']) : 0;

    $book_image = null;
    if (!empty($_FILES["book_image"]["name"])) {
        $upload = uploadImage($_FILES["book_image"]);
        if ($upload !== null) {
            $book_image = $upload;
        } else {
            $message = "Image upload failed or invalid file type.";
        }
    }

    if ($action == 'add') {
        $sql = "INSERT INTO books (book_name, book_category, book_number, books_available, book_image) VALUES (
            '$book_name', '$book_category', '$book_number', '$books_available', " . ($book_image ? "'$book_image'" : "NULL") . ")";
        if ($conn->query($sql) === TRUE) {
            $message = "New book added successfully!";
            $book = ['id'=>'', 'book_name'=>'', 'book_category'=>'', 'book_number'=>'', 'books_available'=>'Yes', 'book_image'=>''];
        } else {
            $message = "Add error: " . $conn->error;
        }
    } elseif ($action == 'update') {
        if ($id > 0) {
            $sql = "UPDATE books SET
                book_name='$book_name',
                book_category='$book_category',
                book_number='$book_number',
                books_available='$books_available'";
            if ($book_image) {
                $sql .= ", book_image='$book_image'";
            }
            $sql .= " WHERE id=$id";

            if ($conn->query($sql) === TRUE) {
                $message = "Book updated successfully!";
                $res = $conn->query("SELECT * FROM books WHERE id = $id LIMIT 1");
                if ($res) $book = $res->fetch_assoc();
            } else {
                $message = "Update error: " . $conn->error;
            }
        } else {
            $message = "Invalid book ID for update.";
        }
    }
}

// Fetch all books for display
$books_result = $conn->query("SELECT * FROM books ORDER BY id DESC");
$conn->close();
?>

<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8" />
<meta name="viewport" content="width=device-width, initial-scale=1" />
<title>Books Management</title>
<style>
    table { border-collapse: collapse; width: 100%; max-width: 900px; }
    th, td { border: 1px solid #ccc; padding: 8px; text-align: left; }
    img { max-width: 80px; max-height: 80px; }
    form { margin-top: 30px; max-width: 500px; }
</style>
</head>
<body>

<h1>Books List</h1>
<?php if ($message): ?>
    <p><strong><?php echo htmlspecialchars($message); ?></strong></p>
<?php endif; ?>

<table>
    <thead>
        <tr>
            <th>ID</th>
            <th>Book Name</th>
            <th>Category</th>
            <th>Book Number</th>
            <th>Available</th>
            <th>Image</th>
            <th>Action</th>
        </tr>
    </thead>
    <tbody>
    <?php if ($books_result && $books_result->num_rows > 0): ?>
        <?php while ($row = $books_result->fetch_assoc()): ?>
        <tr>
            <td><?php echo $row['id']; ?></td>
            <td><?php echo htmlspecialchars($row['book_name']); ?></td>
            <td><?php echo htmlspecialchars($row['book_category']); ?></td>
            <td><?php echo htmlspecialchars($row['book_number']); ?></td>
            <td><?php echo htmlspecialchars($row['books_available']); ?></td>
            <td>
                <?php if (!empty($row['book_image'])): ?>
                    <img src="uploads/<?php echo htmlspecialchars($row['book_image']); ?>" alt="Book Image">
                <?php else: ?>
                    No image
                <?php endif; ?>
            </td>
            <td>
                <a href="?edit_id=<?php echo $row['id']; ?>">Update</a>
            </td>
        </tr>
        <?php endwhile; ?>
    <?php else: ?>
        <tr><td colspan="7">No books found.</td></tr>
    <?php endif; ?>
    </tbody>
</table>

<hr>

<h2><?php echo ($book['id'] ? "Update Book (ID: {$book['id']})" : "Add New Book"); ?></h2>

<form method="POST" enctype="multipart/form-data">
    <?php if ($book['id']): ?>
        <input type="hidden" name="id" value="<?php echo $book['id']; ?>">
    <?php endif; ?>

    <label>Book Name:</label><br>
    <input type="text" name="book_name" value="<?php echo htmlspecialchars($book['book_name']); ?>" required><br><br>

    <label>Category:</label><br>
    <input type="text" name="book_category" value="<?php echo htmlspecialchars($book['book_category']); ?>" required><br><br>

    <label>Book Number:</label><br>
    <input type="text" name="book_number" value="<?php echo htmlspecialchars($book['book_number']); ?>" required><br><br>

    <label>Available:</label><br>
    <select name="books_available" required>
        <option value="Yes" <?php if ($book['books_available'] == "Yes") echo "selected"; ?>>Yes</option>
        <option value="No" <?php if ($book['books_available'] == "No") echo "selected"; ?>>No</option>
    </select><br><br>

    <?php if (!empty($book['book_image'])): ?>
        <label>Current Image:</label><br>
        <img src="uploads/<?php echo htmlspecialchars($book['book_image']); ?>" alt="Book Image" width="120"><br><br>
    <?php endif; ?>

    <label>New Image (optional):</label><br>
    <input type="file" name="book_image" accept="image/*"><br><br>

    <button type="submit" name="action" value="add" <?php if ($book['id']) echo 'disabled title="Editing existing book. Use Update."'; ?>>Add Book</button>
    <button type="submit" name="action" value="update" <?php if (!$book['id']) echo 'disabled title="Select a book to update."'; ?>>Update Book</button>
</form>

</body>
</html>