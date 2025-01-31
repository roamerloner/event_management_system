<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);

session_start();
require_once '../includes/functions.php';

$success = $error = '';
if(!isLoggedIn() || !isAdmin()){
    redirectTo('../login.php');
}

if($_SERVER['REQUEST_METHOD'] === 'POST'){
    $name = sanitizeInput($_POST['name']);
    $description = sanitizeInput($_POST['description']);
    $date = sanitizeInput($_POST['date']);
    $capacity = (int)$_POST['capacity'];

    $conn = getDBConnection();
    $stmt = $conn->prepare("INSERT INTO events (name, description, date, capacity) VALUES (?, ?, ?, ?)");
    $stmt->bind_param("sssi", $name, $description, $date, $capacity);

    if($stmt->execute()){
        $success = "Event created successfully.";
    } else{
        $error = "Failed to create event. Please try again";
    }

    $stmt->close();
    closeDBConnection($conn);
}

?>


<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Create Event - Event Management System</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="../assets/css/styles.css" rel="stylesheet">
</head>
<body>
<div class="container">
        <h1 class="mt-5 mb-3">Create Event</h1>
        <?php if($error):?>
            <div class="alert alert-danger"><?php echo $error;?></div>
         <?php endif; ?>
         <?php if($success):?>
            <div class="alert alert-success"><?php echo $success;?></div>
         <?php endif; ?>   
         <form method="POST" action="">
            <div class="mb-3">
                <label class="form-label" for="name">Event Name</label>
                <input type="text" class="form-control" id="name" name="name" required>
            </div>

            <div class="mb-3">
                <label class="form-label" for="description">Description</label>
                <textarea type="email" class="form-control" id="description" name="description" rows="3" required></textarea>
            </div>

            <div class="mb-3">
                <label class="form-label" for="date">Date</label>
                <input type="date" class="form-control" id="date" name="date" required>
            </div>

            <div class="mb-3">
                <label class="form-label" for="capacity">Capacity</label>
                <input type="number" class="form-control" id="capacity" name="capacity" min="1" required>
            </div>
            <button class="btn btn-primary" type="submit">Create Event</button>
        </form>
        <div class="mt-3">
            <a href="dashboard.php" class="btn btn-secondary">Back to Dashboard</a>
        </div>
    </div>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.bundle.min.js"></script>
    <script src="../assets/js/main.js"></script>
</body>
</html>