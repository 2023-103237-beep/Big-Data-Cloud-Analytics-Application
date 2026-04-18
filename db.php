<?php
$host = "localhost";
$user = "root";
$password = "";
$database = "walmart_sales"; // CHANGE THIS to your real database name

$conn = new mysqli($host, $user, $password, $database);

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}
?>