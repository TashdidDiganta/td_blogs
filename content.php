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
    <title>Content</title>
    
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Nunito:ital,wght@0,200;0,300;0,400;0,500;0,600;1,200;1,300;1,400;1,500;1,600&display=swap" rel="stylesheet">
        <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@4.4.1/dist/css/bootstrap.min.css"
     integrity="sha384-Vkoo8x4CGsO3+Hhxv8T/Q5PaXtkKtu6ug5TOeNV6gBiFeWPGFN9MuhOf23Q9Ifjh" crossorigin="anonymous">
     <script src="https://kit.fontawesome.com/5769b18a7e.js" crossorigin="anonymous"></script>

     <link rel="stylesheet" href="style.css">
</head>
<body>
    <!-- Header Section Start -->
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
                <span class="d-none d-md-block dropdown-toggle ps-2">K. Anderson</span>
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
                <a href="#" class="nav-link collapsed">
                <i class="fa-regular fa-address-book"></i><span>Add Blog</span><i class="fa-solid fa-caret-down ms-auto"></i>
                </a>
                <ul>
                </ul>
            </li>
            <li class="nav-item">
                <a class="nav-link collapsed" href="profile.php">
                <i class="bi bi-person"></i>
                <span>Profile</span>
                </a>
            </li>
        </ul>
    </aside>
    <!-- Sidebar End -->

    <div class="main">
        <?php 
           $id = $_SESSION['ID'];
            $conn = mysqli_connect("localhost", "root", "", "td_blogs");
                $content_sql = "SELECT * FROM `content`";
                $get_content = mysqli_query($conn, $content_sql);

                //upload comment
      
                $submit_comment = isset($_POST['submit_comment'])? $_POST['submit_comment'] : "";
                if(isset($_POST['submit_comment'])){
                    $comment = isset($_POST['comment'])? $_POST['comment'] : "";
                    $blog_id = isset($_POST['blog_id'])? $_POST['blog_id'] : "";
                    $comment_sql = "INSERT INTO `comment` (`comment`, `user_id`, `blog_id`) VALUES ('$comment', '$id', '$blog_id');";
                    $set_comment = mysqli_query($conn, $comment_sql);
                } 

                // replay_comment

                if(isset($_POST['reply_comment'])){
                    $replay_comment = isset($_POST['reply_comment'])? $_POST['reply_comment'] : "";
                    $comment_id = isset($_POST['comment_id'])? $_POST['comment_id'] : "";
                    $replay_comment = mysqli_real_escape_string($conn, $replay_comment);
                    $replay_comment_sql = "INSERT INTO `replay_comment` (`replay_comment`, `comment_id`, `user_id`) VALUES ('$replay_comment', '$comment_id', '$id');";
                    $set_replay_comment = mysqli_query($conn, $replay_comment_sql);
                }

                

                echo '<div class="content-box">';

                    while($all_content = mysqli_fetch_assoc($get_content)){
                            
                        echo '<div class="row">';
                          echo '<div class="col-xl-8">';
                            echo '<div class="card mt-3 mb-5">';
                              echo '<div class="content">';
                                echo '<h1>'. $all_content['content_title'].'</h1>' ;
                                echo "<img src='{$all_content['content_image']}' width='800' height='600'>";
                                echo '<p>'. $all_content['content'].'</p>';
                          
                            echo '<form method="post">';
                                echo '<input name="comment" class="form-control w-50 mb-2" placeholder="Comment" />';
                                echo '<input name="blog_id" type="hidden" value="'.$all_content['ID'].'"/>';
                                echo '<button type="submit" name="submit_comment" class="btn btn-primary mb-3">'. "Comment" .'</button>';
                            echo '</form>';

                            $cmnt_sql = "SELECT * FROM `comment` WHERE blog_id = {$all_content['ID']};";
                            $cmnt_result = mysqli_query($conn, $cmnt_sql);
                            while($comments = mysqli_fetch_assoc($cmnt_result)){
                                $user_sql = "SELECT * FROM `users` WHERE ID = {$comments['user_id']};";
                                $user_result = mysqli_query($conn, $user_sql);
                                $user_info = mysqli_fetch_assoc($user_result);
                                
                                echo '<div class="comment-section">';
                                  echo '<div class="comment-box">';
                                    echo '<h6>'.$user_info['name'].'</h6>';
                                    echo '<p>'.$comments['comment'].'</p>'; 
                                  echo '</div>';
                                   echo '<div class="comm-acction-btn">';
                                    echo "<a data-toggle='collapse' href='#collapseExample-{$comments['ID']}' role='button' aria-expanded='false' aria-controls='collapseExample'>".'Replay'."</a>";
                                    echo "<a href='delete-comment.php?id={$comments['ID']}'>".'Delete'."</a>";
                                    echo "<div class='collapse' id='collapseExample-{$comments['ID']}'>";

                                        echo '<form method="post">';
                                        echo '<input name="comment_id" type="hidden" value="'.$comments['ID'].'"/>';
                                         echo '<input name="reply_comment" class="form-control w-50" placeholder="Replay Comment" />';
                                        echo '</form>';
                                    echo '</div>';
                                   echo '</div>';
                                echo '</div>';
             

                                //get replay comment
                                $cmnt_replay_sql = "SELECT * FROM `replay_comment` WHERE comment_id = {$comments['ID']};";
                                $get_cmnt_sql = mysqli_query($conn, $cmnt_replay_sql);
                                while($replay_comments = mysqli_fetch_assoc($get_cmnt_sql)){

                                //get user info
                                $sql = "SELECT * FROM `users` WHERE ID = $replay_comments[user_id];";
                                $data = mysqli_query($conn, $sql);
                                $user_name = mysqli_fetch_assoc($data);
                                // var_dump($user_name['name']);

                                    echo '<div class="comment-section">';
                                       echo '<div class="comment-box rply">';
                                            echo '<h6>'.$user_name['name'].'</h6>';
                                            echo '<p>'.$replay_comments['replay_comment'].'</p>'; 
                                       echo '</div>';     
                                    echo "</div>";
                                }
                            }
                        }

                        echo '</div>';
                  echo '</div>';
                echo '</div>';
                echo '</div>';
                echo '</div>';


            //get comment

           
        
        
        ?>
    </div>

    <script src="https://code.jquery.com/jquery-3.4.1.slim.min.js" integrity="sha384-J6qa4849blE2+poT4WnyKhv5vZF5SrPo0iEjwBvKU7imGFAV0wwj1yYfoRSJoZ+n" crossorigin="anonymous"></script>
    <script src="https://cdn.jsdelivr.net/npm/popper.js@1.16.0/dist/umd/popper.min.js" integrity="sha384-Q6E9RHvbIyZFJoft+2mJbHaEWldlvI9IOYy5n3zV9zzTtmI3UksdQRVvoxMfooAo" crossorigin="anonymous"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@4.4.1/dist/js/bootstrap.min.js" integrity="sha384-wfSDF2E50Y2D1uUdj0O3uMBJnjuUD4Ih7YwaYd1iqfktj0Uod8GCExl3Og8ifwB6" crossorigin="anonymous"></script>

</body>
</html>