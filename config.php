<?php

  $db_name = "mysql:host=localhost;dbname=adbms_cw"; 
  $username = "root";
  $password = "";

  try {
      $conn = new PDO($db_name, $username, $password);
  } catch (PDOException $e) {
      echo "Connection failed: " . $e->getMessage();
  }

?>
