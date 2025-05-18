<?php
$page_title = "Dashboard - Login System";
require_once 'header.php';

// Check if user is logged in
if (!isset($_SESSION['username'])) {
    header("Location: login.php");
    exit();
}
?>

<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card shadow-sm">
                <div class="card-body p-4">
                    <h1 class="card-title mb-4">Welcome to Your Dashboard, <?php echo isset($_SESSION['first_name']) ? htmlspecialchars($_SESSION['first_name']) : htmlspecialchars($_SESSION['username']); ?>!</h1>

                    <div class="alert alert-success">
                        <p class="mb-0">You are successfully logged in. This is your personal dashboard.</p>
                    </div>

                    <div class="row mt-4">
                        <div class="col-md-6 mb-3">
                            <div class="card h-100">
                                <div class="card-body">
                                    <h5 class="card-title">Account Information</h5>
                                    <?php if (isset($_SESSION['profile_picture']) && !empty($_SESSION['profile_picture']) && file_exists($_SESSION['profile_picture'])): ?>
                                        <div class="text-center mb-3">
                                            <img src="<?php echo htmlspecialchars($_SESSION['profile_picture']); ?>" alt="Profile Picture" class="img-thumbnail rounded-circle" style="width: 100px; height: 100px; object-fit: cover;">
                                        </div>
                                    <?php endif; ?>
                                    <p class="card-text">Username: <?php echo htmlspecialchars($_SESSION['username']); ?></p>
                                    <?php if (isset($_SESSION['first_name']) && isset($_SESSION['last_name'])): ?>
                                        <p class="card-text">Name: <?php echo htmlspecialchars($_SESSION['first_name']) . ' ' . htmlspecialchars($_SESSION['last_name']); ?></p>
                                    <?php endif; ?>
                                    <p class="card-text">Email: <?php echo htmlspecialchars($_SESSION['email']); ?></p>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-6 mb-3">
                            <div class="card h-100">
                                <div class="card-body">
                                    <h5 class="card-title">Quick Actions</h5>
                                    <ul class="list-group list-group-flush">
                                        <li class="list-group-item"><a href="edit_profile.php" class="text-decoration-none">Edit Profile</a></li>
                                        <li class="list-group-item"><a href="site_settings.php" class="text-decoration-none">Site Settings</a></li>
                                        <li class="list-group-item"><a href="change_password.php" class="text-decoration-none">Change Password</a></li>
                                        <li class="list-group-item"><a href="logout.php" class="text-decoration-none text-danger">Logout</a></li>
                                    </ul>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<?php require_once 'footer.php'; ?>
