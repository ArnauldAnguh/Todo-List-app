<?php 
  session_start(); 
// Db connection
  $db = mysqli_connect('127.0.0.1', 'root', '', 'todo') or die("Connection Error" . mysqli_error());
 // Login in a User
  if(isset($_POST['login'])) {
      $name = mysqli_real_escape_string($db,$_POST['username']);
      $pass = mysqli_real_escape_string($db,$_POST['password']);
      $query  = "SELECT * FROM users WHERE username = '$name' AND password = '$pass' LIMIT 1 ";
      $result = mysqli_query($db,$query);
      if ($result) {
        if (mysqli_num_rows($result) > 0) {
           $user = mysqli_fetch_assoc($result);
           $_SESSION['id']           = $user['id'];
           $_SESSION['username']     = $user['username']; 
           $_SESSION['firstname']    = $user['firstname'];
           $_SESSION['lastname']     = $user['lastname'];
           $_SESSION['success']      = 
           "<div style='color:lightlue;font-family:cursive;text-align:center;'> You are now logged in</div>";
          header('location: index.php');
          exit(0);
        } else {
            $_SESSION['error'] = "<span style='color:#fff;'>Wrong username/password Combination</span>";
            header('location: login.php');
            exit(0);
        }
      } else {
        die("Results Failed" . mysqli_error($db));
      }
  }
 // Registering a new user
  if (isset($_POST['register-user'])) {
        $firstname = mysqli_real_escape_string($db,$_POST['firstname']);
        $lastname  = mysqli_real_escape_string($db,$_POST['lastname']);
        $username  = mysqli_real_escape_string($db,$_POST['username']);
        $email     = mysqli_real_escape_string($db,$_POST['email']);
        $password  = mysqli_real_escape_string($db,$_POST['password']);
        $passconf  = mysqli_real_escape_string($db,$_POST['passwordconf']);

        // Password Verification
        if ($password !== $passconf) {
          $_SESSION['error'] = "Oops! Passwords do not match.";
          header('location: register.php');
          exit(0);
        }

        // Check whether Email exists
        $query  = "SELECT * FROM users WHERE  email = '$email' ";
        $email_test_query = mysqli_query($db,$query);

        if ($email_test_query) {
          if (mysqli_num_rows($email_test_query) > 0) {
             $_SESSION['error'] = "<div class='email'> This Email already exists in Database </div>";
             header('location: register.php');
             exit(0);
           } else {
            // Register User if email doesn't exists
            $query = "INSERT INTO users (firstname,lastname,username,email,password) VALUES ('$firstname', '$lastname', '$username', '$email', '$password' )";
            $run_query = mysqli_query($db, $query);
            if ($run_query) {
              $_SESSION['id'] = $db->insert_id;
              $_SESSION['success'] = "You've Been Successfully Registered!";
            } else {
              $_SESSION['error'] = "<div class='error'>Sorry, You have an error registering, try again!!</div>";
            }
            $query  = "SELECT * FROM users WHERE  id = ".$_SESSION['id'];
            $result = mysqli_query($db,$query);
            if (mysqli_num_rows($result) > 0) {
              $new_user = mysqli_fetch_assoc($result);
              $_SESSION['id']           = $new_user['id'];
              $_SESSION['username']     = $username; 
              $_SESSION['password']     = $password; 
              $_SESSION['firstname']    = $new_user['firstname'];
              $_SESSION['lastname']     = $new_user['lastname'];

           header('location: index.php');
           exit(0);
        } 
      }
   }
}
//ADDING A NEW TASK
    if (isset($_POST['submit'])) {
       $user_id  = $_SESSION['id'];
       $task = mysqli_real_escape_string($db,$_POST['tasks']);
      if (empty($task)) {
         $_SESSION['error'] = "<div class='error'>Please Pass in To-do task</div>";
         header("location: index.php");
         exit(0);
       }
      $query  = "INSERT INTO tasks (user_id,task) VALUES($user_id, '$task')";
      $task_results = mysqli_query($db,$query);

      if ($task_results) {
          $_SESSION['success'] = " Task Successfully Added! ";
        } else {
          echo "Failed To Set Task";
        }
        header('location: index.php');
        exit(0);
  }

