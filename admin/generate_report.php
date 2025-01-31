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
    die("Error executing statement: " . $stmt->error);
}

$result = $stmt->get_result();

if($result->num_rows === 0){
    die("Event Not Found");
}


$event_name = $result->fetch_assoc()['name'];
$stmt->close();


$stmt =$conn->prepare("SELECT name, email FROM attendees WHERE event_id = ?");
if(!$stmt){
    die("Error preparing statement for attendees: ". $conn->error);
}
$stmt->bind_param("i", $event_id);
if(!$stmt->execute()){
    die("Error executing statement: " . $stmt->error);
}
$result = $stmt->get_result();
$attendees = $result->fetch_all(MYSQLI_ASSOC);
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
    fputcsv($output, array("Event ID: ", $event_id));


    $debug_stmt = $conn->prepare("SELECT COUNT(*) as count FROM attendees");
    $debug_stmt->execute();
    $debug_result = $debug_stmt->get_result();
    $total_attendees = $debug_result->fetch_assoc()['count'];
    fputcsv($output, array('Total attendees in database: ', $total_attendees));
    $debug_stmt->close();
}

fclose($output);
exit();




?>