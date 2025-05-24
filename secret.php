<?php
require_once 'header.php';
// Check if user is logged in
if (!isset($_SESSION['username'])) {
    header("Location: login.php");
    exit();
}

?>

    <div class="container">
        <div class="row align-items-center">
            <div class="col-lg-6 mb-4 mb-lg-0">
                <h1 class="display-5 fw-bold mb-3">Welcome, <?php echo htmlspecialchars($_SESSION['first_name']),' ',htmlspecialchars($_SESSION['last_name']); ?>!</h1>
                <p class="lead mb-4">This is a secret page that can only be accessed by users who have logged in.</p>
                <a class="btn btn-primary btn-lg px-4 me-md-2" href="logout.php">Log out</a>
            </div>
        </div>
    </div>


<?php require_once 'footer.php'; ?>