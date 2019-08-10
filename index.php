<?php include "server.php";
  if (!isset($_SESSION['username']) AND empty($_SESSION['username'])) {
      header('location: login.php');
      exit();
  }
    $query = "SELECT * FROM tasks WHERE user_id = " . $_SESSION['id'];
    $results = mysqli_query($db,$query);

    $tasks = mysqli_fetch_all($results, MYSQLI_ASSOC);

    foreach ($tasks as $task) {
     $id = $task['id'];
    };
?>
<!DOCTYPE html>
<html>
<head>
  <title> Todo list Application With PHP and MySQL </title>
  <link rel="stylesheet" type="text/css" href="css/main.css " />
  <link rel="stylesheet" type="text/css" href="font-awesome-4.7.0/font-awesome-4.7.0/css/font-awesome.min.css">
  <link rel="stylesheet" type="text/css" href="fonticon/font/flaticon.css"> 
  <style>
    ::placeholder{
      color: lightgreen;
      font-style: italic
    }
    tbody tr{
      text-transform: capitalize;
    }
    #completed{
      background: rgba(0,0,0,.4)
    }
    #complete{
      text-decoration: line-through
    }
    #clear {
      height: 23px;
      color: red;
      float: right;
      padding: 3px;cursor: pointer;font-size: 18px;height: 18px;text-decoration: none;
    }
    #restore  {
      float: right;
      margin: 3px auto;
      padding: 0 5px;
      color: green;
      /* text-decoration: none; */
    }
    footer {
      height: 45px;
      background: rgba(0, 0, 0, 0.8);
    }
  </style>
</head>
<body>
  <div class="page">
<?php 
   if (isset($_SESSION['firstname'])) {
       echo "<div class='user'>" . $_SESSION['firstname'] ." ". substr($_SESSION['lastname'], 0, 1) . ".
      <br> <a href='logout.php' class='logout'> logout</a></div>";
    }
?>
  <div class="heading">
	   <h1> Todo list Application With PHP and MySQL </h1> 
  </div>

<form action="index.php" method="POST" autocomplete="off">
	  <?php if (isset($_SESSION['db_task'])): ?>

    <input type="text" name="task_new" class="form-control" value="<?php echo $_SESSION['db_task']; ?>" />

    <input type="hidden" name="task_id" value="<?php echo $_SESSION['new_id']; ?>">

    <button type="submit" name="update" class="btn">Update</button>

    <?php unset($_SESSION['db_task']); ?>

    <?php else :?>

      <input type="text" name="tasks" class="form-control" placeholder="Add New ToDo" />

    <?php echo '<button type="submit" name="submit" class="btn" style="outline: 0;">Add Task</button>'; ?>

    <?php endif ?>

  </form>
  
  <?php if (isset($_SESSION['success'])) {
    echo "<div class='success'>" . $_SESSION['success'] . "  </div>";
     unset($_SESSION['success']);
    } 
  ?>
  <?php if (isset($_SESSION['error'])) {
    echo $_SESSION['error']; unset($_SESSION['error']);
    } 
  ?>
<table class="table">
	 <thead>
     <p><strong> Uncompleted Tasks: 
       <a href="index.php?clear1"><span id='clear' title="Clear Uncompleted Tasks"><i class='fa fa-trash'></i> Clear</span></a> 
<!-- RESTORE BUTTON -->
    <?php if(isset($_SESSION['delete']) AND !empty($_SESSION['delete'])): ?>
    <a href='index.php?restore=<?php echo $_SESSION['delete'] ?>' id='restore'>restore</a>
    <?php unset($_SESSION['delete']); else: ?>
    <span></span>
    <?php endif ?>
      
    </strong>
      </p>
	    <tr>
       <th>N <sup>o</sup> </th>
       <th>Tasks</th>
       <th>Action</th>    
      </tr>
   </thead>
  <tbody>
<?php $n = 1; foreach ($tasks as $task) : ?>
     <tr>
      <td><?php echo $n; ?></td>
      <td><?php echo $task['task']; ?></td>
      <td > 
        <a href="index.php?completed=<?php echo $task['id']; ?>">
        <span title="Complete Task"><i  class="fa fa-check"></i></span></a>
        <a href="index.php?edit=<?php echo $task['id']; ?>">
        <span title="Edit Task"><i  class="fa fa-pencil-square-o"></i></span></a>
      <a href="index.php?delete=<?php echo $task['id']; ?>" id="delete">
        <span title="Delete Task"><i class="fa fa-trash-o"></i></span></a>  
      </td> 
  	 </tr>
    <?php $n++; endforeach; ?>
  </tbody>
 </table>
 <hr style='margin: 20px 0px'>
 <table class="table">
	 <thead id="completed">
    <p><strong> Completed Tasks: 
      <a href="index.php?clear2"><span id='clear'  title="Clear Completed Tasks"><i class='fa fa-trash'></i> Clear</span></a>
    </strong> 
    </p>
	    <tr>
       <th>N <sup>o</sup></th>
       <th>Tasks</th>
       <th>Action</th>    
      </tr>
   </thead>
  <tbody>
    <?php 
      if (isset($_GET['completed'])) {
// SELECT ALL FROM THE TASKS TABLE 
          $completed_id = $_GET['completed'];
          $query = "SELECT * FROM tasks WHERE id = $completed_id ";
          $results = mysqli_query($db, $query);
        if ($results) {
// THEN INSERT ALL INTO THE COMPLETED TASK TABLE
          $fetch = mysqli_fetch_assoc($results);
          $user_id = $fetch['user_id'];
          $tasks = $fetch['task'];
          $query1 = "INSERT INTO completed_tasks (user_id, tasks) VALUE($user_id, '$tasks') ";
          $new_results = mysqli_query($db, $query1);
          if ($new_results) {
          //Delete Task once completed
            $query = "DELETE FROM tasks WHERE id = $completed_id ";
            $result = mysqli_query($db, $query);
            if ($result) {
              $_SESSION['success'] = "Applause! You've Completed a Task!";
               header("location: index.php");
                exit();
            }
          }
        }
      }
// SELECT ALL FROM THE COMPLETED TASKS TABLE AND DISPLAY TO USER
      $query = "SELECT * FROM completed_tasks WHERE user_id = " . $_SESSION['id'];
      $results = mysqli_query($db, $query);
      $tasks = mysqli_fetch_all($results, MYSQLI_ASSOC);
      $n = 1; foreach ($tasks as $task) : 
    ?>
     <tr id="complete">
      <td><?php echo $n; ?></td>
      <td><?php echo $task['tasks']; ?></td>
      <td > 
      <a href="index.php?deleted=<?php echo $task['id']; ?>" id="delete">
        <span title="Delete Task"><i class="fa fa-trash-o"></i></span></a>  
      </td> 
  	 </tr>
    <?php $n++; endforeach;
 
    ?>
  </tbody>
 </table>
</div>
 <footer>
     Todo list Application Develoed by <b>Arnauld Anguh</b>.<br> &copy; Copyright 
     <script>document.write(new Date().getFullYear());</script>
 </footer>
</body>
</html>