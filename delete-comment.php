<?php 
$msg ="";

$get_id = $_GET['id'];


$conn = mysqli_connect("localhost", "root", "", "td_blogs");

$sql = "DELETE FROM comment WHERE ID = $get_id";

$insert = mysqli_query($conn, $sql);

if($insert){
    header('Location:content.php?$msg=Delete Successfully'. $get_id);
} else{
    header('Location:content.php?$msg=Something is wrong'. $get_id);
}

?>