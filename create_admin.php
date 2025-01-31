<?php
session_start();
require_once 'includes/config.php';
require_once 'includes/db.php';
require_once 'includes/functions.php';

$success = $error = '';

if($_SERVER['REQUEST_METHOD'] === 'POST'){
    $name = sanitizeInput($_POST['name']);
    $email = sanitizeInput($_POST['email']);
    $password = $_POST['password'];
    $confirm_password = $_POST['confirm_password'];


    if($password !== $confirm_password){
        $error = "Passwords do not match";
    } else {
        $conn = getDBConnection();
        $stmt = $conn->prepare("SELECT id FROM users WHERE email = ?");
        $stmt->bind_param("s", $email);
        $stmt->execute();
        $result = $stmt->get_result();

        if($result->num_rows > 0){
            $error = "Email already exists";
        } else{
            $hashedPassword = hashPassword($password);
            $is_admin = 0;
            $stmt = $conn->prepare("INSERT INTO  users (name, email, password, is_admin) VALUES (?, ?, ?, 1)");
            $stmt->bind_param("sss", $name, $email, $hashedPassword);

            if($stmt->execute()){
                $success = "Admin was created successfully";
            } else {
                $error = "Registration failed";
            }   
        }
        $stmt->close();
        closeDBConnection($conn);
    }
}

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Create Admin User - Event Management System</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="assets/css/styles.css" rel="stylesheet">
</head>
<body>
    <div class="container">
        <h1 class="mt-5 mb-3">Create Admin User</h1>
        <?php if($success):?>
            <div class="alert alert-success"><?php echo $success;?></div>
         <?php endif; ?>
         <?php if($error):?>
            <div class="alert alert-danger"><?php echo $error;?></div>
         <?php endif; ?>   
         <form method="POST" action="">
            <div class="mb-3">
                <label class="form-label" for="name">Name</label>
                <input type="text" class="form-control" id="name" name="name" required>
            </div>

            <div class="mb-3">
                <label class="form-label" for="email">Email</label>
                <input type="email" class="form-control" id="email" name="email" required>
            </div>

            <div class="mb-3">
                <label class="form-label" for="password">Password</label>
                <input type="password" class="form-control" id="password" name="password" required>
            </div>

            <div class="mb-3">
                <label class="form-label" for="confirm_password">Confirm Password</label>
                <input type="password" class="form-control" id="confirm_password" name="confirm_password" required>
            </div>
            <button class="btn btn-primary" type="submit">Create Admin User</button>
        </form>
    </div>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>