<?php
$host="localhost";
$dbname="hapaghanap";
$username="root";
$password="";
$pdo=new PDO("mysql:host=$host;dbname=$dbname",$username,$password);

$conn = new mysqli($host, $username, $password, $dbname);

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}