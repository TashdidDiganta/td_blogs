<?php     session_start();
if(!isset($_SESSION['login']) && $_SESSION['login'] != true){
    header("Location:login.php");
    exit;
} else{
    // if(isset($_SESSION['registration']) && $_SESSION['registration'] == true){
    //     header("Location:login.php");
    //     exit;
    // };
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Profile</title>
    <script src="https://kit.fontawesome.com/4ac9a7d2dc.js" crossorigin="anonymous"></script>
    <link href="https://fonts.googleapis.com/css2?family=Nunito:ital,wght@0,200;0,300;0,400;0,500;0,600;1,200;1,300;1,400;1,500;1,600&display=swap" rel="stylesheet">
        <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@4.4.1/dist/css/bootstrap.min.css"
     integrity="sha384-Vkoo8x4CGsO3+Hhxv8T/Q5PaXtkKtu6ug5TOeNV6gBiFeWPGFN9MuhOf23Q9Ifjh" crossorigin="anonymous">

     <link rel="stylesheet" href="style.css">
</head>
<body>
    <?php 
    $msg="";

    $id = $_SESSION['ID'];
    $conn = mysqli_connect("localhost", "root", "", "td_blogs");


  

    if(!$conn){
        $msg = "Server connect error". mysqli_connect_error();
    } else{
        $update_user_info = isset($_POST['update_user_info'])? $_POST['update_user_info'] :"";

        if(isset($_POST['update_user_info'])){
        // get user data 
        $username = isset($_POST['name'])? $_POST['name'] :"";
        $user_bio = isset($_POST['user_bio'])? $_POST['user_bio'] :"";
        $company= isset($_POST['company'])? $_POST['company'] :"";
        $job = isset($_POST['job'])? $_POST['job'] :"";
        $country = isset($_POST['country'])? $_POST['country'] :"";
        $address = isset($_POST['address'])? $_POST['address'] :"";
        $phone = isset($_POST['phone'])? $_POST['phone'] :"";
   
        $user_bio = mysqli_real_escape_string($conn, $user_bio);

        //add user more info
        $update_sql = "UPDATE `users` SET  username='$username', user_bio='$user_bio', company='$company', job='$job', country='$country', address='$address', phone='$phone' WHERE ID = $id";
        mysqli_query($conn, $update_sql);
        }


        //Upload content
        if(isset($_POST['upload'])){
            $content_image = isset($_POST['content_image'])? $_POST['content_image'] : "";
            $content_title = isset($_POST['content_title'])? $_POST['content_title'] : "";
            $content = isset($_POST['content'])? $_POST['content'] : "";
            $content = mysqli_real_escape_string($conn, $content);


            $upload_sql = "INSERT INTO `content` (`content_image`,`content_title`,`content`, `user_id`) VALUES ('$content_image', '$content_title', '$content', $id);";
            $set_content = mysqli_query($conn, $upload_sql);
           
          
        }

        $content_sql = "SELECT * FROM `content` WHERE user_id = $id";
        $get_content = mysqli_query($conn, $content_sql);


        //get user info
        $sql = "SELECT * FROM `users` WHERE ID = $id";
        $data = mysqli_query($conn, $sql);
        $fetch_data = mysqli_fetch_assoc($data);

       $change_password = isset($_POST['change_password'])? $_POST['change_password'] :"";

       if(isset($_POST['change_password'])){
        $password = isset($_POST['password'])? $_POST['password'] :"";
        $newpassword = isset($_POST['newpassword'])? $_POST['newpassword'] :"";
        $renewpassword = isset($_POST['renewpassword'])? $_POST['renewpassword'] :"";
        if($fetch_data['password'] == $password){
            if($newpassword == $renewpassword){
                $update_pass = "UPDATE `users` SET  password='$newpassword' WHERE ID = $id";
               $update_pass = mysqli_query($conn, $update_pass);
               if($update_pass == true){
                echo "Password Update";
               }
            }
        }
       }


       //Image upload
       if(isset($_POST['avatar_upload'])){
            $ext = pathinfo($_FILES['user_avatar']['name'], PATHINFO_EXTENSION);
            $types = array('png', 'jpg', 'jpeg', 'svg', 'gif');
            if(!in_array($ext, $types)){
                echo "Your are not allowed to upload this file!";
            }
            elseif($_FILES['user_avatar']['size']  > 100000){
                echo "File is too large!";
            }else{
                $upload_dir = dirname(__FILE__) . '/uploads';
                if(!file_exists($upload_dir)){
                    if(mkdir($upload_dir, 0777, true)){
                        $file_name = $_FILES['user_avatar']['name'];
                        $file_upload_path = $upload_dir . '/' . $file_name;
                        if(file_exists($file_upload_path)){
                            $file_name = rand(0, 999999). '.'.$ext;
                            $file_upload_path = $upload_dir . '/' . $file_name;
                        }
                        if(move_uploaded_file($_FILES['user_avatar']['tmp_name'], $file_upload_path)){   
                           $host = $_SERVER['HTTP_ORIGIN'];
                           $url = $host . '/td_blogs/uploads/' . $file_name;
                           $update_avatar_sql = "UPDATE `users` SET user_avater = '{$url}' WHERE ID = $id";
                            $update_avatar = mysqli_query($conn, $update_avatar_sql);
                            if($update_avatar){
                                $msg = "Avatar uploda successfully!";
                            }
                        }
                    }
                }else{
                    $file_name = $_FILES['user_avatar']['name'];
                    $file_upload_path = $upload_dir . '/' . $_FILES['user_avatar']['name'];
                    if(file_exists($file_upload_path)){
                        $file_name = rand(0, 999999). '.'.$ext;
                        $file_upload_path = $upload_dir . '/' . $file_name;
                    }
                    if(move_uploaded_file($_FILES['user_avatar']['tmp_name'], $file_upload_path)){
                       $host = $_SERVER['HTTP_ORIGIN'];
                       $url = $host . '/td_blogs/uploads/' . $file_name;
                       $update_avatar_sql = "UPDATE `users` SET user_avater = '{$url}' WHERE ID = $id";
                       $update_avatar = mysqli_query($conn, $update_avatar_sql);
                       if($update_avatar){
                        $msg = "Avatar uploda successfully!";
                       }
                    }
                }
            }
       }
    }
    
    ?>
    <header class="header d-flex align-items-center">
            <div class="d-flex">
                <a href="#" class="logo"><span>TD Blogs</span></a>
            </div>

            <div class="search-bar">
                <form action="" class=" search-form d-flex align-items-center">
                    <input  type="text" placeholder="Search">
                    <button type="submit"><i class="fa-solid fa-magnifying-glass"></i></button>
                </form>
            </div>
            <nav class="header-nav">
                <ul>
                    <a class="nav-link nav-profile d-flex align-items-center pe-0" href="#">
                    <i class="fa-solid fa-user"></i>
                    <span class="d-none d-md-block dropdown-toggle ps-2"><?php echo $fetch_data['name']; ?></span>
                    </a><!-- End Profile Iamge Icon -->
                </ul>
            </nav>
        </header>
    <!-- Header Section End -->


    <!-- Sidebar Start -->
    <aside class="sidebar">
        <ul class="sidebar-nav">
            <li class="nav-item">
                <a href="#" class="nav-link">
                <i class="fa-solid fa-bars"></i>
                <span>Dashboard</span>
                </a>
            </li>
            <li class="nav-item">
                <a class="nav-link collapsed" href="profile.php">
                <i class="fa-regular fa-address-book"></i>
                <span>Profile</span>

                </a>
            </li>
            <li class="nav-item">
                <a class="nav-link collapsed" href="content.php">
                <i class="fa-regular fa-address-book"></i>
                <span>View Content</span>
                </a>
            </li>
            <li class="nav-item">
                <a class="nav-link collapsed" href="logout.php">
                <i class="fa-solid fa-right-from-bracket"></i>
                <span>log Out</span>
                </a>
            </li>
        </ul>
    </aside>
    <!-- Sidebar End -->


<main id="main" class="main">

    <section class="section profile">
    <div class="row">
        <div class="col-xl-4">
        <?php if(isset($msg) && $msg != ''){?>
            <p class="alert alert-success"><?php echo $msg; ?></p>
        <?php } ?>
        <div class="card">
            <div class="card-body profile-card pt-4 d-flex flex-column align-items-center">

            <img src="<?php echo $fetch_data['user_avater']; ?>" alt="Profile" class="rounded-circle">
            <h2><?php  echo $fetch_data['name']; ?></h2>
            <h3><?php echo $fetch_data['job'] ?></h3>
            <div class="social-links mt-2">
                <a href="#" class="twitter"><i class="fa-brands fa-twitter"></i></a>
                <a href="#" class="facebook"><i class="fa-brands fa-facebook"></i></a>
                <a href="#" class="instagram"><i class="fa-brands fa-instagram"></i></a>
                <a href="#" class="linkedin"><i class="fa-brands fa-linkedin"></i></a>
            </div>
            </div>
        </div>

        </div>

        <div class="col-xl-8">

        <div class="card">
            <div class="card-body pt-3">
            <!-- Bordered Tabs -->
            <ul class="nav nav-tabs nav-tabs-bordered">

                <li class="nav-item">
                <button class="nav-link active" data-bs-toggle="tab" data-bs-target="#profile-overview">Overview</button>
                </li>

                <li class="nav-item">
                <button class="nav-link" data-bs-toggle="tab" data-bs-target="#profile-edit">Edit Profile</button>
                </li>

                <li class="nav-item">
                <button class="nav-link" data-bs-toggle="tab" data-bs-target="#profile-change-password">Change Password</button>
                </li>
                <li class="nav-item">
                <button class="nav-link" data-bs-toggle="tab" data-bs-target="#add-content">Add Content</button>
                </li>

            </ul>
            <div class="tab-content pt-2">

                <div class="tab-pane fade show active profile-overview" id="profile-overview">
                <h5 class="card-title">About</h5>
                <p class="small fst-italic">Sunt est soluta temporibus accusantium neque nam maiores cumque temporibus. Tempora libero non est unde veniam est qui dolor. Ut sunt iure rerum quae quisquam autem eveniet perspiciatis odit. Fuga sequi sed ea saepe at unde.</p>

                <h5 class="card-title">Profile Details</h5>

                <div class="row">
                    <div class="col-lg-3 col-md-4 label ">Full Name</div>
                    <div class="col-lg-9 col-md-8"><?php  echo isset($fetch_data['name'])?$fetch_data['name'] : "Add Name";?></div>
                </div>

                <div class="row">
                    <div class="col-lg-3 col-md-4 label">Company</div>
                    <div class="col-lg-9 col-md-8"><?php  echo isset($fetch_data['company'])?$fetch_data['company'] : "Add company";?></div>
                </div>

                <div class="row">
                    <div class="col-lg-3 col-md-4 label">Job</div>
                    <div class="col-lg-9 col-md-8"><?php  echo isset($fetch_data['job'])?$fetch_data['job'] : "Add job";?></div>
                </div>

                <div class="row">
                    <div class="col-lg-3 col-md-4 label">Country</div>
                    <div class="col-lg-9 col-md-8"><?php  echo isset($fetch_data['country'])?$fetch_data['country'] : "Add country";?></div>
                </div>

                <div class="row">
                    <div class="col-lg-3 col-md-4 label">Address</div>
                    <div class="col-lg-9 col-md-8"><?php  echo isset($fetch_data['address'])?$fetch_data['address'] : "Add address";?></div>
                </div>

                <div class="row">
                    <div class="col-lg-3 col-md-4 label">Phone</div>
                    <div class="col-lg-9 col-md-8"><?php  echo isset($fetch_data['phone'])?$fetch_data['phone'] : "Add phone";?></div>
                </div>

                <div class="row">
                    <div class="col-lg-3 col-md-4 label">Email</div>
                    <div class="col-lg-9 col-md-8"><?php  echo isset($fetch_data['email'])?$fetch_data['email'] : "Add email";?></div>
                </div>

                </div>

                <div class="tab-pane fade profile-edit pt-3" id="profile-edit">

                <!-- Profile Edit Form -->
                <form method="post" enctype="multipart/form-data">
                    <div class="row mb-3">
                    <label for="profileImage" class="col-md-4 col-lg-3 col-form-label">Profile Image</label>
                    <div class="col-md-8 col-lg-9">
                        <img src="<?php echo $fetch_data['user_avater']; ?>" alt="Profile">
                        <div class="pt-2">
                            <input type="file" accept="image/jpg, image/png, image/jpeg, image/svg, image/gif" name="user_avatar">
                            <button type="submit" name="avatar_upload">Upload</button>
                            <!-- <a href="#" class="btn btn-primary btn-sm" title="Upload new profile image"><i class="fa-solid fa-circle-arrow-up"></i></a> -->
                        </div>
                    </div>
                    </div>

                    <div class="row mb-3">
                    <label for="fullName" class="col-md-4 col-lg-3 col-form-label">Full Name</label>
                    <div class="col-md-8 col-lg-9">
                        <input name="name" type="text" class="form-control" id="fullName" value="<?php  echo isset($fetch_data['name'])?$fetch_data['name'] : "Add Name";?>">
                    </div>
                    </div>

                    <div class="row mb-3">
                    <label for="about" class="col-md-4 col-lg-3 col-form-label">About</label>
                    <div class="col-md-8 col-lg-9">
                        <textarea name="user_bio" class="form-control" id="about" style="height: 100px"><?php  echo isset($fetch_data['user_bio'])?$fetch_data['user_bio'] : "Add user_bio";?></textarea>
                    </div>
                    </div>

                    <div class="row mb-3">
                    <label for="company" class="col-md-4 col-lg-3 col-form-label">Company</label>
                    <div class="col-md-8 col-lg-9">
                        <input name="company" type="text" class="form-control" id="company" value="<?php  echo isset($fetch_data['company'])?$fetch_data['company'] : "Add company";?>">
                    </div>
                    </div>

                    <div class="row mb-3">
                    <label for="Job" class="col-md-4 col-lg-3 col-form-label">Job</label>
                    <div class="col-md-8 col-lg-9">
                        <input name="job" type="text" class="form-control" id="Job" value="<?php  echo isset($fetch_data['job'])?$fetch_data['job'] : "Add job";?>">
                    </div>
                    </div>

                    <div class="row mb-3">
                    <label for="Country" class="col-md-4 col-lg-3 col-form-label">Country</label>
                    <div class="col-md-8 col-lg-9">
                        <input name="country" type="text" class="form-control" id="Country" value="<?php  echo isset($fetch_data['country'])?$fetch_data['country'] : "Add country";?>">
                    </div>
                    </div>

                    <div class="row mb-3">
                    <label for="Address" class="col-md-4 col-lg-3 col-form-label">Address</label>
                    <div class="col-md-8 col-lg-9">
                        <input name="address" type="text" class="form-control" id="Address" value="<?php  echo isset($fetch_data['address'])?$fetch_data['address'] : "Add address";?>">
                    </div>
                    </div>

                    <div class="row mb-3">
                    <label for="Phone" class="col-md-4 col-lg-3 col-form-label">Phone</label>
                    <div class="col-md-8 col-lg-9">
                        <input name="phone" type="text" class="form-control" id="Phone" value="<?php  echo isset($fetch_data['phone'])?$fetch_data['phone'] : "Add phone";?>">
                    </div>
                    </div>


                    <div class="text-center">
                    <button type="submit" name="update_user_info" class="btn btn-primary">Save Changes</button>
                    </div>
                </form><!-- End Profile Edit Form -->

                </div>


                <div class="tab-pane fade pt-3" id="profile-change-password">
                <!-- Change Password Form -->
                <form method="post">

                    <div class="row mb-3">
                    <label for="currentPassword" class="col-md-4 col-lg-3 col-form-label">Current Password</label>
                    <div class="col-md-8 col-lg-9">
                        <input name="password" type="password"  class="form-control" id="currentPassword">
                    </div>
                    </div>

                    <div class="row mb-3">
                    <label for="newPassword" class="col-md-4 col-lg-3 col-form-label">New Password</label>
                    <div class="col-md-8 col-lg-9">
                        <input name="newpassword" type="password"  class="form-control" id="newPassword">
                    </div>
                    </div>

                    <div class="row mb-3">
                    <label for="renewPassword" class="col-md-4 col-lg-3 col-form-label">Re-enter New Password</label>
                    <div class="col-md-8 col-lg-9">
                        <input name="renewpassword" type="password" class="form-control" id="renewPassword">
                    </div>
                    </div>

                    <div class="text-center">
                    <button type="submit" name="change_password" class="btn btn-primary">Change Password</button>
                    </div>
                </form><!-- End Change Password Form -->

                </div>

                <div class="tab-pane fade pt-3" id="add-content">
                <input type="text" placeholder="Write your content" class="form-control" data-bs-toggle="modal" data-bs-target="#exampleModal" value="">

                <!-- Content Upload -->
                <div class="modal fade" id="exampleModal" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
                <div class="modal-dialog">
                    <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="exampleModalLabel">Content</h5>
                    </div>
                    <div class="modal-body">
                        <form method="post">
                        <div class="mb-3">
                            <label for="recipient-name" class="col-form-label">Content Image:</label>
                            <input type="text" name="content_image" class="form-control" id="recipient-name">
                        </div>
                        <div class="mb-3">
                            <label for="recipient-name" class="col-form-label">Content Title:</label>
                            <input type="text" name="content_title" class="form-control" id="recipient-name">
                        </div>
                        <div class="mb-3">
                            <label for="message-text" class="col-form-label">content:</label>
                            <textarea class="form-control" name="content" rows="7" id="message-text" ></textarea>
                        </div>
                        <div class="modal-footer">
                        <button type="submit" data-bs-dismiss="modal" name="upload" class="btn btn-primary">Upload Content</button>
                        </div>
                        </form>
                    </div>
                    </div>
                </div>
                </div>
                <?php 
              
                     while($all_content = mysqli_fetch_assoc($get_content)){
                        echo '<div class="card mt-3">';
                               echo '<div>';
                                 echo '<button class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#editModal">'. "Edit" .'</button>';
                                 echo "<a class='btn btn-danger' href='delete.php?id={$all_content['ID']}'>". "Delete " ."</a>";
                               echo '</div>';
                             echo $all_content['content_image']. '</br>';
                             echo $all_content['content_title']. '</br>';
                             echo $all_content['content'];
                        echo '</div>';
                     }
               
                ?>
                <div class="modal fade" id="editModal" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
                <div class="modal-dialog">
                    <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="exampleModalLabel">Content</h5>
                    </div>
                    <div class="modal-body">
                        <form method="post">
                        <div class="mb-3">
                            <label for="recipient-name" class="col-form-label">Content Image:</label>
                            <input type="text" name="content_image" class="form-control" id="recipient-name">
                        </div>
                        <div class="mb-3">
                            <label for="recipient-name" class="col-form-label">Content Title:</label>
                            <input type="text" name="content_title" class="form-control" id="recipient-name">
                        </div>
                        <div class="mb-3">
                            <label for="message-text" class="col-form-label">content:</label>
                            <textarea class="form-control" name="content" rows="7" id="message-text" ></textarea>
                        </div>
                        <div class="modal-footer">
                        <button type="submit" data-bs-dismiss="modal" name="upload" class="btn btn-primary">Upload Content</button>
                        </div>
                        </form>
                    </div>
                    </div>
                </div>
                </div>
                </div>
            </div><!-- End Bordered Tabs -->

            </div>
        </div>

        </div>
    </div>
    </section>

</main><!-- End #main -->

<!-- ======= Footer ======= -->
<footer id="footer" class="footer">
<div class="copyright">
  &copy; Copyright <strong><span>TD_Blogs</span></strong>. All Rights Reserved
</div>
<div class="credits">

  Designed by <a href="">Tashdid Diganta</a>
</div>
</footer><!-- End Footer -->


    
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/js/bootstrap.bundle.min.js" integrity="sha384-MrcW6ZMFYlzcLA8Nl+NtUVF0sA7MsXsP1UyJoMp4YLEuNSfAP+JcXn/tWtIaxVXM" crossorigin="anonymous"></script>
</body>
</html>