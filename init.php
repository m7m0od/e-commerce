<?php
ini_set('display_errors','on');
error_reporting(E_ALL);
$sessionUser='';
if(isset($_SESSION['user'])){
    $sessionUser=$_SESSION['user'];
}
include "admin/connect.php";
//Routes
    $tpl='inc/template/';//template directory
    $lang='inc/langs/';//langs directory
    $func='inc/functions/';//functions directory
    $css='layout/css/';//css directory
    $js='layout/js/';//js directory
    


// Include The Important Files
    include $func."functions.php";
    include $lang."english.php";
    include $tpl."header.php";

?>