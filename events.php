<?php
session_start();
require_once 'includes/functions.php';

if(!isLoggedIn()){
    redirectTo('login.php');
}

$page = isset($_GET['page']) ? (int)$_GET['page'] : 1;
$limit = 10;
$sortBy = isset($_GET['sort']) ? sanitizeInput($_GET['sort']) : 'date';
$sortOrder = isset($_GET['order']) ? sanitizeInput($_GET['order']) : 'ASC';
$filter = isset($_GET['filter']) ? sanitizeInput($_GET['filter']) : '';

$events = getPaginatedEvents($page, $limit, $sortBy, $sortOrder, $filter);


?>




<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Events - Event Management System</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="assets/css/styles.css" rel="stylesheet">
</head>
<body>
    <div class="container">
        <h1 class="mt-5 mb-3">Events</h1>
        <div class="mb-3">
            <?php if(isAdmin()) : ?>
            <a href="admin/create_event.php" class="btn btn-primary">Create Event</a>
            <?php endif;?>
            <a href="logout.php" class="btn btn-secondary">Logout</a>
        </div>
        <form method="GET" action="" class="mb-3">
            <div class="input-group">
                <input type="text" class="form-control" name="filter" placeholder="Search events" value="<?php echo $filter;?>">
                <button type="submit" class="btn btn-outline-secondary">Search</button>
            </div>
        </form>
        <table class="table table-striped">
                <thead>
                    <tr>
                        <th><a href="?sort=name&order=<?php echo $sortOrder === 'ASC' ? 'DESC' : 'ASC' ;?>">Name</a></th>
                        <th><a href="?sort=date&order=<?php echo $sortOrder === 'ASC' ? 'DESC' : 'ASC' ;?>">Date</a></th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach($events as $event) : ?>
                        <tr>
                            <td><?php echo $event['name']; ?></td>
                            <td><?php echo $event['date']; ?></td>
                            <td>
                                <a href="event_details.php?id=<?php echo $event['id'];?>" class="btn btn-sm btn-info">View</a>
                                <?php if(isAdmin()) : ?>
                                    <a href="admin/edit_event.php?id=<?php echo $event['id'];?>" class="btn btn-sm btn-warning">Edit</a>
                                    <?php endif;?>
                                    <a href="register_attendee.php?id=<?php echo $event['id'];?>" class="btn btn-sm btn-success">Register</a>
                            </td>
                        </tr>
                        <?php endforeach;?>
                </tbody>
        </table>
    </div>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.bundle.min.js"></script>
    <script src="assets/js/main.js"></script>
</body>
</html>