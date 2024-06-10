<?php

@include '../config.php';

session_start();

$admin_id = $_SESSION['admin_id'];

if(!isset($admin_id)){
   header('location:login.php');
};


if (isset($_GET['delete'])) {
   
   $manager = new MongoDB\Driver\Manager("mongodb://localhost:27017");

   $delete_id = $_GET['delete'];

   // Filter to match the document with the given ID
   $filter = ['_id' => new MongoDB\BSON\ObjectId($delete_id)];

   //Delete command
   $command = new MongoDB\Driver\Command([
       'delete' => 'adbms_cw', // Collection name
       'deletes' => [
           ['q' => $filter, 'limit' => 1] // Limiting to delete only one document
       ],
       'writeConcern' => new MongoDB\Driver\WriteConcern(MongoDB\Driver\WriteConcern::MAJORITY, 1000)
   ]);

   // Execute the delete command
   try {
       $result = $manager->executeCommand('nibm', $command);
   } catch (MongoDB\Driver\Exception\Exception $e) {
       echo "Error deleting document: " . $e->getMessage();
       exit;
   }
}

?>

<!DOCTYPE html>
<html lang="en">
<head>
   <meta charset="UTF-8">
   <meta http-equiv="X-UA-Compatible" content="IE=edge">
   <meta name="viewport" content="width=device-width, initial-scale=1.0">
   <title>messages</title>

   <!-- font awesome cdn link  -->
   <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.1.1/css/all.min.css">

   <!-- custom css file link  -->
   <link rel="stylesheet" href="../css/admin_style.css">

</head>
<body>
   
<?php include 'admin_header.php'; ?>

<section class="messages">

   <h1 class="title">messages</h1>

   <div class="box-container">

   <?php
      $mongoClient = new MongoDB\Driver\Manager("mongodb://localhost:27017");

      // Specify the query
      $query = new MongoDB\Driver\Query([]);

      // Execute the query
      $cursor = $mongoClient->executeQuery('nibm.adbms_cw', $query);

      // Iterate through the results
      foreach ($cursor as $document) {
   ?>
   <div class="box">
      <p>Name: <?= $document->name ?></p>
      <p>Tele: <?= $document->tele ?></p>
      <p>Email: <?= $document->email ?></p>
      <p>Message: <?= $document->message ?></p>
      <a href="admin_contacts.php?delete=<?= $document->_id; ?>" onclick="return confirm('Delete this message?');" class="delete-btn">Delete</a>
   </div>

   <?php
   }
   ?>

   </div>

</section>

<script src="../js/script.js"></script>

</body>
</html>