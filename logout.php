<?php
session_start();
$_SESSION['username'] = NULL;
$_SESSION['id']       = NULL;


header('refresh:3, login.php');
$_SESSION['success'] = "you are now logged out";
echo "<small>" . "We Will return in 3s..." . "</small>";

?>
      