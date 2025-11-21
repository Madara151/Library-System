<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <title>B/ Dhammananda central college</title>
    <meta content="width=device-width, initial-scale=1.0" name="viewport">
    <meta content="" name="keywords">
    <meta content="" name="description">

    <!-- Favicon -->
    <link href="img/favicon.ico" rel="icon">

    <!-- Google Web Fonts -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Heebo:wght@400;500;600&family=Nunito:wght@600;700;800&display=swap" rel="stylesheet">

    <!-- Icon Font Stylesheet -->
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.10.0/css/all.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.4.1/font/bootstrap-icons.css" rel="stylesheet">

    <!-- Libraries Stylesheet -->
    <link href="lib/animate/animate.min.css" rel="stylesheet">
    <link href="lib/owlcarousel/assets/owl.carousel.min.css" rel="stylesheet">

    <!-- Customized Bootstrap Stylesheet -->
    <link href="css/bootstrap.min.css" rel="stylesheet">

    <!-- Template Stylesheet -->
    <link href="css/style.css" rel="stylesheet">
</head>

<body>
    <!-- Spinner Start -->
    <div id="spinner" class="show bg-white position-fixed translate-middle w-100 vh-100 top-50 start-50 d-flex align-items-center justify-content-center">
        <div class="spinner-border text-primary" style="width: 3rem; height: 3rem;" role="status">
            <span class="sr-only">Loading...</span>
        </div>
    </div>
    <!-- Spinner End -->


    <!-- Navbar Start -->
    <nav class="navbar navbar-expand-lg bg-white navbar-light shadow sticky-top p-0">
        <a href="index.php" class="navbar-brand d-flex align-items-center px-4 px-lg-5">
            <h2 class="m-0 text-primary"><i class="fa fa-book me-3"></i>B/ Dhammananda central college</h2>
        </a>
        <button type="button" class="navbar-toggler me-4" data-bs-toggle="collapse" data-bs-target="#navbarCollapse">
            <span class="navbar-toggler-icon"></span>
        </button>
        <div class="collapse navbar-collapse" id="navbarCollapse">
            <div class="navbar-nav ms-auto p-4 p-lg-0">
                <a href="index.php" class="nav-item nav-link active">Home</a>
                <a href="courses.php" class="nav-item nav-link">Books</a>
                <div class="nav-item dropdown">
                    <a href="#" class="nav-link dropdown-toggle" data-bs-toggle="dropdown">Pages</a>
                    <div class="dropdown-menu fade-down m-0">
                        <a href="testimonial.php" class="dropdown-item">Testimonial</a>
                        <a href="comment.php" class="dropdown-item">student comment</a>
                    </div>
                </div>
                <a href="#" class="nav-item nav-link"></a>
                <a href="#" class="nav-item nav-link"></a>
            </div>
    </nav>
    <!-- Navbar End -->


    <!-- Header Start -->
    <div class="container-fluid bg-primary py-5 mb-5 page-header">
        <div class="container py-5">
            <div class="row justify-content-center">
                <div class="col-lg-10 text-center">
                    <h1 class="display-3 text-white animated slideInDown">Books</h1>
                    <nav aria-label="breadcrumb">
                        <ol class="breadcrumb justify-content-center">
                            <li class="breadcrumb-item"><a class="text-white" href="index.php">Home</a></li>
                            <li class="breadcrumb-item"><a class="text-white" href="#">Pages</a></li>
                            <li class="breadcrumb-item text-white active" aria-current="page">Books</li>
                        </ol>
                    </nav>
                </div>
            </div>
        </div>
    </div>
    <!-- Header End -->


       <!-- Categories Start -->
    <?php
$servername = "localhost";
$username   = "root";
$password   = "";
$dbname     = "library_db";

// Connect to DB
$conn = new mysqli($servername, $username, $password, $dbname);
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Fetch all books
$sql = "SELECT * FROM books ORDER BY id DESC";
$result = $conn->query($sql);
?>

