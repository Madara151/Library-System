<?php
// Database connection
$servername = "localhost";
$username   = "root";
$password   = "";
$dbname     = "library_db"; // change if needed
$conn = new mysqli($servername, $username, $password, $dbname);

// Check DB connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

$message = "";

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $name  = trim($_POST['name']);
    $email = trim($_POST['email']);
    $pass  = $_POST['password'];

    if (!empty($name) && !empty($email) && !empty($pass)) {
        // Check if email already exists
        $check = $conn->prepare("SELECT id FROM users WHERE email = ?");
        $check->bind_param("s", $email);
        $check->execute();
        $check->store_result();

        if ($check->num_rows > 0) {
            $message = "Email already registered!";
        } else {
            // Hash password
            $hashed_pass = password_hash($pass, PASSWORD_DEFAULT);

            // Insert new user
            $stmt = $conn->prepare("INSERT INTO users (name, email, password) VALUES (?, ?, ?)");
            $stmt->bind_param("sss", $name, $email, $hashed_pass);

            if ($stmt->execute()) {
                $message = "Registration successful! ";
            } else {
                $message = "Error: " . $stmt->error;
            }
            $stmt->close();
        }
        $check->close();
    } else {
        $message = "All fields are required.";
    }
}

$conn->close();
?>
<!DOCTYPE html>
<html>
<head>
    <link href="aaa.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    
    <title>Register Account</title>
</head>
<body>
    <div class="form-container">
    <h2>Register</h2>
    <?php if ($message) echo "<p><strong>$message</strong></p>"; ?>
    <form method="post" action="">
        Name: <input type="text" name="name" required><br><br>
        Email: <input type="email" name="email" required><br><br>
        Password: <input type="password" name="password" required><br><br>
        <button type="submit">Register</button> <br>
        <p>Are you register</p> <a href='login.php'>Login here</a>
    </form>
    </div>
</body>
</html>
