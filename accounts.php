

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

$message = "";
$account = [
    'id' => '',
    'name' => '',
    'email' => '',
    'password' => ''  // will not fill password in form for update
];

// Load account for editing if requested
if (isset($_GET['edit_id'])) {
    $edit_id = intval($_GET['edit_id']);
    $res = $conn->query("SELECT id, name, email FROM accounts WHERE id = $edit_id LIMIT 1");
    if ($res && $res->num_rows > 0) {
        $account = $res->fetch_assoc();
    } else {
        $message = "Account not found with ID = $edit_id";
    }
}

// Handle form submission (add or update)
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $action = $_POST['action'] ?? '';
    $name  = $conn->real_escape_string($_POST['name']);
    $email = $conn->real_escape_string($_POST['email']);
    $password_raw = $_POST['password'] ?? '';
    $id = isset($_POST['id']) ? intval($_POST['id']) : 0;

    // Validate basic inputs
    if (empty($name) || empty($email) || ($action == 'add' && empty($password_raw))) {
        $message = "Name, Email, and Password (for add) are required.";
    } else {
        // Hash password if provided (for add or update)
        $password_hashed = null;
        if (!empty($password_raw)) {
            $password_hashed = password_hash($password_raw, PASSWORD_DEFAULT);
        }

        if ($action == 'add') {
            // Check email uniqueness
            $check = $conn->query("SELECT id FROM accounts WHERE email = '$email'");
            if ($check && $check->num_rows > 0) {
                $message = "Email already exists. Please use a different email.";
            } else {
                // Insert new account
                $sql = "INSERT INTO accounts (name, email, password) VALUES ('$name', '$email', '$password_hashed')";
                if ($conn->query($sql) === TRUE) {
                    $message = "New account created successfully!";
                    $account = ['id'=>'', 'name'=>'', 'email'=>'', 'password'=>''];
                } else {
                    $message = "Add error: " . $conn->error;
                }
            }
        } elseif ($action == 'update') {
            if ($id > 0) {
                // Check email uniqueness excluding current record
                $check = $conn->query("SELECT id FROM accounts WHERE email = '$email' AND id != $id");
                if ($check && $check->num_rows > 0) {
                    $message = "Email already exists for another account.";
                } else {
                    $sql = "UPDATE accounts SET name='$name', email='$email'";
                    if ($password_hashed !== null) {
                        $sql .= ", password='$password_hashed'";
                    }
                    $sql .= " WHERE id=$id";

                    if ($conn->query($sql) === TRUE) {
                        $message = "Account updated successfully!";
                        $res = $conn->query("SELECT id, name, email FROM accounts WHERE id = $id LIMIT 1");
                        if ($res) $account = $res->fetch_assoc();
                    } else {
                        $message = "Update error: " . $conn->error;
                    }
                }
            } else {
                $message = "Invalid account ID for update.";
            }
        }
    }
}

// Fetch all accounts for display
$accounts_result = $conn->query("SELECT id, name, email FROM accounts ORDER BY id DESC");
$conn->close();
?>

<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8" />
<meta name="viewport" content="width=device-width, initial-scale=1" />
<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">

<title>Account Management</title>

<style>
/* Background style for both navbar and page */
body, .navbar {
  background-image: url("uploads/istockphoto-1290063471-612x612-removebg-preview.png.gif"),
                    url("uploads/background.jpg.gif");
  background-position: right bottom, left top;
  background-repeat: no-repeat, repeat;
}

/* Wrap content in padding */
#example1 {
  padding: 15px;
}

/* Navbar link styling */
.navbar .nav-link {
  color: #f2f2f2 !important;
  padding: 14px 16px;
}
.navbar .nav-link:hover {
  background-color: #ddd !important;
  color: rgb(28, 38, 230) !important;
}

/* Table styling */
table {
  border-collapse: collapse;
  width: 100%;
  max-width: 900px;
  background-color: white;
}
table th, table td {
  border: 1px solid #ccc;
  padding: 8px;
}
table th {
  background-color: #199def;
  color: white;
}
table tr:nth-child(even) {
  background-color: #f2f2f2;
}
table tr:hover {
  background-color: #ddd;
}

/* Form styling */
input[type=text], select, input[type=file], input[type=email], input[type=password] {
  width: 100%;
  padding: 12px 20px;
  margin: 8px 0;
  border: 1px solid #ccc;
  border-radius: 4px;
}
button[type=submit] {
  background-color: #199def;
  color: white;
  padding: 14px 20px;
  margin: 8px 0;
  border: none;
  border-radius: 4px;
  cursor: pointer;
}
button[type=submit]:hover {
  background-color: #0b7dda;
}
</style>
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

<div id="example1">

<h1>Accounts List</h1>
<?php if ($message): ?>
    <p><strong><?php echo htmlspecialchars($message); ?></strong></p>
<?php endif; ?>

<table>
    <thead>
        <tr>
            <th>ID</th>
            <th>Name</th>
            <th>Email</th>
            <th>Action</th>
        </tr>
    </thead>
    <tbody>
    <?php if ($accounts_result && $accounts_result->num_rows > 0): ?>
        <?php while ($row = $accounts_result->fetch_assoc()): ?>
        <tr>
            <td><?php echo $row['id']; ?></td>
            <td><?php echo htmlspecialchars($row['name']); ?></td>
            <td><?php echo htmlspecialchars($row['email']); ?></td>
            <td>
                <a href="?edit_id=<?php echo $row['id']; ?>">Update</a>
            </td>
        </tr>
        <?php endwhile; ?>
    <?php else: ?>
        <tr><td colspan="4">No accounts found.</td></tr>
    <?php endif; ?>
    </tbody>
</table>

<hr>

<h2><?php echo ($account['id'] ? "Update Account (ID: {$account['id']})" : "Create New Account"); ?></h2>

<form method="POST" autocomplete="off">
    <?php if ($account['id']): ?>
        <input type="hidden" name="id" value="<?php echo $account['id']; ?>">
    <?php endif; ?>

    <label>Name:</label><br>
    <input type="text" name="name" value="<?php echo htmlspecialchars($account['name']); ?>" required><br><br>

    <label>Email:</label><br>
    <input type="email" name="email" value="<?php echo htmlspecialchars($account['email']); ?>" required><br><br>

    <label>Password: </label><br>
    <input type="password" name="password" <?php echo ($account['id'] ? '' : 'required'); ?> placeholder="<?php echo ($account['id'] ? 'Leave blank to keep current password' : 'Enter password'); ?>"><br><br>

    <button type="submit" name="action" value="add" <?php if ($account['id']) echo 'disabled title="Editing existing account. Use Update."'; ?>>Create Account</button>
    <button type="submit" name="action" value="update" <?php if (!$account['id']) echo 'disabled title="Select an account to update."'; ?>>Update Account</button>
</form>

</body>
</html>
