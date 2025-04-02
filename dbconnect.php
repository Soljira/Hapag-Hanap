<?php
$host="localhost";
$dbname="hapaghanap";
$username="root";
$password="";
$pdo=new PDO("mysql:host=$host;dbname=$dbname",$username,$password);

// Create connection
$conn = new mysqli($host, $username, $password, $dbname);

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}