<div class="container-xxl py-5 category">
    <div class="container">
        <div class="text-center wow fadeInUp" data-wow-delay="0.1s">
            <h6 class="section-title bg-white text-center text-primary px-3">Categories</h6>
            <h1 class="mb-5">Book Categories</h1>
        </div>
        <div class="row g-3">
            <div class="col-lg-7 col-md-6">
                <div class="row g-3">
                    <?php 
                    if ($result && $result->num_rows > 0) {
                        $delay = 0.1;
                        while ($row = $result->fetch_assoc()) {
                            // Image path
                            $image = !empty($row['book_image']) ? 'uploads/' . htmlspecialchars($row['book_image']) : 'img/default.jpg';
                            ?>
                            <div class="col-lg-6 col-md-12 wow zoomIn" data-wow-delay="<?php echo $delay; ?>s">
                                <a class="position-relative d-block overflow-hidden" href="#">
                                    <img class="img-fluid" src="<?php echo $image; ?>" alt="">
                                    <div class="bg-white text-center position-absolute bottom-0 end-0 py-2 px-3" style="margin: 1px;">
                                        <h5 class="m-0"><?php echo htmlspecialchars($row['book_name']); ?></h5>
                                        <small class="text-primary"><?php echo htmlspecialchars($row['book_category']); ?></small>
                                    </div>
                                </a>
                            </div>
                            <?php
                            $delay += 0.2; // increment animation delay
                        }
                    } else {
                        echo "<p>No books found.</p>";
                    }
                    ?>
                </div>
            </div>
        </div>
    </div>
</div>

<?php $conn->close(); ?>

    <!-- Categories Start -->


    <!-- Courses Start -->
   <?php
$servername = "localhost";
$username   = "root";
$password   = "";
$dbname     = "library_db";

$conn = new mysqli($servername, $username, $password, $dbname);
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

$result = $conn->query("SELECT * FROM popular_books ORDER BY id DESC");
?>

<div class="container-xxl py-5">
    <div class="container">
        <div class="text-center wow fadeInUp" data-wow-delay="0.1s">
            <h6 class="section-title bg-white text-center text-primary px-3">Categories</h6>
            <h1 class="mb-5">Popular Books</h1>
        </div>
        <div class="row g-4 justify-content-center">
            <?php 
            if ($result && $result->num_rows > 0) {
                $delay = 0.1;
                while ($row = $result->fetch_assoc()) {
                    $image = !empty($row['book_image']) ? 'uploads/' . htmlspecialchars($row['book_image']) : 'img/default.jpg';
                    ?>
                    <div class="col-lg-4 col-md-6 wow fadeInUp" data-wow-delay="<?php echo $delay; ?>s">
                        <div class="course-item bg-light">
                            <div class="position-relative overflow-hidden">
                                <img class="img-fluid" src="<?php echo $image; ?>" alt="<?php echo htmlspecialchars($row['book_name']); ?>">
                            </div>
                            <div class="text-center p-4 pb-0">
                               
                                <h5 class="mb-4"><?php echo htmlspecialchars($row['book_name']); ?></h5>
                                <p class="text-muted"><?php echo htmlspecialchars($row['book_category']); ?></p>
                            </div>
                        </div>
                    </div>
                    <?php
                    $delay += 0.2;
                }
            } else {
                echo "<p class='text-center'>No popular books found.</p>";
            }
            ?>
        </div>
    </div>
</div>

<?php $conn->close(); ?>

    <!-- Courses End -->

<!-- Testimonial Start -->
   <?php
$servername = "localhost";
$username   = "root";
$password   = "";
$dbname     = "library_db";

$conn = new mysqli($servername, $username, $password, $dbname);
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Fetch all student testimonials
$result = $conn->query("SELECT * FROM students_say ORDER BY id DESC");
?>

