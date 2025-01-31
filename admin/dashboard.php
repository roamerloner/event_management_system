<?php
session_start();
require_once '../includes/functions.php';


if(!isLoggedIn() || !isAdmin()){
    redirectTo('../login.php');
}


$conn = getDBConnection();
$stmt =$conn->prepare("SELECT id, name, date FROM events ORDER BY date DESC LIMIT 5");
$stmt->execute();
$result = $stmt->get_result();
$recent_events = $result->fetch_all(MYSQLI_ASSOC);
$stmt->close();

$stmt =$conn->prepare("SELECT COUNT(*) as total_events FROM events");
$stmt->execute();
$result = $stmt->get_result();
$total_events = $result->fetch_assoc()['total_events'];
$stmt->close();

$stmt =$conn->prepare("SELECT COUNT(*) as total_attendees FROM attendees");
$stmt->execute();
$result = $stmt->get_result();
$total_attendees = $result->fetch_assoc()['total_attendees'];
$stmt->close();

closeDBConnection($conn);
?>


<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Dashboard - Event Management System</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="../assets/css/styles.css" rel="stylesheet">
</head>
<body>
    <div class="container">
        <h1 class="mt-5 mb-3">Admin Dashboard</h1>
        <div class="row">
            <div class="col-md-6">
                <div class="card mb-3">
                    <div class="card-body">
                        <h5 class="card-title">Total Events</h5>
                        <p class="card-text"><?php echo $total_events; ?></p>
                    </div>
                </div>
            </div>
            <div class="col-md-6">
                <div class="card mb-3">
                    <div class="card-body">
                        <h5 class="card-title">Total Attendees</h5>
                        <p class="card-text"><?php echo $total_attendees; ?></p>
                    </div>
                </div>
            </div>
            <h2 class="mt-4 mb-3">Recent Events</h2>
            <table class="table table-striped">
                <thead>
                    <tr>
                        <th>Name</th>
                        <th>Date</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach($recent_events as $event) :?>
                        <tr>
                            <td><?php echo $event['name']; ?></td>
                            <td><?php echo $event['date']; ?></td>
                            <td>
                                <a href="edit_event.php?id=<?php echo $event['id'];?>" class="btn btn-sm btn-warning">Edit</a>
                                <a href="generate_report.php?id=<?php echo $event['id'];?>" class="btn btn-sm btn-info">Generate Report</a>
                            </td>
                        </tr>
                        <?php endforeach;?>
                </tbody>
            </table>
            <div class="mt-3">
                <a href="create_event.php" class="btn btn-primary">Create New Event</a>                            
                <a href="../events.php" class="btn btn-secondary">View All Events</a>
                <a href="../logout.php" class="btn btn-danger">Logout</a>                                                        
            </div>
        </div>
    </div>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.bundle.min.js"></script>
    <script src="../assets/js/main.js"></script>
</body>
</html>