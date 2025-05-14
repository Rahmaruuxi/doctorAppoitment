<?php

$conn = new mysqli("localhost", "root", "", "doctor_appointment");
if ($conn->connect_error) {
    die("Connection Error: ");
} 
?>
