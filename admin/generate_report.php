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
if(!$stmt){
    die("Error preparing statement: ". $conn->error);
}
$stmt->bind_param("i", $event_id);
if(!$stmt->execute()){
    die("Error executing statement: " . $tmt->error);
}

$result = $stmt->get_result();

if($result->num_rows === 0){
    redirectTo('dashboard.php');
}


$event_name = $result->fetch_assoc()['name'];
$stmt->close();

//fetch attendees name
$stmt =$conn->prepare("SELECT name, email FROM attendees WHERE event_id = ?");
if(!$stmt){
    die("Error preparing statement: ". $conn->error);
}
$stmt->bind_param("i", $event_id);
if(!$stmt->execute()){
    die("Error executing statement: " . $tmt->error);
}
$result = $stmt->get_result();
$recent_events = $result->fetch_all(MYSQLI_ASSOC);
$stmt->close();

closeDBConnection($conn);

$filename = $event_name . "_attendees.csv";
header('Content-Type: text/csv');
header('Content-Disposition: attachment; filename="' . $filename . '"');

$output = fopen('php://output', 'w');
fputcsv($output, array('Name', 'Email'));


if(!empty($attendees)){
foreach($attendees as $attendee){
    fputcsv($output, $attendee);
}
} else {
    fputcsv($output, array("No attendees found for this event."));
}

fclose($output);
exit();




?>