<?php
session_start();
require_once 'includes/functions.php';
if(isLoggedIn()){
    redirectTo('events.php');
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Welcome to Event Management System</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="assets/css/styles.css" rel="stylesheet">
</head>
<body>
    <div class="container">
     <h1 class="mt-5 mb-3">Welcome to Event Management System</h1>
     <div class="row">
        <div class="col-md-6">
            <div class="card">
                <div class="card-body">
                    <h5 class="card-title">Already have an account?</h5>
                    <p class="card-text">Log into manage your events and registrations</p>
                    <a href="login.php" class="btn btn-primary">Login</a>
                </div>
            </div>
        </div>
        <div class="col-md-6">
            <div class="card">
                <div class="card-body">
                <h5 class="card-title">New to Event Management System>?</h5>
                <p class="card-text">Create an account to start managing your events.</p>
                <a href="register.php" class="btn btn-secondary">Register</a>
                </div>
            </div>
        </div>
     </div>
    </div>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.bundle.min.js"></script>
    <script src="assets/js/main.js"></script>
</body>
</html>