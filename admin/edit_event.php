<?php
session_start();
require_once '../includes/functions.php';

// $success = $error = ''

if(!isLoggedIn() || !isAdmin()){
    redirectTo('../login.php');
}

$event_id = isset($_GET['id']) ? (int)$_GET['id'] : 0;

if($event_id === 0){
    redirectTo('dashboard.php');
}

$conn = getDBConnection();

if($_SERVER['REQUEST_METHOD'] === 'POST'){
    $name = sanitizeInput($_POST['name']);
    $description = sanitizeInput($_POST['description']);
    $date = sanitizeInput($_POST['date']);
    $capacity = (int)$_POST['capacity'];

    $stmt = $conn->prepare("UPDATE events SET name = ?, description = ?, date = ?, capacity = ? WHERE id = ?");
    $stmt->bind_param("sssii", $name, $description, $date, $capacity, $event_id);

    if($stmt->execute()){
        $success = "Event updated successfully.";
    } else{
        $error = "Failed to update event. Please try again";
    }

    $stmt->close();
    
}

$stmt = $conn->prepare("SELECT * FROM events WHERE id = ?");
$stmt->bind_param("i", $event_id);
$stmt->execute();
$result = $stmt->get_result();

if($result->num_rows === 0){
    redirectTo('dashboard.php');
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
    <title>Edit Event - Event Management System</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="../assets/css/styles.css" rel="stylesheet">
</head>
<body>
<div class="container">
        <h1 class="mt-5 mb-3">Edit Event</h1>
        <?php if (isset($error)): ?>
            <div class="alert alert-success"><?php echo $error;?></div>
         <?php endif; ?>
         <?php if (isset($success)):?>
            <div class="alert alert-danger"><?php echo $success;?></div>
         <?php endif; ?>   
         <form method="POST" action="">
            <div class="mb-3">
                <label class="form-label" for="name">Event Name</label>
                <input type="text" class="form-control" id="name" name="name" value="<?php echo htmlspecialchars($event['name']);?>" required>
            </div>

            <div class="mb-3">
                <label class="form-label" for="description">Description</label>
                <textarea type="email" class="form-control" id="description" name="description" rows="3" required><?php echo htmlspecialchars($event['description']);?></textarea>
            </div>

            <div class="mb-3">
                <label class="form-label" for="date">Date</label>
                <input type="date" class="form-control" id="date" name="date" value="<?php echo $event['date'];?>" required>
            </div>

            <div class="mb-3">
                <label class="form-label" for="capacity">Capacity</label>
                <input type="number" class="form-control" id="capacity" name="capacity" min="1" value="<?php echo $event['capacity'];?>" required>
            </div>
            <button class="btn btn-primary" type="submit">Update Event</button>
        </form>
        <div class="mt-3">
            <a href="dashboard.php" class="btn btn-secondary">Back to Dashboard</a>
        </div>
    </div>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.bundle.min.js"></script>
    <script src="../assets/js/main.js"></script>
</body>
</html>