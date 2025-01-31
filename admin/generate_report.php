<?php
session_start();
require_once '../includes/functions.php';

if(!isLoggedIn() || !isAdmin()){
    redirectTo('../login.php');
}

$event_id = isset($_GET['id']) ? (int)$_GET['id'] : 0;

if($event_id === 0){
    redirectTo('dashboard.php');
}

$conn = getDBConnection();
$stmt = $conn->prepare("SELECT name FROM events WHERE id = ?");
$stmt->bind_param("i", $event_id);
$stmt->execute();
$result = $stmt->get_result();

if($result->num_rows === 0){
    redirectTo('dashboard.php');
}

$stmt =$conn->prepare("SELECT name, email FROM attendees WHERE event_id = ?");
$stmt->bind_param("i", $event_id);
$stmt->execute();
$result = $stmt->get_result();
$recent_events = $result->fetch_all(MYSQLI_ASSOC);
$stmt->close();

closeDBConnection($conn);

$filename = $event_name . "_attendees.csv";
header('Content-Type: text/csv');
header('Content-Disposition: attachment; filename="' . $filename . '"');

$output = fopen('php://output', 'w');
fputcsv($output, array('Name', 'Email'));

foreach($attendees as $attendee){
    fputcsv($output, $attendee);
}

fclose($output);
exit();




?>