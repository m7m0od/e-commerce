<?php 
ob_start();
session_start();
if(isset($_SESSION['username']))
{
    $pageTitle='Comments';
    include "init.php"; 


    $do=isset($_GET['do'])?$_GET['do']:'manage';

    if($do=='manage'){ 

        $stmt= $con->prepare("SELECT comments.*,items.Name,users.UserName 
                                FROM `comments` 
                                INNER JOIN items ON items.ItemID=comments.Item_ID
                                INNER JOIN users ON users.UserID=comments.User_ID
         ");
        $stmt->execute();
        $rows=$stmt->fetchAll();

        ?>
        <h1 class="text-center">Manage Comments</h1>
        <div class="container">
            <div class="table-responsive">
                <table class="main-table text-center table table-bordered">
                    <thead>
                        <tr>
                            <th>#ID</th>
                            <th>Comment</th>
                            <th>CommentDate</th>
                            <th>Item</th>
                            <th>User</th>
                            <th>Control</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php
                        foreach($rows as $row){
                            echo "<tr>";
                            echo "<td>". $row['CID'] . "</td>";
                            echo "<td>". $row['Comment'] . "</td>";
                            echo "<td>". $row['CommentDate'] . "</td>";
                            echo "<td>". $row['Name'] . "</td>";
                            echo "<td>". $row['UserName'] . "</td>";
                            echo "<td> <a href='comments.php?do=edit&UserID=" . $row['CID'] . "' class='btn btn-success'><i class='fa fa-edit'></i> Edit</a> 
                                        <a href='comments.php?do=delete&UserID=" . $row['CID'] . "' class='btn btn-danger confirm'><i class='fa fa-xmark'></i> Delete</a>";
                                        if($row['Status']==0){
                                            echo "<a href='comments.php?do=Activate&UserID=" . $row['CID'] . "' class='btn btn-info activate'><i class='fa fa-check'></i> Activate</a>";

                                        }
                                        echo "</td>";
                            echo "</tr>";
                        }
                         ?>
                    </tbody>
                </table>
            </div>
            </div>
        </div>
   <?php
   }elseif($do=='edit'){ 

        $userid=isset($_GET['UserID']) && is_numeric($_GET['UserID']) ? intval($_GET['UserID']) : 0;
        $stmt= $con->prepare("SELECT * FROM `comments` WHERE CID = ? LIMIT 1");
        $stmt->execute(array($userid));
        $row=$stmt->fetch();
        $count=$stmt->rowCount();//PDO Distinct *
        if($count>0)
        {
        ?>
            <h1 class="text-center">Edit Comments</h1>
            <div class="container">
                <form action="comments.php?do=update" method="POST">
                    <input type="hidden" name="userid" value="<?php echo $userid; ?>">
                    <div class="row mb-3">
                        <label class="col-sm-2 control-label">Comment</label>
                        <div class="col-sm-10 col-md-6 for-pos">
                            <textarea  name="comment" class="username form-control" autocomplete="off" required="required"><?php echo $row['Comment']; ?></textarea>
                            <div class="alert alert-danger custom-alert">
                                Comment can not be <strong>less than 4 chars</strong>
                            </div>
                        </div>
                    </div>

                    <div class="row mb-3">
                        <label class="col-sm-2 control-label">item</label>
                        <div class="col-sm-10 col-md-6 for-pos">
                            <select name='item'>
                                <?php
                                        $stmt=$con->prepare("SELECT * FROM items");
                                        $stmt->execute();
                                        $users=$stmt->fetchAll();
                                        foreach($users as $user)
                                        {
                                            echo "<option value='" . $user['ItemID'] ."'"; if($row['Item_ID']==$user['ItemID']){echo 'selected';}echo">" . $user['Name'] . "</option>";
                                        }
                                ?>
                            </select>
                        </div>
                    </div>

                    <div class="row mb-3">
                        <label class="col-sm-2 control-label">user</label>
                        <div class="col-sm-10 col-md-6 for-pos">
                            <select name='username'>
                                <?php
                                        $stmt=$con->prepare("SELECT * FROM users");
                                        $stmt->execute();
                                        $users=$stmt->fetchAll();
                                        foreach($users as $user)
                                        {
                                            echo "<option value='" . $user['UserID'] ."'"; if($row['User_ID']==$user['UserID']){echo 'selected';}echo">" . $user['UserName'] . "</option>";
                                        }
                                ?>
                            </select>
                        </div>
                    </div>

                   


                    <div class="row mb-3">
                    <label class="col-sm-2 control-label"></label><!-- just for design -->
                        <div class=" col-sm-10">
                            <input type="submit" value="save" class="btn btn-info btn-lg">
                        </div>
                    </div>
                </form>
            </div>

        
   <?php }else{
      $msg="<div class='alert alert-success text-center w-75 mx-auto my-5'>this id not found</div>";
      redHome($msg,5);
   }
   }elseif($do == 'update'){
       if($_SERVER['REQUEST_METHOD']=='POST')
       {
        $id=$_POST['userid'];
        $comment=$_POST['comment'];
        $item=$_POST['item'];
        $user=$_POST['username'];

     $errors=[];

     if(empty($comment))
     {
         $errors[]="comment is required";
     }elseif(!is_string($comment)){
         $errors[]="comment must be string";
     }elseif(strlen($comment)<5)
     {
         $errors[]="comment must be at less 5 chars";
     }
   

        if(empty($errors))
        {
            $stmt= $con->prepare("UPDATE `comments` SET Comment = ?,Item_ID = ?, User_ID = ? WHERE CID = ? ");
            $stmt->execute(array($comment,$item,$user,$id)); 
            $msg="<div class='alert alert-success text-center w-75 mx-auto my-5'>UPDATED</div>";
            redHome($msg,5);
            //header("location:dashboard.php?Updated");
            //echo $stmt->rowCount() . ' record Updated';
        }else{
            foreach($errors as $error)
            {
                echo "<div class='alert alert-danger'>" . $error. "</div>" ;
            }
        }

       }else{
           $msgError="<div class='alert alert-danger text-center w-75 mx-auto my-5'>Not Allow To You</div>";
           redHome($msgError,5);
       }
   }elseif($do == 'delete')
   {
    $userid=isset($_GET['UserID']) && is_numeric($_GET['UserID']) ? intval($_GET['UserID']) : 0;
    $count=checkItem('CID','comments',$userid);
    if($count > 0)
    {
        $stmt= $con->prepare("DELETE FROM `comments` WHERE CID = :zuserid");//:zuserid=?
        $stmt->bindParam(":zuserid",$userid);//if we write ? we do not need this step
        $stmt->execute();//array($userid) inside execute if we use ?
        $msg="<div class='alert alert-success text-center w-75 mx-auto my-5'>DELETED</div>";
        redHome($msg,'back',5);
        //header("location:dashboard.php?DELETED");
    }else{ 
        $msgError="<div class='alert alert-danger text-center w-75 mx-auto my-5'>This Id Is Not Exist</div>";
        redHome($msgError,5);
    }

   }elseif($do == 'Activate'){
    $userid=isset($_GET['UserID']) && is_numeric($_GET['UserID']) ? intval($_GET['UserID']) : 0;
    $count=checkItem('CID','comments',$userid);
    if($count > 0)
    {
        $stmt= $con->prepare("UPDATE `comments` SET Status = 1 WHERE CID = ?");
        $stmt->execute(array($userid));
        $msg="<div class='alert alert-success text-center w-75 mx-auto my-5'>" . $stmt->rowCount() . "Activated</div>";
        redHome($msg,5);
    }else{ 
        $msgError="<div class='alert alert-danger text-center w-75 mx-auto my-5'>This Id Is Not Exist</div>";
        redHome($msgError,5);
    }


   }


    include $tpl."footer.php";
}else{
    header("location:index.php");
    exit();
}
ob_end_flush();
?>
