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

$servername = "localhost";
$username = "root";
$password = "";
$dbname = "library_db";

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

    if (in_array($file_type, $allowed_types) && move_uploaded_file($file["tmp_name"], $target_file)) {
        return $file_name;
    }
    return null;
}

$message = "";
$book = ['id' => '', 'book_name' => '', 'book_category' => '', 'book_image' => ''];

// Edit mode
if (isset($_GET['edit_id'])) {
    $edit_id = intval($_GET['edit_id']);
    $res = $conn->query("SELECT * FROM popular_books WHERE id = $edit_id LIMIT 1");
    if ($res && $res->num_rows > 0) {
        $book = $res->fetch_assoc();
    } else {
        $message = "Book not found.";
    }
}

// Add / Update
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $book_name = $conn->real_escape_string($_POST['book_name']);
    $book_category = $conn->real_escape_string($_POST['book_category']);
    $id = isset($_POST['id']) ? intval($_POST['id']) : 0;

    $book_image = $book['book_image'] ?? null;
    if (!empty($_FILES["book_image"]["name"])) {
        $upload = uploadImage($_FILES["book_image"]);
        if ($upload) {
            $book_image = $upload;
        } else {
            $message = "Image upload failed or invalid type.";
        }
    }

    if (isset($_POST['add'])) {
        $sql = "INSERT INTO popular_books (book_name, book_category, book_image) VALUES ('$book_name', '$book_category', '$book_image')";
        if ($conn->query($sql)) {
            $message = "Book added successfully!";
        } else {
            $message = "Error adding book: " . $conn->error;
        }
    } elseif (isset($_POST['update']) && $id > 0) {
        $sql = "UPDATE popular_books SET book_name='$book_name', book_category='$book_category', book_image='$book_image' WHERE id=$id";
        if ($conn->query($sql)) {
            $message = "Book updated successfully!";
        } else {
            $message = "Error updating book: " . $conn->error;
        }
    }
}

$books_result = $conn->query("SELECT * FROM popular_books ORDER BY id DESC");
?>
<!DOCTYPE html>
<html>
<head>
    <link href="add.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <title>Popular Books</title>
</head>
<body>

<h1>Popular Books</h1>

<?php if ($message): ?>
<p><strong><?php echo htmlspecialchars($message); ?></strong></p>
<?php endif; ?>

<table>
    <tr>
        <th>ID</th>
        <th>Book Name</th>
        <th>Category</th>
        <th>Image</th>
        <th>Action</th>
    </tr>
    <?php if ($books_result && $books_result->num_rows > 0): ?>
        <?php while ($row = $books_result->fetch_assoc()): ?>
        <tr>
            <td><?php echo $row['id']; ?></td>
            <td><?php echo htmlspecialchars($row['book_name']); ?></td>
            <td><?php echo htmlspecialchars($row['book_category']); ?></td>
            <td>
                <?php if ($row['book_image']): ?>
                    <img src="uploads/<?php echo htmlspecialchars($row['book_image']); ?>">
                <?php else: ?>
                    No Image
                <?php endif; ?>
            </td>
            <td><a href="?edit_id=<?php echo $row['id']; ?>">Update</a></td>
        </tr>
        <?php endwhile; ?>
    <?php else: ?>
        <tr><td colspan="5">No books found.</td></tr>
    <?php endif; ?>
</table>

<h2><?php echo $book['id'] ? "Update Book" : "Add New Book"; ?></h2>
<form method="POST" enctype="multipart/form-data">
    <?php if ($book['id']): ?>
        <input type="hidden" name="id" value="<?php echo $book['id']; ?>">
    <?php endif; ?>

    <label>Book Name:</label><br>
    <input type="text" name="book_name" value="<?php echo htmlspecialchars($book['book_name']); ?>" required><br><br>

    <label>Book Category:</label><br>
    <input type="text" name="book_category" value="<?php echo htmlspecialchars($book['book_category']); ?>" required><br><br>

    <?php if ($book['book_image']): ?>
        <label>Current Image:</label><br>
        <img src="uploads/<?php echo htmlspecialchars($book['book_image']); ?>" width="100"><br><br>
    <?php endif; ?>

    <label>Book Image:</label><br>
    <input type="file" name="book_image" accept="image/*"><br><br>

    <?php if ($book['id']): ?>
        <button type="submit" name="update">Update Book</button>
    <?php else: ?>
        <button type="submit" name="add">Add Book</button>
    <?php endif; ?>
</form>

</body>
</html>
