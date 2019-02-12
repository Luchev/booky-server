<?php
include "DBCON/db.php";
$mail = $_POST['mail'];
$pass = $_POST['pass'];

if (!isset($mail)) { // Check if the mail has been received with POST
  echo("1"); // 1 = Mail is not set
  die();
}

if (!isset($pass)) { // Check if the pass has been received with POST
  echo ("2"); // 2 = Password is not set
}

$sql = "SELECT mail FROM `users` WHERE mail='$mail' LIMIT 1";
$result = $c->query($sql);
if (!$result) { // Check if the query was successful (enter if if it failed)
  echo("5"); // 5 = Failed to SELECT from the database
}

if ($result->num_rows > 0) {
  echo("3"); // 3 = Mail is already registered
  die();
}

$sql = "INSERT INTO `users` (mail, pass) VALUES ('$mail', '$pass')";
if ($c->query($sql) === TRUE) {
    echo("0"); // 0 = Successfully added the new user to the database
}
else {
    echo("4"); // 4 = Failed to add the new user to the database after connection, during insertion
}

$c->close();
?>
