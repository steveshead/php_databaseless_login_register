<!-- Test file - feel free to delete -->

<?php
$page_title = "Test Buttons - Login System";
require_once 'header.php';
?>

<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card shadow-sm">
                <div class="card-body p-4">
                    <h1 class="card-title mb-4">Test Buttons</h1>
                    
                    <h5>Button without specific class (should use button_style from global settings):</h5>
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