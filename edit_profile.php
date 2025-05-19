<?php
$page_title = "Edit Profile - Login System";
require_once 'header.php';

// Check if user is logged in
if (!isset($_SESSION['username'])) {
    header("Location: login.php");
    exit();
}

// Initialize variables
$username = $_SESSION['username'];
$email = $_SESSION['email'];
$first_name = isset($_SESSION['first_name']) ? $_SESSION['first_name'] : '';
$last_name = isset($_SESSION['last_name']) ? $_SESSION['last_name'] : '';
$profile_picture = isset($_SESSION['profile_picture']) ? $_SESSION['profile_picture'] : '';
$error = '';

// Get current user data from users.json
$users_file = "users.json";
$users = [];
$current_user_index = -1;

if (file_exists($users_file)) {
    $users_json = file_get_contents($users_file);
    $users = json_decode($users_json, true);

    // Find the current user
    foreach ($users as $index => $user) {
        if ($user["username"] === $username) {
            $current_user_index = $index;
            // Update local variables with current values from file
            $email = $user["email"];
            $first_name = isset($user["first_name"]) ? $user["first_name"] : '';
            $last_name = isset($user["last_name"]) ? $user["last_name"] : '';
            $profile_picture = isset($user["profile_picture"]) ? $user["profile_picture"] : '';
            break;
        }
    }
}

// Check if form is submitted
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Get form data
    $new_email = trim($_POST["email"]);
    $new_first_name = trim($_POST["first_name"]);
    $new_last_name = trim($_POST["last_name"]);

    // Validate input
    if (empty($new_email)) {
        $error = "Email is required";
    } elseif (empty($new_first_name)) {
        $error = "First name is required";
    } elseif (empty($new_last_name)) {
        $error = "Last name is required";
    } elseif (!filter_var($new_email, FILTER_VALIDATE_EMAIL)) {
        $error = "Invalid email format";
    } else {
        // Check if email is already used by another user
        foreach ($users as $index => $user) {
            if ($user["email"] === $new_email && $index !== $current_user_index) {
                $error = "Email already exists";
                break;
            }
        }
    }

    // Handle profile picture upload
    $new_profile_picture = $profile_picture; // Default to current picture

    if (isset($_FILES['profile_picture']) && $_FILES['profile_picture']['error'] === UPLOAD_ERR_OK) {
        $allowed_types = ['image/jpeg', 'image/png', 'image/gif'];
        $max_size = 2 * 1024 * 1024; // 2MB

        // Validate file type
        $file_type = $_FILES['profile_picture']['type'];
        if (!in_array($file_type, $allowed_types)) {
            $error = "Only JPG, PNG, and GIF files are allowed";
        } 
        // Validate file size
        elseif ($_FILES['profile_picture']['size'] > $max_size) {
            $error = "File size must be less than 2MB";
        } 
        // Process upload
        else {
            $upload_dir = "uploads/profile_pictures/";
            $file_extension = pathinfo($_FILES['profile_picture']['name'], PATHINFO_EXTENSION);
            $file_name = $username . "_" . time() . "." . $file_extension;
            $upload_path = $upload_dir . $file_name;

            if (move_uploaded_file($_FILES['profile_picture']['tmp_name'], $upload_path)) {
                // Delete old profile picture if it exists and is not a default profile picture
                if (!empty($profile_picture) && file_exists($profile_picture) && 
                    $profile_picture !== "uploads/profile_pictures/default_user.png" && 
                    $profile_picture !== "uploads/profile_pictures/default_admin.png") {
                    unlink($profile_picture);
                }

                $new_profile_picture = $upload_path;
            } else {
                $error = "Failed to upload profile picture";
            }
        }
    }

    // Update user data if no errors
    if (empty($error)) {
        // Update user in array
        $users[$current_user_index]["email"] = $new_email;
        $users[$current_user_index]["first_name"] = $new_first_name;
        $users[$current_user_index]["last_name"] = $new_last_name;
        $users[$current_user_index]["profile_picture"] = $new_profile_picture;

        // Save to file
        if (file_put_contents($users_file, json_encode($users, JSON_PRETTY_PRINT))) {
            // Update session variables
            $_SESSION["email"] = $new_email;
            $_SESSION["first_name"] = $new_first_name;
            $_SESSION["last_name"] = $new_last_name;
            $_SESSION["profile_picture"] = $new_profile_picture;

            // Update local variables
            $email = $new_email;
            $first_name = $new_first_name;
            $last_name = $new_last_name;
            $profile_picture = $new_profile_picture;

            // Redirect to dashboard page after profile update
            header("Location: dashboard.php");
            exit();
        } else {
            $error = "Failed to save profile changes";
        }
    }
}
?>

<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card shadow-sm">
                <div class="card-body p-4">
                    <h1 class="card-title mb-4">Edit Profile</h1>

                    <?php if (!empty($error)): ?>
                        <div class="alert alert-danger"><?php echo $error; ?></div>
                    <?php endif; ?>

                    <form action="edit_profile.php" method="post" enctype="multipart/form-data">
                        <div class="mb-3">
                            <label for="username" class="form-label">Username</label>
                            <input type="text" class="form-control" id="username" value="<?php echo htmlspecialchars($username); ?>" disabled>
                            <div class="form-text">Username cannot be changed.</div>
                        </div>

                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <label for="first_name" class="form-label">First Name</label>
                                <input type="text" class="form-control" id="first_name" name="first_name" value="<?php echo htmlspecialchars($first_name); ?>" required>
                            </div>
                            <div class="col-md-6 mb-3">
                                <label for="last_name" class="form-label">Last Name</label>
                                <input type="text" class="form-control" id="last_name" name="last_name" value="<?php echo htmlspecialchars($last_name); ?>" required>
                            </div>
                        </div>

                        <div class="mb-3">
                            <label for="email" class="form-label">Email</label>
                            <input type="email" class="form-control" id="email" name="email" value="<?php echo htmlspecialchars($email); ?>" required>
                        </div>

                        <div class="mb-3">
                            <label for="profile_picture" class="form-label">Profile Picture</label>
                            <?php if (!empty($profile_picture) && file_exists($profile_picture)): ?>
                                <div class="mb-2">
                                    <img src="<?php echo htmlspecialchars($profile_picture); ?>" alt="Profile Picture" class="img-thumbnail" style="max-width: 150px;">
                                </div>
                            <?php endif; ?>
                            <input type="file" class="form-control" id="profile_picture" name="profile_picture">
                            <div class="form-text">Allowed file types: JPG, PNG, GIF. Maximum size: 2MB.</div>
                        </div>

                        <div class="d-grid gap-2 d-md-flex justify-content-md-end">
                            <a href="dashboard.php" class="btn btn-secondary me-md-2">Cancel</a>
                            <button type="submit" class="btn btn-primary">Save Changes</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>

<?php require_once 'footer.php'; ?>
