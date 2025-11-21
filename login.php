<?php
session_start();

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
    $email = trim($_POST['email']);
    $pass  = $_POST['password'];

    if (!empty($email) && !empty($pass)) {
        $stmt = $conn->prepare("SELECT id, name, password FROM users WHERE email = ?");
        $stmt->bind_param("s", $email);
        $stmt->execute();
        $stmt->store_result();

        if ($stmt->num_rows > 0) {
            $stmt->bind_result($id, $name, $hashed_pass);
            $stmt->fetch();

            if (password_verify($pass, $hashed_pass)) {
                $_SESSION['user_id'] = $id;
                $_SESSION['user_name'] = $name;
                header("Location: addbook.php");
                exit();
            } else {
                $message = "Invalid password.";
            }
        } else {
            $message = "Email not found.";
        }
        $stmt->close();
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
    <title>Login Account</title>
</head>
<body>
    <div class="form-container">
    <h2>Login</h2>
    <?php if ($message) echo "<p><strong>$message</strong></p>"; ?>
    <form method="post" action="">
        Email: <input type="email" name="email" required><br><br>
        Password: <input type="password" name="password" required><br><br>
        <button type="submit">Login</button>
    </form>
    </div>
</body>
</html>
