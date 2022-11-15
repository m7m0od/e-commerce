<?php
include "connect.php";
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
// Include Navbar On All Pages Expect The One With $noNavbar Vairable
    if(!isset($noNav))
    {
        include $tpl."navbar.php";
    }

?>