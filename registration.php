<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Registration</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Nunito:ital,wght@0,200;0,300;0,400;0,500;0,600;1,200;1,300;1,400;1,500;1,600&display=swap" rel="stylesheet">
        <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@4.4.1/dist/css/bootstrap.min.css"
     integrity="sha384-Vkoo8x4CGsO3+Hhxv8T/Q5PaXtkKtu6ug5TOeNV6gBiFeWPGFN9MuhOf23Q9Ifjh" crossorigin="anonymous">

     <link rel="stylesheet" href="style.css">
</head>
<body>

<?php 

session_start();
if(isset($_SESSION['login']) && $_SESSION['login'] == true){
  header("Location:profile.php");
  exit;
}




if(isset($_POST['registration'])){
  if(!isset($_POST['name']) ||  !isset($_POST['email']) || !isset($_POST['username']) || !isset($_POST['password'])){
    $msg = "Please fill all this field";
  } else{
  
    $name = isset($_POST['name'])? $_POST['name'] :"";
    $email = isset($_POST['email'])? $_POST['email'] :"";
    $username = isset($_POST['username'])? $_POST['username'] :"";
    $password = isset($_POST['password'])? $_POST['password'] :"";

  
  
  
    $conn = mysqli_connect("localhost", "root", "", "td_blogs");
  
    if(!$conn){
      $msg = "Server connect error". mysqli_connect_error();
    } else{
      $email = mysqli_real_escape_string($conn, $email);
      $email = filter_var($email, FILTER_SANITIZE_EMAIL);
      $check_email = "SELECT * FROM `users` WHERE email = '{$email}'";
      $email_result = mysqli_query($conn, $check_email);
      $email_exist = mysqli_fetch_assoc($email_result);
  
      if(!empty($email_exist)){
        $msg = "This email alredy in used";
        
      } else{
  
        $sql = "INSERT INTO `users` (`name`, `email`, `username`, `password`) VALUES ('$name', '$email', '$username', '$password');";
        $data = mysqli_query($conn, $sql);
    

        $user_id = mysqli_insert_id($conn);
        $user_sql = "SELECT * FROM `users` WHERE ID = {$user_id}";
        $user_result = mysqli_query($conn, $user_sql);
        $user_info = mysqli_fetch_assoc($user_result);
    
    
      
    
        if(!$data){
          $msg = "Registration Failed";
        } else{
          
          $_SESSION['ID'] = $user_info['ID'];
          $_SESSION['username'] = $user_info['username'];
          $_SESSION['login'] = true;
          $msg = "Registration Successfull";
          header("Location:admin.php?msg={$msg}");
      }
        
      }
  }
  
  }
}



?>

  <main>
    <div class="container">
      <section class="py-4">
        <div class="container">
          <div class="row justify-content-center">
            <div class="col-lg-4 col-md-6 d-flex flex-column align-items-center justify-content-center">

              <div class=" justify-content-center py-4">
                <a href="index.html" class="logo d-flex align-items-center w-auto">
                  <span>TD Blogs</span>
                 
                </a>
              </div><!-- End Logo -->

              <div class="card mb-3">

                <div class="card-body">
                 
                  <div class="pt-4 pb-2">
                    <h5 class="card-title text-center pb-0">Create an Account</h5>
                    <?php if(isset($msg)){?>
                    <div class="alert alert-danger alert-dismissible fade show" role="alert">
                      <strong>Error</strong> <?php echo $msg; ?>
                      <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                    </div>
                    <?php }?>
                    <p >     
                      
                    
                    </p>
                    <p class="text-center small">Enter your personal details to create account</p>
                  </div>

                  <form class="row" method="post">
                    <div class="col-12">
                      <label for="yourName" class="form-label">Your Name</label>
                      <input type="text" name="name" class="form-control" required>
                    </div>

                    <div class="col-12">
                      <label for="yourEmail" class="form-label">Your Email</label>
                      <input type="email" name="email" class="form-control" required>
                    </div>

                    <div class="col-12">
                      <label for="yourUsername" class="form-label">Username</label>
                      <div class="input-group">
                        <span class="input-group-text">@</span>
                        <input type="text" name="username" class="form-control" required>
                      </div>
                    </div>

                    <div class="col-12">
                      <label for="yourPassword" class="form-label">Password</label>
                      <input type="password" name="password" class="form-control" required>
                    </div>

                    <div class="col-12">
                      <div class="form-check">
                        <input class="form-check-input" name="terms" type="checkbox" value="" required>
                        <label class="form-check-label" for="acceptTerms">I agree and accept the <a href="#">terms and conditions</a></label>
                      </div>
                    </div>
                    <div class="col-12">
                      <button class="btn btn-primary w-100" name="registration" type="submit">Create Account</button>
                    </div>
                    <div class="col-12">
                      <p class="small mb-0">Already have an account? <a href="pages-login.html">Log in</a></p>
                    </div>
                  </form>

                </div>
              </div>

              <div class="credits">
                Designed by <a href="#">Tashdid Diganta</a>
              </div>

            </div>
          </div>
        </div>

      </section>

    </div>
  </main>



<!-- bootstrap Cdn -->
<script src="https://code.jquery.com/jquery-3.4.1.slim.min.js" integrity="sha384-J6qa4849blE2+poT4WnyKhv5vZF5SrPo0iEjwBvKU7imGFAV0wwj1yYfoRSJoZ+n" crossorigin="anonymous"></script>
<script src="https://cdn.jsdelivr.net/npm/popper.js@1.16.0/dist/umd/popper.min.js" integrity="sha384-Q6E9RHvbIyZFJoft+2mJbHaEWldlvI9IOYy5n3zV9zzTtmI3UksdQRVvoxMfooAo" crossorigin="anonymous"></script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@4.4.1/dist/js/bootstrap.min.js" integrity="sha384-wfSDF2E50Y2D1uUdj0O3uMBJnjuUD4Ih7YwaYd1iqfktj0Uod8GCExl3Og8ifwB6" crossorigin="anonymous"></script>
</body>
</html>