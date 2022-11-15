<?php 
ob_start();
session_start();
if(isset($_SESSION['username']))
{
    $pageTitle='Members';
    include "init.php"; 


    $do=isset($_GET['do'])?$_GET['do']:'manage';

    if($do=='manage'){ 

        $query='';
        if(isset($_GET['page']) && $_GET['page'] == 'Pending' ){
            $query='AND RegStatus = 0';
        }
        $stmt= $con->prepare("SELECT * FROM `users` WHERE GroupID != 1 $query");
        $stmt->execute();
        $rows=$stmt->fetchAll();
        ?>
        <h1 class="text-center">Manage Members</h1>
        <div class="container">
            <div class="table-responsive">
                <table class="main-table manage-members text-center table table-bordered">
                    <thead>
                        <tr>
                            <th>#ID</th>
                            <th>Avatar</th>
                            <th>Username</th>
                            <th>Email</th>
                            <th>Full Name</th>
                            <th>Registered Date</th>
                            <th>Control</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php
                        foreach($rows as $row){
                            echo "<tr>";
                            echo "<td>". $row['UserID'] . "</td>";
                            echo "<td>";
                                if(!empty($row['avatar'])){
                                    echo "<img src='uploads/images/". $row['avatar'] ."'alt=''>";
                                }else{
                                    echo "no avatar";
                                }
                            echo "</td>";
                            echo "<td>". $row['UserName'] . "</td>";
                            echo "<td>". $row['Email'] . "</td>";
                            echo "<td>". $row['FullName'] . "</td>";
                            echo "<td>". $row['Date'] . "</td>";
                            echo "<td> <a href='members.php?do=edit&UserID=" . $row['UserID'] . "' class='btn btn-success'><i class='fa fa-edit'></i> Edit</a> 
                                        <a href='members.php?do=delete&UserID=" . $row['UserID'] . "' class='btn btn-danger confirm'><i class='fa fa-xmark'></i> Delete</a>";
                                        if($row['RegStatus']==0){
                                            echo "<a href='members.php?do=Activate&UserID=" . $row['UserID'] . "' class='btn btn-info activate'><i class='fa fa-check'></i> Activate</a>";

                                        }
                                        echo "</td>";
                            echo "</tr>";
                        }
                         ?>
                    </tbody>
                </table>
            </div>
            <a href="members.php?do=add" class="btn btn-info my-5">Add Member</a>
        </div>
   <?php }
    elseif($do=='add')
    {
        ?>
        <h1 class="text-center">Add Members</h1>
            <div class="container">
                <form action="members.php?do=insert" method="POST" enctype="multipart/form-data">
                    <div class="row mb-3">
                        <label class="col-sm-2 control-label">Username</label>
                        <div class="col-sm-10 col-md-6 for-pos">
                            <input type="text" name="username" class="username form-control" autocomplete="off" required="required" placeholder="username to login into shop">
                            <div class="alert alert-danger custom-alert">
                                Username can not be <strong>less than 4 chars</strong>
                            </div>
                        </div>
                    </div>

                    <div class="row mb-3">
                        <label class="col-sm-2 control-label">Password</label>
                        <div class="col-sm-10 col-md-6 for-pos">
                            <input type="password" name="password" class="showpassword email form-control" autocomplete="new-password" required="required" placeholder="password must be complex">
                            <i class="show-pass fa fa-eye"></i>
                            <div class="alert alert-danger custom-alert">
                                Password can not be <strong>Empty</strong>
                            </div>
                        </div>
                    </div>

                    <div class="row mb-3">
                        <label class="col-sm-2 control-label">Email</label>
                        <div class="col-sm-10 col-md-6 for-pos">
                            <input type="email" name="email" class="email form-control"autocomplete="off" required="required" placeholder="email must be valid">
                            <div class="alert alert-danger custom-alert">
                                Email can not be <strong>Empty</strong>
                            </div>
                        </div>
                    </div>

                    <div class="row mb-3">
                        <label class="col-sm-2 control-label">Full Name</label>
                        <div class="col-sm-10 col-md-6 for-pos">
                            <input type="text" name="full" class="fullname form-control"autocomplete="off" required="required" placeholder="fullname important for your profile">
                            <div class="alert alert-danger custom-alert">
                                Username can not be <strong>less than 10 chars</strong>
                            </div>
                        </div>
                    </div>

                    <div class="row mb-3">
                        <label class="col-sm-2 control-label">User Img</label>
                        <div class="col-sm-10 col-md-6 for-pos">
                            <input type="file" name="avatar" class="fullname form-control" required="required">
                        </div>
                    </div>

                    <div class="form-group row">
                    <label class="col-sm-2 control-label"></label><!-- just for design -->
                        <div class=" col-sm-10">
                            <input type="submit" value="ADD" class="btn btn-info btn-lg">
                        </div>
                    </div>
                </form>
            </div>


        <?php
    }
    elseif($do=='insert')
    {
       if($_SERVER['REQUEST_METHOD']=='POST')
       {
           
           $user=$_POST['username'];
           $pass=$_POST['password'];
           $email=$_POST['email'];
           $name=$_POST['full'];

           $hashpass=sha1($_POST['password']);

           print_r($_FILES['avatar']);

           $avatarName=$_FILES['avatar']['name'];
           $avatarTempName=$_FILES['avatar']['tmp_name'];
           $avatarSize=$_FILES['avatar']['size'];
           $avatarType=$_FILES['avatar']['type'];

           $avatarMineExtension=array("jpeg","jpg","png","gif");
           $avatarExtension=pathinfo($imageName,PATHINFO_EXTENSION);



        $errors=[];

        if(empty($user))
        {
            $errors[]="username is required";
        }elseif(!is_string($user)){
            $errors[]="username must be string";
        }elseif(strlen($user)<5)
        {
            $errors[]="username must be at less 5 chars";
        }

        if(empty($pass))
        {
            $errors[]="pass is required";
        }

        if(empty($email))
        {
            $errors[]="email is required";
        }

        if(empty($name))
        {
            $errors[]="full name required";
        }

        if(!empty($avatarName) && ! in_array(strtolower($avatarExtension),$avatarMineExtension)){
            $errors[]="img ext not allowed ";
        }

        if(empty($avatarName))
        {
            $errors[]="img is required";
        }

        if($avatarSize > 4194304)
        {
            $errors[]="img must be less than 4MB";
        }


        if(empty($errors))
        {
            $avatar=rand(0,100000) . '_' . $avatarName ;
            move_uploaded_file($avatarTempName,"uploads\images\\".$avatar);
            $count=checkItem('UserName','users',$user);
            if($count > 0){
               $msgError="<div class='alert alert-danger text-center w-75 mx-auto my-5'>this record is already exist</div>";
               redHome($msgError,5);
            }else{
                $stmt= $con->prepare("INSERT INTO `users`(UserName,Password,Email,FullName,RegStatus,Date,avatar) VALUES (:zuser, :zpass, :zemail, :zname, 1, now(), :zimg)");
                $stmt->execute(array('zuser' => $user, 'zpass' => $hashpass, 'zemail' => $email, 'zname' =>$name,'zimg' =>$avatar)); 
                $msg="<div class='alert alert-success text-center w-75 mx-auto my-5'>" . $stmt->rowCount() . "record inserted</div>";
                redHome($msg);
                //header("location:dashboard.php?Inserted");
                //echo $stmt->rowCount() . ' record inserted';
            }
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
    }
    elseif($do=='edit'){ 

        $userid=isset($_GET['UserID']) && is_numeric($_GET['UserID']) ? intval($_GET['UserID']) : 0;
        $stmt= $con->prepare("SELECT * FROM `users` WHERE UserID = ? LIMIT 1");
        $stmt->execute(array($userid));
        $row=$stmt->fetch();
        $count=$stmt->rowCount();//PDO Distinct *
        if($count>0)
        {
        ?>
            <h1 class="text-center">Edit Members</h1>
            <div class="container">
                <form action="members.php?do=Update" method="POST" enctype="multipart/form-data">
                    <input type="hidden" name="userid" value="<?php echo $userid; ?>">
                    <div class="row mb-3">
                        <label class="col-sm-2 control-label">Username</label>
                        <div class="col-sm-10 col-md-6 for-pos">
                            <input type="text" name="username" value="<?php echo $row['UserName']; ?>" class="username form-control" autocomplete="off" required="required">
                            <div class="alert alert-danger custom-alert">
                                Username can not be <strong>less than 4 chars</strong>
                            </div>
                        </div>
                    </div>

                    <div class="row mb-3">
                        <label class="col-sm-2 control-label">Password</label>
                        <div class="col-sm-10 col-md-6 for-pos">
                        <input type="hidden" name="oldpassword" value="<?php echo $row['Password']; ?>" >
                            <input type="password" name="newpassword" class=" form-control" autocomplete="new-password" placeholder="Leave Blank If You Don't Want Change">
                        </div>
                    </div>

                    <div class="row mb-3">
                        <label class="col-sm-2 control-label">Email</label>
                        <div class="col-sm-10 col-md-6 for-pos">
                            <input type="email" name="email" value="<?php echo $row['Email']; ?>" class="email form-control"autocomplete="off" required="required">
                            <div class="alert alert-danger custom-alert">
                                Email can not be <strong>Empty</strong>
                            </div>
                        </div>
                    </div>

                    <div class="row mb-3">
                        <label class="col-sm-2 control-label">Full Name</label>
                        <div class="col-sm-10 col-md-6 for-pos">
                            <input type="text" name="full" value="<?php echo $row['FullName']; ?>" class="fullname form-control"autocomplete="off" required="required">
                            <div class="alert alert-danger custom-alert">
                                Username can not be <strong>less than 10 chars</strong>
                            </div>
                        </div>
                    </div>

                    <div class="row mb-3">
                        <label class="col-sm-2 control-label">User Img</label>
                        <div class="col-sm-10 col-md-6 for-pos">
                            <input type="file" name="avatar" class="fullname form-control" required="required">
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
   }elseif($do == 'Update'){
       if($_SERVER['REQUEST_METHOD']=='POST')
       {
           $id=$_POST['userid'];
           $user=$_POST['username'];
           $pass=empty($_POST['newpassword'])?$_POST['oldpassword']:sha1($_POST['newpassword']);
           $email=$_POST['email'];
           $name=$_POST['full'];
        
           $avatarName=$_FILES['avatar']['name'];
           $avatarTempName=$_FILES['avatar']['tmp_name'];
           $avatarSize=$_FILES['avatar']['size'];
           $avatarType=$_FILES['avatar']['type'];

           $avatarMineExtension=array("jpeg","jpg","png","gif");
           $avatarExtension=pathinfo($avatarName,PATHINFO_EXTENSION);

           $errors=[];

           if(empty($user))
           {
               $errors[]="username is required";
           }elseif(!is_string($user)){
               $errors[]="username must be string";
           }elseif(strlen($user)<5)
           {
               $errors[]="username must be at less 5 chars";
           }
   
           if(empty($email))
           {
               $errors[]="email is required";
           }
   
           if(empty($name))
           {
               $errors[]="full name required";
           }

           if(!empty($avatarName) && ! in_array(strtolower($avatarExtension),$avatarMineExtension)){
            $errors[]="img ext not allowed ";
        }

        if(empty($avatarName))
        {
            $errors[]="img is required";
        }

        if($avatarSize > 4194304)
        {
            $errors[]="img must be less than 4MB";
        }
   
   

        if(empty($errors))
        {
            $avatar=rand(0,100000) . '_' . $avatarName ;
            move_uploaded_file($avatarTempName,"uploads\images\\".$avatar);

            $stmt=$con->prepare("SELECT * FROM users WHERE UserName = ? AND UserID != ?");
            $stmt->execute(array($user,$id));
            $count =$stmt->rowCount();
            if($count == 1){
                $msg="<div class='alert alert-success text-center w-75 mx-auto my-5'>Sorry update is available</div>";
                redHome($msg,'back',3);

            }else{
                $stmt= $con->prepare("UPDATE `users` SET UserName = ?,Password = ?, Email = ?, FullName = ?, avatar = ? WHERE UserID = ? ");
                $stmt->execute(array($user,$pass,$email,$name,$avatar,$id)); 
                $msg="<div class='alert alert-success text-center w-75 mx-auto my-5'>UPDATED</div>";
                redHome($msg,5);
                //header("location:dashboard.php?Updated");
                //echo $stmt->rowCount() . ' record Updated';

            }
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
    $count=checkItem('UserID','users',$userid);
    if($count > 0)
    {
        $stmt= $con->prepare("DELETE FROM `users` WHERE UserID = :zuserid");//:zuserid=?
        $stmt->bindParam(":zuserid",$userid);//if we write ? we do not need this step
        $stmt->execute();//array($userid) inside execute if we use ?
        $msg="<div class='alert alert-success text-center w-75 mx-auto my-5'>DELETED</div>";
        redHome($msg,'dashboard.php?DELETED',5);
        //header("location:dashboard.php?DELETED");
    }else{ 
        $msgError="<div class='alert alert-danger text-center w-75 mx-auto my-5'>This Id Is Not Exist</div>";
        redHome($msgError,5);
    }

   }elseif($do == 'Activate'){
    $userid=isset($_GET['UserID']) && is_numeric($_GET['UserID']) ? intval($_GET['UserID']) : 0;
    $count=checkItem('UserID','users',$userid);
    if($count > 0)
    {
        $stmt= $con->prepare("UPDATE `users` SET RegStatus = 1 WHERE UserID = ?");
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
