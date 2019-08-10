<?php
  include "server.php"; 
?>
<!DOCTYPE html>
<html>
<head>
  <title>Registration</title>
    <link rel="stylesheet" type="text/css" href="css/auth.css">
    <style>html,body{background-color: whitesmoke;}</style>
</head>
<body>
<div class="container">
  <div align="center" style="box-shadow: 1px 1px 6px #CCCC99;padding: 20px;">
    <div class="page-header">
      <h2>Registration</h2>
    </div>
<form method="post" action="" name="frmRegistration" autocomplete="off">
  <?php if(isset($_SESSION['error'])) { ?> 
     <div class="error">
         <?php echo  $_SESSION['error']; unset($_SESSION['error']); ?>
      </div>
  <?php } ?>
  <div class="form-group">
    <input type="text" name="firstname" placeholder="Firstname" value="<?php if  ?>" required  class="form-control">
  </div>
  <div class="form-group">
    <input type="text" name="lastname" placeholder="Lastname"  required class="form-control">
  </div>
  <div class="form-group">
    <input type="text" name="username" placeholder="Username" required  class="form-control">
  </div>

  <div class="form-group">
    <input type="email" name="email" placeholder="Email: example@gmail.com"  class="form-control">
  </div>
  <div class="form-group">
    <input type="password" required name="password" placeholder="Password" class="form-control">
  </div>
  <div class="form-group">
    <input type="password" required name="passwordconf" placeholder="Confirm Password" class="form-control">
  </div>

   <p style="margin:10px;">
    <input type="submit" name="register-user" value="Register" style="width: 100%;padding: 8px 10px;cursor:pointer;background: lightblue;color: #fff;font-family: georgia;border: none;border-radius: 5px;font-size: 20px"><br>
   </p>
   <p style="font-size:15px;">
    Already a member? <a href="login.php" style="text-decoration:none;color: #EE00FF;">Sign in</a>
  </p>

   
</form>
</div>

</div>


</body>
</html>