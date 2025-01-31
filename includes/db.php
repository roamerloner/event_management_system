<?php

require_once 'config.php';

function getDBConnection(){
    static $conn;

    if($conn === null){
        $conn = new mysqli(DB_HOST, DB_USER, DB_PASS, DB_NAME);
        if($conn->connect_error){
            die("Connection failed " . $conn->connect_error);
        }
    }
    return $conn;
}

function closeDBConnection($conn){
    static $closed = false;
    if(!$closed){
        register_shutdown_function(function(){
            $conn = getDBConnection();
            if($conn !== null){
                $conn->close();
            }
        });
        $closed = true;
    }
}


?>