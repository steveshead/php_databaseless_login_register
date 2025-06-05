<!-- Test file - feel free to delete -->

<?php
$page_title = "Test Index Buttons - Login System";
require_once 'header.php';
?>

<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card shadow-sm">
                <div class="card-body p-4">
                    <h1 class="card-title mb-4">Test Index Buttons</h1>
                    
                    <h5>Buttons from index.php (should use button_style from global settings):</h5>
                    <div class="d-grid gap-2 d-md-flex">
                        <a href="login.php" class="btn btn-primary btn-lg px-4 me-md-2">Login</a>
                        <a href="register.php" class="btn btn-outline-primary btn-lg px-4">Register</a>
                    </div>
                    
                    <h5 class="mt-4">Button without specific class (for comparison):</h5>
                    <button class="btn">Default Button</button>
                    
                    <h5 class="mt-4">Buttons with specific classes (for comparison):</h5>
                    <button class="btn btn-primary">Primary Button</button>
                    <button class="btn btn-secondary">Secondary Button</button>
                    <button class="btn btn-success">Success Button</button>
                    <button class="btn btn-danger">Danger Button</button>
                    <button class="btn btn-warning">Warning Button</button>
                    <button class="btn btn-info">Info Button</button>
                    <button class="btn btn-light">Light Button</button>
                    <button class="btn btn-dark">Dark Button</button>
                </div>
            </div>
        </div>
    </div>
</div>

<?php require_once 'footer.php'; ?>