// Edit task query 
      if (isset($_GET['edit'])) {
        $edit_task = $_GET['edit'];
        $query = "SELECT * FROM tasks WHERE id = $edit_task ";
        $run_query = mysqli_query($db, $query);
        $db_edit_task = mysqli_fetch_assoc($run_query);
        $_SESSION['db_task'] = $db_edit_task['task'];
        $_SESSION['new_id']  = $db_edit_task['id'];
      }
// Updating query
      if (isset($_POST['update'])) {
        $new_task = $_POST['task_new'];
        $new_id   = mysqli_real_escape_string($db,$_POST['task_id']);
        $query = "UPDATE tasks SET task = '$new_task' WHERE id = $new_id  ";
        $run_new_task = mysqli_query($db,$query);
        $_SESSION['success'] = " Task Successfully Updated!";
        header('location: index.php');
        exit(0);
      }
// CLEAR TODOS
      //clear uncompleted tasks
      if (isset($_GET['clear1'])) {
        $query = "DELETE FROM tasks WHERE user_id = " . $_SESSION['id'];
        $results = mysqli_query($db, $query);
        if ($results) {
          $_SESSION['success'] = "Tasks List Cleared!";
            header("location: index.php");
            exit(0);
        }
      }
      //clear completed tasks
      if (isset($_GET['clear2'])) {
        $query = "DELETE FROM completed_tasks WHERE user_id = " . $_SESSION['id'];
        $results = mysqli_query($db, $query);
        if ($results) {
          $_SESSION['success'] = "Completed Tasks List Cleared!";
            header("location: index.php");
            exit(0);
        }
      }
// DELETE a COMPLETED TASK 
        if (isset($_GET['deleted'])) {
          $_SESSION['deleted'] = $_GET['deleted'];
          $query = "DELETE FROM completed_tasks WHERE id = " . $_SESSION['deleted'];
          $result = mysqli_query($db, $query);
          if ($result) {
            $_SESSION['success'] = "Completed Task Successfully DELETED!";
            header("location: index.php");
            exit(0);
          }
        }
        
// DELETE TASKS QUERY
    if (isset($_GET['delete'])) {
// SELECT ALL FROM THE TASKS TABLE 
      $_SESSION['delete'] = $_GET['delete'];
          $query = "SELECT * FROM tasks WHERE id = " . $_SESSION['delete'];
          $results = mysqli_query($db, $query);
        if ($results) {
// THEN INSERT ALL INTO THE COMPLETED TASK TABLE
          $fetch = mysqli_fetch_assoc($results);
          $id = $_SESSION['delete'];
          $user_id = $fetch['user_id'];
          $tasks = $fetch['task'];

          $query = "INSERT INTO deleted_tasks (id, user_id, tasks) VALUE($id, $user_id, '$tasks') ";
          $results = mysqli_query($db, $query);
      //Delete Task once completed
          if ($results) {
            $query = "DELETE FROM tasks WHERE id = " . $_SESSION['delete'];
            $return = mysqli_query($db,$query);
            if($return) {
              $_SESSION['success'] = "Task Successfully Deleted! ";
            }
       header('location: index.php');
       exit(0);
      }
    }
  }
// <!-- RESTORE DELETED ITEM/ITEMS -->
if (isset($_GET['restore'])) {
    $restore = $_GET['restore'];
    $query = "SELECT * FROM deleted_tasks WHERE id = $restore";
    $result = mysqli_query($db, $query);
    if ($result) {
      $restored = mysqli_fetch_assoc($result);
      $user_id = $restored['user_id'];
      $task = $restored['tasks'];
      $query = "INSERT INTO tasks(user_id, task) VALUES($user_id, '$task')";
      $results = mysqli_query($db, $query);
      if ($results) {
      //Delete Task once completed
        $query = "DELETE FROM deleted_tasks WHERE id = " . $restore;
        $return = mysqli_query($db,$query);
        $_SESSION['success'] = "Task Successfully Restored From Trash ";
        header('location: index.php');
        exit();
      }
    }
}