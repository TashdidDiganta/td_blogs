<?php 

$msg="";

$id = $_GET['id'];


$conn = mysqli_connect("localhost", "root", "", "td_blogs");
$delete_sql = "DELETE FROM content WHERE ID = $id";

$delete = mysqli_query($conn, $delete_sql);

if($delete){
    header("Location:profile.php?msg=Blog is Deleted". $id);
    exit;
} else{
    header("Location:profile.php?msg=Soemething is wrong". $id);
    exit;
}

?>