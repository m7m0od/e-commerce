<?php
session_start();
if(isset($_GET['userOut'])){
    unset($_SESSION['username']);
    header("location:index.php");
}else{
    header("location:dashboard.php");
    exit();
}







?>