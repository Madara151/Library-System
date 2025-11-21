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
error_reporting(E_ALL);
ini_set('display_errors', 1);

$servername = "localhost";
$username   = "root";
$password   = "";
$dbname     = "library_db"; // change if different

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

// Handle delete
if (isset($_GET['delete_id'])) {
    $delete_id = intval($_GET['delete_id']);
    // Delete image file if exists
    $res = $conn->query("SELECT student_image FROM students_say WHERE id = $delete_id");
    if ($res && $res->num_rows > 0) {
        $row = $res->fetch_assoc();
        if (!empty($row['student_image']) && file_exists("uploads/" . $row['student_image'])) {
            unlink("uploads/" . $row['student_image']);
        }
    }
    $conn->query("DELETE FROM students_say WHERE id = $delete_id");
    $message = "Student comment deleted successfully.";
}

// Handle add
if ($_SERVER["REQUEST_METHOD"] == "POST" && ($_POST['action'] ?? '') == 'add') {
    $student_name = $conn->real_escape_string($_POST['student_name']);
    $student_comment = $conn->real_escape_string($_POST['student_comment']);

    $student_image = null;
    if (!empty($_FILES["student_image"]["name"])) {
        $upload = uploadImage($_FILES["student_image"]);
        if ($upload !== null) {
            $student_image = $upload;
        } else {
            $message = "Image upload failed or invalid file type.";
        }
    }

    $sql = "INSERT INTO students_say (student_name, student_comment, student_image) VALUES (
        '$student_name', '$student_comment', " . ($student_image ? "'$student_image'" : "NULL") . ")";
    if ($conn->query($sql) === TRUE) {
        $message = "New student comment added successfully!";
    } else {
        $message = "Add error: " . $conn->error;
    }
}

// Fetch all students
$students_result = $conn->query("SELECT * FROM students_say ORDER BY id DESC");
$conn->close();
?>
<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8" />
<meta name="viewport" content="width=device-width, initial-scale=1" />
<link href="add.css" rel="stylesheet">
<title>Our Students Say</title>
</head>
<body>

<h1>Our Students Say</h1>
<?php if ($message): ?>
    <p><strong><?php echo htmlspecialchars($message); ?></strong></p>
<?php endif; ?>

<table>
    <thead>
        <tr>
            <th>ID</th>
            <th>Student Name</th>
            <th>Comment</th>
            <th>Image</th>
            <th>Action</th>
        </tr>
    </thead>
    <tbody>
    <?php if ($students_result && $students_result->num_rows > 0): ?>
        <?php while ($row = $students_result->fetch_assoc()): ?>
        <tr>
            <td><?php echo $row['id']; ?></td>
            <td><?php echo htmlspecialchars($row['student_name']); ?></td>
            <td><?php echo nl2br(htmlspecialchars($row['student_comment'])); ?></td>
            <td>
                <?php if (!empty($row['student_image'])): ?>
                    <img src="uploads/<?php echo htmlspecialchars($row['student_image']); ?>" alt="Student Image">
                <?php else: ?>
                    No image
                <?php endif; ?>
            </td>
            <td>
                <a href="?delete_id=<?php echo $row['id']; ?>" onclick="return confirm('Are you sure you want to delete this comment?')">Delete</a>
            </td>
        </tr>
        <?php endwhile; ?>
    <?php else: ?>
        <tr><td colspan="5">No students found.</td></tr>
    <?php endif; ?>
    </tbody>
</table>

<hr>

<h2>Add New Student Comment</h2>
<form method="POST" enctype="multipart/form-data">
    <label>Student Name:</label><br>
    <input type="text" name="student_name" required><br><br>

    <label>Comment:</label><br>
    <textarea name="student_comment" rows="4" required></textarea><br><br>

    <label>Image (optional):</label><br>
    <input type="file" name="student_image" accept="image/*"><br><br>

    <button type="submit" name="action" value="add">Add Student</button>
</form>

</body>
</html>
