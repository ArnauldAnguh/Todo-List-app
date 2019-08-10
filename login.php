<?php include "server.php"; ?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Login</title>
   <link rel="stylesheet" type="text/css" href="css/auth.css">
   <style>html,body{  background-color: whitesmoke;}</style>
</head>
<body>
   <div class="container">
        <h1>Login Page</h1>
     <div class="form">
        <div class="img"><img src="vatar3.png" width="60px" height="60px"></div>
         <form action="login.php" method="POST">
            <?php 
            if (!empty($_SESSION['error'])) {
                echo "<div class='error'>" . $_SESSION['error'] . "</div>" . "<br>";
                  unset($_SESSION['error']);
                } 
            if (!empty($_SESSION['success'])) {
                echo "<div class='success'>" . $_SESSION['success'] . "</div>" . "<br>";
                  unset($_SESSION['success']); session_destroy();
                } 
         
             ?>
             <div class="form-group">
                 <input type="text" name="username"  placeholder="Enter Username">
             </div>
             <div class="form-group">
                 <input type="password" name="password"  placeholder="Enter Password">
             </div>
                 <input type="submit" name="login" value="Login" class="btn">
         </form>
        <hr>
        <p>create account <a href="register.php" style="color: #EE00FF;">SignUp</a>
            <a href="#" style="color: #EE00FF;float: right">Forgot Password?</a></p>
     </div>
   </div> 
</body>
</html>