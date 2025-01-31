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

$user_id = $_SESSION['user_id'];
$stmt = $conn->prepare("SELECT name, email FROM users WHERE id = ?");
$stmt->bind_param("i", $user_id);
$stmt->execute();
$result = $stmt->get_result();
$user = $result->fetch_assoc();
$stmt->close();


if($_SERVER['REQUEST_METHOD'] === 'POST'){
    $stmt = $conn->prepare("SELECT id FROM attendees WHERE event_id = ? AND user_id = ?");
    $stmt->bind_param("ii", $event_id, $user_id);
    $stmt->execute();
    $result = $stmt->get_result();

    

    if($result->num_rows > 0){
        $error = "You are already registered for this event.";
    } else{
        $stmt = $conn->prepare("SELECT COUNT(*) as attendee_count FROM attendees WHERE event_id = ?");
        $stmt->bind_param("i", $event_id);
        $stmt->execute();
        $result = $stmt->get_result();
        $attendee_count = $result->fetch_assoc()['attendee_count'];
        $stmt->close();

        if($attendee_count >= $event['capacity']){
            $error = "Sorry, this event is already full.";
        } else {

        }
        $stmt = $conn->prepare("INSERT INTO attendees (event_id, user_id, name, email) VALUES (?, ?, ?, ?)");
        $stmt->bind_param("iiss", $event_id, $user_id, $user['name'], $user['email']);

        if($stmt->execute()){
            $success = "You have successfully registered for the event.";
        } else{
            $error = "Registration failed. Please try again.";
        }
        $stmt->close();
    }
}
closeDBConnection($conn);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Register for <?php echo $event['name'];?> - Event Management System</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="assets/css/styles.css" rel="stylesheet">
</head>
<body>
    <div class="container">
        <h1 class="mt-5 mb-3">Register for <?php echo $event['name'];?></h1>
        <?php if(isset($error)) :?>
            <div class="alert alert-danger"><?php echo $error; ?></div>
         <?php endif?>
         <?php if(isset($success)) :?>
            <div class="alert alert-success"><?php echo $success; ?></div>
          <?php else : ?>
            <form method="POST" action="">
            <div class="mb-3">
                <label class="form-label" for="name">Name</label>
                <input type="text" class="form-control" id="name" name="name" value="<?php echo $user['name'] ?>" >
            </div>

            <div class="mb-3">
                <label class="form-label" for="email">Email</label>
                <input type="email" class="form-control" id="email" name="email"  value="<?php echo $user['email'] ?>" >
            </div>
            <button class="btn btn-primary" type="submit">Register</button>
            </form>  
            <?php endif;?>
            <div class="mt-3">
                <a href="event_details.php?id=<?php echo $event_id; ?>" class="btn btn-secondary">Back to event details</a>
            </div>
    </div>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.bundle.min.js"></script>
    <script src="assets/js/main.js"></script>
</body>
</html>