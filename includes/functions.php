<?php

require_once 'db.php';

function sanitizeInput($input){
    return htmlspecialchars(strip_tags(trim($input)));
}

function hashPassword($password){
    return password_hash($password . SALT, PASSWORD_BCRYPT);
}

function verifyPassword($password, $hashedPassword){
    return password_verify($password . SALT, $hashedPassword);
}

function isLoggedIn(){
    return isset($_SESSION['user_id']);
}

function isAdmin(){
    if(!isset($_SESSION['user_id'])){
        return false;
    }

    $conn = getDBConnection();
    $stmt = $conn->prepare("SELECT is_admin FROM users WHERE id = ?");
    $stmt->bind_param("i", $_SESSION['user_id']);
    $stmt->execute();
    $result = $stmt->get_result();
    $row = $result->fetch_assoc();
    $stmt->close();
    
    return $row['is_admin'] ?? false;

    
}

function redirectTo($location){
    $base_url = sprintf(
        "%s://%s%s",
        isset($_SERVER['HTTPS']) && $_SERVER['HTTPS'] != 'off' ? 'https' : 'http',
        $_SERVER['SERVER_NAME'],
        dirname($_SERVER['PHP_SELF'])
    );

    $base_url = rtrim($base_url, '/');

    $location = '/' . ltrim($location, '/');

    header("Location: " . $base_url . $location);
    exit();
}

function getPaginatedEvents($page = 1, $limit = 10, $sortBy = 'date', $sortOrder = 'ASC', $filter = ''){
    $conn = getDBConnection();
    $offset = ($page - 1) * $limit;

    $query = "SELECT * FROM events";
    if(!empty($filter)){
        $query .= " WHERE name LIKE ? OR description LIKE ?";
    }
    $query .= " ORDER BY $sortBy $sortOrder LIMIT ? OFFSET ?";
    
    $stmt = $conn->prepare($query);

    if(!empty($filter)){
        $filterParam = "%$filter%";
        $stmt->bind_param("ssii", $filterParam, $filterParam, $limit, $offset);
    } else{
        $stmt->bind_param("ii", $limit, $offset);
    }


    $stmt->execute();
    $result = $stmt->get_result();
    $events = $result->fetch_all(MYSQLI_ASSOC);

    $stmt->close();
    closeDBConnection($conn);

    return $events;
}


?>