<?php
session_start();
require_once 'includes/functions.php';

if($_SERVER['REQUEST_METHOD'] === 'POST'){
    $email = sanitizeInput($_POST['email']);
    $password = $_POST['password'];
    

    $conn = getDBConnection();
    $stmt = $conn->prepare("SELECT id, password, is_admin FROM users WHERE email = ?");
    $stmt->bind_param("s", $email);
    $stmt->execute();
    $result = $stmt->get_result();

    if($result->num_rows === 1){
        $user = $result->fetch_assoc();
       if(verifyPassword($password, $user['password'])){
        $_SESSION['user_id'] = $user['id'];
        $_SESSION['is_admin'] = (bool)$user['is_admin'];
        redirectTo('events.php');
       }
    }

    $error = "Invalid email or password";
    $stmt->close();
    closeDBConnection($conn);
}

?>



<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login - Event Management System</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="assets/css/styles.css" rel="stylesheet">
</head>
<body>
    <div class="container">
        <h1 class="mt-5 mb-3">Login</h1>
        <?php if (isset($error)): ?>
            <div class="alert alert-danger"><?php echo $error; ?></div>
        <?php endif; ?>
        <form method="POST" action="">
            <div class="mb-3">
                <label class="form-label" for="email">Email</label>
                <input type="email" class="form-control" id="email" name="email" required>
            </div>

            <div class="mb-3">
                <label class="form-label" for="password">Password</label>
                <input type="password" class="form-control" id="password" name="password" required>
            </div>
            <button class="btn btn-primary" type="submit">Login</button>
        </form>
            <p class="mt-3">Don't have an account? <a href="register.php">Register Here</a></p>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.bundle.min.js"></script>
    <script src="assets/js/main.js"></script>
    
</body>
</html>