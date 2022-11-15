<?php 
session_start();
$pageTitle='Show Item';
include "init.php";
$userid=isset($_GET['Itemid']) && is_numeric($_GET['Itemid']) ? intval($_GET['Itemid']) : 0;
$stmt= $con->prepare("SELECT items.*,categories.Name AS CATNAME,users.UserName,users.avatar FROM `items` INNER JOIN categories ON categories.ID = items.Cat_ID INNER JOIN users ON users.UserId = items.Member_ID WHERE ItemID = ? AND Approve = 1");
$stmt->execute(array($userid));
$count=$stmt->rowCount();
if($count > 0){
    $row=$stmt->fetch();


?>

<h1 class='text-center'><?php echo $row['Name'] ?></h1>
<div class='container'>
    <div class='row'>
        <div class='col-md-3'>
        <?php 
                if(empty($row['avatar'])){
                    echo "<img src='admin/uploads/images/mmm.jpg'  class='img-fluid'>";
                }else{
                    echo "<img src='admin/uploads/images/".$row['avatar']."'  class='img-fluid'>";
                }
         ?>
        </div>
        <div class='col-md-9 item-info'>
            <h2><?php echo $row['Name'] ?> </h2>
            <p><?php echo $row['Description'] ?> </p>
            <ul class='list-unstyled'>
                <li><span>Added Date :</span><?php echo $row['Add_Date'] ?> </li>
                <li><span>Price :</span><?php echo $row['Price'] ?> </li>
                <li><span>Made In :</span> <?php echo $row['Country_Made'] ?> </li>
                <li><span>Category Name:</span><a href="categories.php?pageid=<?php echo $row['Cat_ID'] ?>"><?php echo $row['CATNAME'] ?></a></li>
                <li><span>Added By :</span> <a  href='#'><?php echo $row['UserName'] ?></a></li>
            </ul>
        </div>

    </div>
    <hr class='custom-hr'>
    <?php if(isset($_SESSION['user'])){ ?>
    <div class='row'>
        <div class='offset-md-3 col-md-3'>
            <div class='add-comment'>
                <h3>Add Your Comment</h3>
                <form action="<?php echo $_SERVER['PHP_SELF'] . "?Itemid=" . $row['ItemID'] ?>" method='POST'>
                    <textarea name='comment' required></textarea>
                    <input class='btn btn-info'type='submit' value='Add Comment'>
                </form>
                    <?php
                        if($_SERVER['REQUEST_METHOD']=='POST'){

                            $comment=filter_var($_POST['comment'],FILTER_SANITIZE_FULL_SPECIAL_CHARS);
                            $userComid=$_SESSION['uid'];
                            $itemComid=$row['ItemID'];

                            if(! empty($comment)){
                                $stmt=$con->prepare("INSERT INTO `comments`(Comment,Status,CommentDate,Item_ID,User_ID) VALUES (:zcomment, 0, NOW(), :zitemid, :zuserid)");
                                $stmt->execute(array('zcomment' => $comment,'zitemid' => $itemComid,'zuserid' => $userComid));
                                if($stmt){
                                    echo "<div class='alert alert-success'>Comment Added</div>";
                                }
                            }
                        }   
                    ?>
            </div>
        </div>
    </div>
    <?php }else{
        echo "Login First";
    } ?>
    <hr class='custom-hr'>
    <?php
        $stmt= $con->prepare("SELECT comments.*,users.UserName AS member,users.avatar
        FROM `comments` 
        INNER JOIN users ON users.UserID=comments.User_ID
        WHERE Item_ID = ? 
        AND Status = 0
        ORDER BY CID DESC
        ");
        $stmt->execute(array($row['ItemID']));
        $rows=$stmt->fetchAll();  
    ?>
    <?php foreach($rows as $row){  ?>
        <div class='comment-box'>
            <div class='row'>
                <div class='col-sm-2 text-center'>
                    <?php 
                        if(empty($row['avatar'])){
                            echo "<img src='admin/uploads/images/mmm.jpg'  class='img-fluid rounded-circle w-75'>";
                        }else{
                            echo "<img src='admin/uploads/images/".$row['avatar']."'  class='img-fluid rounded-circle w-75'>";
                        }
                    ?>
                    <?php echo $row['member'] ?></div>
                <div class='col-sm-10'><p class='lead'><?php echo $row['Comment'] ?></p></div>
            </div>
        </div>
        <hr class='custom-hr'>
    <?php  } ?>
</div>

<?php
}else{
    echo "this id is not exist or item under Approve";
}

include $tpl."footer.php";
?>