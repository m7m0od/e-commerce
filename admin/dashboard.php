<?php 
ob_start();
session_start();
if(isset($_SESSION['username']))
{
    $pageTitle='Dashboard';
    include "init.php"; 


    $theLatestUsers=5; //I will make it var in parameter so it the same so i did it here for use it in heading
    $theLatestU=getLatest("*","users","UserID",$theLatestUsers);

    $theLatestItems=5;
    $theLatestI=getLatest("*","items","ItemID",$theLatestItems);

    $latestComment=5;
    ?>
    <div class='container text-center home-stats'>
        <h1>Welcome To Dashboard</h1>
        <div class='row'>
            <div class='col-md-3 statP'>
                <div class='stat st-members'>
                    Total Members
                    <span><a href='members.php'><?php echo countItems('UserID','users');  ?></a></span>
                </div> 
            </div>
            <div class='col-md-3'>
                <div class='stat st-pending'>
                    Pending Members
                    <span><a href='members.php?do=manage&page=Pending'><?php echo checkItem('RegStatus','users',0);  ?></a></span>
                </div>
            </div>
            <div class='col-md-3'>
                <div class='stat st-items'>
                    Total Items
                    <span><a href='item.php'><?php echo countItems('ItemID','items');  ?></a></span>
                </div>
            </div>
            <div class='col-md-3'>
                <div class='stat st-comments'>
                    Total Comments
                    <span><a href='comments.php'><?php echo countItems('CID','comments');  ?></a></span>
                </div>
            </div>
        </div>
    </div>

    <div class='container latest'>
        <div class='row'>
            <div class='col-sm-6'>
                <div class='panel panel-default'>
                   <div class='panel-heading'>
                        <i class='fa fa-users'></i> Latest <?php echo $theLatestUsers ?> Registred Users
                        <span class='toggle-info pull-right'>
                            <i class='fa fa-plus fa-lg'></i>
                        </span>
                    </div>
                    <div class='panel-body'>
                        <ul class='list-unstyled latest-users'>
                            <?php
                            if(! empty($theLatestU)){
                                foreach($theLatestU as $latestu)
                                    {
                                        echo 
                                        "<li>" . $latestu['UserName'] . 
                                            "<a href='members.php?do=edit&UserID=" . $latestu['UserID'] . "'>
                                                <span class='btn btn-success pull-right'>
                                                    <i class='fa fa-edit'></i> edit
                                                </span>
                                            </a>
                                            <div class='clr'></div>
                                        </li>"; 
                                    }
                                }else{
                                    echo "there is no records"; 
                                }
                            ?>
                        </ul>
                    </div>
                </div>
            </div>
            <div class='col-sm-6'>
                <div class='panel panel-default'>
                    <div class='panel-heading'>
                        <i class='fa fa-tag'></i> Latest <?php echo $theLatestItems ?> Items
                        <span class='toggle-info pull-right'>
                            <i class='fa fa-plus fa-lg'></i>
                        </span>
                    </div>
                    <div class='panel-body'>
                    <ul class='list-unstyled latest-users'>
                            <?php
                            if(! empty($theLatestI)){
                                foreach($theLatestI as $latesti)
                                    {
                                        echo 
                                        "<li>" . $latesti['Name'] . 
                                            "<a href='item.php?do=edit&UserID=" . $latesti['ItemID'] . "'>
                                                <span class='btn btn-success pull-right'>
                                                    <i class='fa fa-edit'></i> edit
                                                </span>
                                            </a>
                                            <div class='clr'></div>
                                        </li>"; 
                                    }
                                }else{
                                    echo "there is no items";
                                }
                            ?>
                        </ul>
                    </div>
                </div>
            </div>
        </div>

        <div class='row py-5'>
            <div class='col-sm-6'>
                <div class='panel panel-default'>
                   <div class='panel-heading'>
                        <i class="fa fa-comment"></i> Latest <?php echo $latestComment ; ?> Comments
                        <span class='toggle-info pull-right'>
                            <i class='fa fa-plus fa-lg'></i>
                        </span>
                    </div>
                    <div class='panel-body'>
                        <?php
                            $stmt= $con->prepare("SELECT comments.*,items.Name,users.UserName 
                            FROM `comments` 
                            INNER JOIN items ON items.ItemID=comments.Item_ID
                            INNER JOIN users ON users.UserID=comments.User_ID
                            ORDER BY CID DESC
                            LIMIT $latestComment
                            ");
                            $stmt->execute();
                            $rows=$stmt->fetchAll();   
                            if(! empty($rows)){
                            foreach($rows as $row)
                            {
                                echo "<div class='comment-box'>";
                                    echo "<span class='comment-n'>" . $row['UserName'] . "</span>";
                                    echo "<p class='comment-c'>" . $row['Comment'] . "</p>";
                                    echo "<div class='clr'></div>";
                                echo "</div>";

                            }
                        }else{
                            echo "there is no comment";
                        }
                        ?>
                    </div>
                </div>
            </div>
        </div>

    </div>

    <?php
    
    if(isset($_GET['Updated']))
    {
        echo "<div class='text-center m-5 alert alert-success'> record is Updated</div>";
    }
    if(isset($_GET['Inserted']))
    {
        echo "<div class='text-center m-5 alert alert-success'> record is Inserted</div>";
    }
    if(isset($_GET['DELETED']))
    {
        echo "<div class='text-center m-5 alert alert-success'> record is DELETED</div>";
    }
    include $tpl."footer.php";
}else{
    header("location:index.php");
    exit();
}

ob_end_flush();
?>
