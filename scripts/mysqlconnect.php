<?php
$servername = "localhost";
$username = "dico";
$password = "tad2rYP1aRre6cLk";
$dbname = "dico";

// Create connection
$conn = new mysqli($servername, $username, $password, $dbname);

// Check connection
if ($conn->connect_error) {
    die("La conexión a la base de datos falló: " . $conn->connect_error);
} 

//echo "Connected successfully";
?>