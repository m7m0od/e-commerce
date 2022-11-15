<!DOCTYPE html>
<html>
    <head>
        <meta charset="UTF-8">
        <meta http-equiv="X-UA-Compatible" content="IE=edge">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <title><?php getTitle()?></title>
        <link rel="stylesheet" href="<?php echo $css ?>bootstrap.min.css">
        <link rel="stylesheet" href="<?php echo $css ?>all.min.css">
        <link rel="stylesheet" href="<?php echo $css ?>jquery-ui.css">
        <link rel="stylesheet" href="<?php echo $css ?>jquery.selectBoxIt.css">
        <link rel="stylesheet" href="<?php echo $css ?>front.css">
    </head>
    <body>
        <div class='upper-bar'>
            <div class='container'>
                    <?php
                    if(isset($_SESSION['user']))
                    {
                    $getUser = $con->prepare("SELECT * FROM users WHERE UserName = ?");
                        $getUser->execute(array($_SESSION['user']));
                        $info=$getUser->fetch();
                        if(empty($info['avatar'])){
                            echo "<img src='admin/uploads/images/mmm.jpg'  class='rounded-circle my-avatar'>";
                        }else{
                            echo "<img src='admin/uploads/images/".$info['avatar']."'  class='rounded-circle my-avatar'>";
                        }
                        ?>
                        <div class='btn-group my-info'>
                            <span class='btn nav-link  dropdown-toggle' data-bs-toggle='collapse' role="button" data-bs-target="#navbarDropdown" aria-haspopup="true" aria-expanded="false">
                                <?php echo $_SESSION['user']; ?>
                                <span class='caret'></span>
                            </span>
                            <ul class='dropdown-menu' aria-labelledby="navbarDropdown" id="navbarDropdown">
                                <li><a class="dropdown-item" href='profile.php'>Profile</a></li>
                                <li><a class="dropdown-item" href='newad.php'>New Item</a></li>
                                <li><a class="dropdown-item" href='logout.php?userOut'>LogOut</a></li>
                            </ul>
                        </div>
                    <?php

                    }else{ 
                    ?>
                    <a href='login.php'>
                        <span class='pull-right'>Login/SignUp</span>
                    <div class='clr'></div>
                    </a>
                    <?php } ?>
            </div>
        </div>
        <nav class="navbar navbar-expand-lg navbar-dark bg-dark">
            <div class="container">
                <a class="navbar-brand" href="index.php">HomePage</a>
                <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#app-nav" aria-expanded="false"  >
                    <span class="navbar-toggler-icon"></span>
                </button>
                <div class="collapse navbar-collapse row" id="app-nav">
                <ul class="col-md-4 offset-md-8 navbar-nav">
                    <li class="nav-item">
                        <?php
                        foreach(getCategories() as $cat) {

                            echo '<li><a class="nav-link" href="categories.php?pageid=' . $cat['ID'] . '">' . $cat['Name'] . '</a></li>';
                        }
                        ?>
                    </li>
                </ul> 
                </div>
            </div>
        </nav>

