<?php
  $servername = "localhost";
  $username = "root";
  $password = "lambaudi2803";
  $dbname="course";
  $conn = new mysqli($servername, $username, $password, $dbname);
  if ($conn->connect_error)
    die("Connection failed: " . $conn->connect_error);
?>