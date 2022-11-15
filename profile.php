<?php 
session_start();
$pageTitle='Profile';
include "init.php";
if(isset($_SESSION['user'])){
    $getUser = $con->prepare("SELECT * FROM users WHERE UserName = ?");
    $getUser->execute(array($sessionUser));
    $info=$getUser->fetch();
?>

<h1 class='text-center'>my Profile</h1>
<div class='information block pb-3'>
    <div class='container'>
        <div class='panel panel-primary bg-info'>
            <div class='panel-heading p-2'>my infromation</div>
            <div class='panel-body'>
                <ul class="list-unstyled forIcon">
                    <li><i class='fa fa-unlock-alt fa-fw'></i><span>name</span> : <?php echo $info['UserName'] ?> </li>
                    <li><i class='fa fa-envelope-o fa-fw'></i><span>Email</span> : <?php echo $info['Email'] ?> </li>
                    <li><i class='fa fa-user fa-fw'></i><span>FullName</span> : <?php echo $info['FullName'] ?> </li>
                    <li><i class='fa fa-calendar fa-fw'></i><span>Reg Date</span> : <?php echo $info['Date'] ?> </li>
                    <li><i class='fa fa-tags fa-fw'></i><span>Fav Category</span> : </li>
                </ul>
            </div>
        </div>

    </div>
</div>

<div class='my-ads block pb-3'>
    <div class='container'>
        <div class='panel panel-primary bg-info'>
            <div class='panel-heading p-2'>my ads</div>
            <div class='panel-body'>
                <?php
                if(! empty(getItems('Member_ID',$info['UserID']))){
                    echo "<div class='row'>";
                    foreach(getItems('Member_ID',$info['UserID']) as $item){
                        echo "<div class='col-md-3 col-sm-6'>";
                            echo "<div class='thumbnail item-box'>";
                            echo "<span class='price'>" . $item['Price'] . "</span>";
                            if(empty($info['avatar'])){
                                echo "<img src='admin/uploads/images/mmm.jpg' class='img-fluid'>";
                            }else{
                                echo "<img src='admin/uploads/images/".$info['avatar']."' class='img-fluid'>";
                            }
                            echo "<div class='caption'>";
                            echo "<h3><a href='item.php?Itemid=".$item['ItemID']."'>" . $item['Name'] . "</a></h3>";
                            echo "<p>" . $item['Description'] . "</p>";
                            echo "<div class='date'>" . $item['Add_Date'] . "</div>";
                            echo "</div>";
                            echo "</div>";
                        echo "</div>";
                    }  
                    echo "</div>";
                }else{
                    echo "sorry there is no ads, Create <a href='newad.php' class='btn btn-info'>New Ads</a>";
                }
                ?> 
            </div>
        </div>

    </div>
</div>

<div class='my-comments block pb-3'>
    <div class='container'>
        <div class='panel panel-primary bg-info'>
            <div class='panel-heading p-2'>latest comments</div>
            <div class='panel-body'>
               <?php
                    $stmt= $con->prepare("SELECT Comment FROM comments WHERE User_ID = ? ");
                    $stmt->execute(array($info['UserID']));
                    $comments=$stmt->fetchAll();

                    if(! empty($comments)){
                        foreach($comments as $comment){
                            echo "<p>" . $comment['Comment'] . "</p><br>";
                        }

                    }else{
                        echo "there is no comments";
                    }
               ?>
            </div>
        </div>  
    </div>
</div>





<?php
}else{
    header("location:login.php");
    exit();
}
include $tpl."footer.php";
?>