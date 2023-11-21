<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Nunito:ital,wght@0,200;0,300;0,400;0,500;0,600;1,200;1,300;1,400;1,500;1,600&display=swap" rel="stylesheet">
        <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@4.4.1/dist/css/bootstrap.min.css"
     integrity="sha384-Vkoo8x4CGsO3+Hhxv8T/Q5PaXtkKtu6ug5TOeNV6gBiFeWPGFN9MuhOf23Q9Ifjh" crossorigin="anonymous">

    <link rel="stylesheet" href="style.css">
</head>
<body>
    <?php 
  
    $msg="";
    $username = isset($_POST['username'])? $_POST['username'] : "";
    $password = isset($_POST['password'])? $_POST['password'] : "";
    $login = isset($_POST['login'])? $_POST['login'] : "";

    if(empty($username) && empty($password)){
        $msg= "Insert username and password";
    } else{
        $conn = mysqli_connect("localhost", "root", "", "td_blogs");

        if(!$conn){
            $msg = "Server connection failed" . mysqli_connect_error();
        } else{
            $sql = "SELECT * FROM `users` WHERE (username = '$username' ) AND (password = '$password')";

            $get_user = mysqli_query($conn, $sql);
            $user = mysqli_fetch_assoc($get_user);


            if($username = $user['username'] && $password = $user['password']){
               session_start();
                $_SESSION['ID'] = $user['ID'];
                $_SESSION['username'] = $user['username'];
                $_SESSION['login'] = true;

                $msg = 'Login Successfull';
                header("Location:profile.php?msg={$msg}");
            } else{
                $msg = "Login Failed";
            }
        }
    }
    ?>

    <?php   if(isset($_SESSION['login']) && $_SESSION['login'] == true){ ?>
        <?php header("Location:profile.php"); ?>
    <?php } else{?>
    <div class="container">
        <section>
            <div class="container">
                <div class="row justify-content-center">
                    <div class="col-lg-4 col-md-6 d-flex flex-column align-items-center justify-content-center">

                        <div class="justity-content-center py-4">
                            <a href="#" class="logo w-auto"><span>TD Blogs</span></a>
                        </div>

                        <div class="card">
                            <div class="card-body">

                              <div class="pt-4 text-center">
                                <h5 class="card-title ">Log to your Account</h5>
                                <p> <?php echo isset($msg)? $msg : ""; ?></p>
                                <p class=" small">Enter your personal details to create account</p>
                              </div>

                              <form action="" class="row" method="post">
                                <div class="col-12">
                                    <label for="">Username</label>
                                    <div class="input-group">
                                        <span class="input-group-text">@</span>
                                        <input type="text" name="username" class="form-control" id="" required>
                                    </div>
                                </div>
                                <div class="col-12">
                                    <label class="form-label mt-2" for="">Password</label>
                                    <input type="text" name="password" class="form-control" id="" required>
                                </div>

                                <div class="col-12">
                                    <div class="form-check">
                                        <input class="form-check-input" type="checkbox" name="terms" required>
                                        <label class="form-check-label" for="">I agree and accept the<a href="#"> terms and conditions</a></label>
                                    </div>
                                </div>
                                <div class="col-12">
                                    <button class="btn btn-primary w-100" name="login">Log in</button>
                                </div>
                                <div class="col-12">
                                    <p class="mt-3">Don't have account? <a href="registration.php">Create an Account</a></p>
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
<?php }?>

    <script src="https://code.jquery.com/jquery-3.4.1.slim.min.js" integrity="sha384-J6qa4849blE2+poT4WnyKhv5vZF5SrPo0iEjwBvKU7imGFAV0wwj1yYfoRSJoZ+n" crossorigin="anonymous"></script>
    <script src="https://cdn.jsdelivr.net/npm/popper.js@1.16.0/dist/umd/popper.min.js" integrity="sha384-Q6E9RHvbIyZFJoft+2mJbHaEWldlvI9IOYy5n3zV9zzTtmI3UksdQRVvoxMfooAo" crossorigin="anonymous"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@4.4.1/dist/js/bootstrap.min.js" integrity="sha384-wfSDF2E50Y2D1uUdj0O3uMBJnjuUD4Ih7YwaYd1iqfktj0Uod8GCExl3Og8ifwB6" crossorigin="anonymous"></script>
</body>
</html>