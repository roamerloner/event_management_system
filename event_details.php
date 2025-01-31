<?php
session_start();
require_once 'includes/functions.php';

if(!isLoggedIn()){
    redirectTo('login.php');
}

$event_id = isset($_GET['id']) ? (int)$_GET['id'] : 0;

if($event_id === 0){
    redirectTo('events.php');
}

$conn = getDBConnection();
$stmt = $conn->prepare("SELECT * FROM events WHERE id = ?");
$stmt->bind_param("i", $event_id);
$stmt->execute();
$result = $stmt->get_result();

if($result->num_rows === 0){
    redirectTo('events.php');
}

$event = $result->fetch_assoc();
$stmt->close();
closeDBConnection($conn);


?>



<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?php echo $event['name'];?> - Event Management System</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="assets/css/styles.css" rel="stylesheet">
</head>
<body>
    <div class="container">
        <h1 class="mt-5 mb-3"><?php echo $event['name'];?></h1>
        <div class="card">
            <div class="card-body">
                <p><strong>Date:</strong><?php echo $event['date'];?></p>
                <p><strong>Description:</strong><?php echo $event['description'];?></p>
                <p><strong>Capacity:</strong><?php echo $event['capacity'];?></p>
            </div>
        </div>
        <div class="mt-3">
            <a href="register_attendee.php?id=<?php $event['id'];?>" class="btn btn-primary">Register for Event</a>
            <a href="events.php" class="btn btn-secondary">Back to Events</a>
        </div>
    </div>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.bundle.min.js"></script>
    <script src="assets/js/main.js"></script>
</body>
</html>