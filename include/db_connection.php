<?php

$servername = "localhost";
$username = "root";
$password = "";
$database = "appointform";

// Create connection
$db = new mysqli($servername, $username, $password, $database);

// Check connection
if ($db->connect_error) {
    die("Connection failed: " . $db->connect_error);
}

?>
