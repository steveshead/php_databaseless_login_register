<?php
$page_title = "Contact Us - Login System";
// Start session in header.php
require_once 'header.php';

// Initialize variables
$name = $email = $subject = $message = "";
$success = $error = "";

// Generate CSRF token if not exists
if (!isset($_SESSION['csrf_token'])) {
    $_SESSION['csrf_token'] = md5(uniqid(mt_rand(), true));
}

// Check if form is submitted
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Verify CSRF token
    if (!isset($_POST['csrf_token']) || $_POST['csrf_token'] !== $_SESSION['csrf_token']) {
        $error = "Security verification failed. Please try again.";
    } else {
        // Get form data
        $name = trim($_POST["name"]);
        $email = trim($_POST["email"]);
        $subject = trim($_POST["subject"]);
        $message = trim($_POST["message"]);

        // Validate input
        if (empty($name) || empty($email) || empty($subject) || empty($message)) {
            $error = "All fields are required";
        } elseif (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
            $error = "Invalid email format";
        } else {
            // Process the form - send email
            $to = "admin@example.com"; // Replace with your email
            $email_subject = "Contact Form: $subject";
            $email_body = "You have received a new message from your website contact form.\n\n";
            $email_body .= "Name: $name\n";
            $email_body .= "Email: $email\n";
            $email_body .= "Subject: $subject\n";
            $email_body .= "Message:\n$message\n";
            $headers = "From: $email\n";
            $headers .= "Reply-To: $email\n";

            // Try to send email
            $mail_sent = mail($to, $email_subject, $email_body, $headers);

            // If mail fails, log the message to a file as a fallback
            if (!$mail_sent) {
                $log_file = "contact_submissions.log";
                $log_entry = date("Y-m-d H:i:s") . " - Name: $name, Email: $email, Subject: $subject, Message: " . str_replace("\n", " ", $message) . "\n";
                file_put_contents($log_file, $log_entry, FILE_APPEND);
            }

            // Show success message regardless of mail function success
            // This ensures the form works even in environments where mail() doesn't work
            $success = "Thank you for contacting us. We will get back to you soon!";
            // Clear form fields after submission
            $name = $email = $subject = $message = "";
        }
    }
}
?>
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-md-8">
                <div class="card shadow-sm">
                    <div class="card-body p-4">
                        <h1 class="card-title text-center mb-4">Contact Us</h1>
                        <p class="text-center mb-4">Have a question or feedback? Fill out the form below to get in touch with us.</p>

                        <?php if (!empty($success)): ?>
                            <div class="alert alert-success text-center"><?php echo $success; ?></div>
                        <?php endif; ?>

                        <?php if (!empty($error)): ?>
                            <div class="alert alert-danger text-center"><?php echo $error; ?></div>
                        <?php endif; ?>

                        <form action="contact.php" method="post">
                            <!-- CSRF Protection -->
                            <input type="hidden" name="csrf_token" value="<?php echo $_SESSION['csrf_token']; ?>">
                            <div class="mb-3">
                                <label for="name" class="form-label">Your Name</label>
                                <input type="text" class="form-control" id="name" name="name" value="<?php echo htmlspecialchars($name); ?>" required>
                            </div>
                            <div class="mb-3">
                                <label for="email" class="form-label">Your Email</label>
                                <input type="email" class="form-control" id="email" name="email" value="<?php echo htmlspecialchars($email); ?>" required>
                            </div>
                            <div class="mb-3">
                                <label for="subject" class="form-label">Subject</label>
                                <input type="text" class="form-control" id="subject" name="subject" value="<?php echo htmlspecialchars($subject); ?>" required>
                            </div>
                            <div class="mb-3">
                                <label for="message" class="form-label">Message</label>
                                <textarea class="form-control" id="message" name="message" rows="5" required><?php echo htmlspecialchars($message); ?></textarea>
                            </div>
                            <div class="d-grid gap-2 mb-3">
                                <button type="submit" class="btn btn-primary">Send Message</button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>

<?php require_once 'footer.php'; ?>