<div class="container-xxl py-5 wow fadeInUp" data-wow-delay="0.1s">
    <div class="container">
        <div class="text-center">
            <h6 class="section-title bg-white text-center text-primary px-3">Comment</h6>
            <h1 class="mb-5">Our Students Say!</h1>
        </div>
        <div class="owl-carousel testimonial-carousel position-relative">
            <?php 
            if ($result && $result->num_rows > 0) {
                while ($row = $result->fetch_assoc()) {
                    $image = !empty($row['student_image']) 
                        ? 'uploads/' . htmlspecialchars($row['student_image']) 
                        : 'img/default-avatar.png'; // fallback image
                    ?>
                    <div class="testimonial-item text-center">
                        <img class="border rounded-circle p-2 mx-auto mb-3" 
                             src="<?php echo $image; ?>" 
                             alt="<?php echo htmlspecialchars($row['student_name']); ?>" 
                             style="width: 80px; height: 80px;">
                        <h5 class="mb-0"><?php echo htmlspecialchars($row['student_name']); ?></h5>
                        <p>Student</p>
                        <div class="testimonial-text bg-light text-center p-4">
                            <p class="mb-0"><?php echo nl2br(htmlspecialchars($row['student_comment'])); ?></p>
                        </div>
                    </div>
                    <?php
                }
            } else {
                echo "<p class='text-center'>No student testimonials found.</p>";
            }
            ?>
        </div>
    </div>
</div>

<?php $conn->close(); ?>

    <!-- Testimonial End -->
        

<!-- Footer Start -->
    <div class="container-fluid bg-dark text-light footer pt-5 mt-5 wow fadeIn" data-wow-delay="0.1s">
        <div class="container py-5">
            <div class="row g-5">
                <div class="col-lg-3 col-md-6">
                    <h4 class="text-white mb-3">Zeynax Programmers</h4>
                </div>
                <div class="col-lg-3 col-md-6">
                    <h4 class="text-white mb-3">Contact</h4>
                    <p class="mb-2"><i class="fa fa-map-marker-alt me-3"></i>123 Street, New York, USA</p>
                    <p class="mb-2"><i class="fa fa-phone-alt me-3"></i>+012 345 67890</p>
                    <p class="mb-2"><i class="fa fa-envelope me-3"></i>info@example.com</p>
                </div>
                <div class="col-lg-3 col-md-6">
                    <h4 class="text-white mb-3">Gallery</h4>
                    <div class="row g-2 pt-2">
                        <div class="col-4">
                            <img class="img-fluid bg-light p-1" src="img/course-1.jpg" alt="">
                        </div>
                        <div class="col-4">
                            <img class="img-fluid bg-light p-1" src="img/course-2.jpg" alt="">
                        </div>
                        <div class="col-4">
                            <img class="img-fluid bg-light p-1" src="img/course-3.jpg" alt="">
                        </div>
                        <div class="col-4">
                            <img class="img-fluid bg-light p-1" src="img/course-2.jpg" alt="">
                        </div>
                        <div class="col-4">
                            <img class="img-fluid bg-light p-1" src="img/course-3.jpg" alt="">
                        </div>
                        <div class="col-4">
                            <img class="img-fluid bg-light p-1" src="img/course-1.jpg" alt="">
                        </div>
                    </div>
                </div>
                </div>
            </div>
        </div>
    </div>
    <!-- Footer End -->


    <!-- Back to Top -->
    <a href="#" class="btn btn-lg btn-primary btn-lg-square back-to-top"><i class="bi bi-arrow-up"></i></a>


    <!-- JavaScript Libraries -->
    <script src="https://code.jquery.com/jquery-3.4.1.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.0/dist/js/bootstrap.bundle.min.js"></script>
    <script src="lib/wow/wow.min.js"></script>
    <script src="lib/easing/easing.min.js"></script>
    <script src="lib/waypoints/waypoints.min.js"></script>
    <script src="lib/owlcarousel/owl.carousel.min.js"></script>

    <!-- Template Javascript -->
    <script src="js/main.js"></script>
</body>

</html>