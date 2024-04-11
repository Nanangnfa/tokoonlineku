<?php

// Create connection
$con = mysqli_connect("localhost","root", "ayomondok","tokoonline","3307");

// Check connection
if (!$con) {
    die ("Connection failed: " . mysqli_connect_error());
}
?>