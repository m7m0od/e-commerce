<?php
ob_start();
session_start();


if(isset($_SESSION['username']))
{
    $pageTitle='Items';
    include "init.php"; 

    $do=isset($_GET['do'])?$_GET['do']:'manage';

    if($do=='manage'){

        $stmt= $con->prepare("SELECT 
                                    items.*,
                                    categories.Name AS cateName,
                                    users.UserName
                            FROM items
                            INNER JOIN categories ON categories.ID = items.Cat_ID
                            INNER JOIN users ON users.UserID = items.Member_ID 
                            ");
        $stmt->execute();
        $rows=$stmt->fetchAll();
        ?>
        <h1 class="text-center">Manage Items</h1>
        <div class="container">
            <div class="table-responsive">
                <table class="main-table text-center table table-bordered">
                    <thead>
                        <tr>
                            <th>#ID</th>
                            <th>Name</th>
                            <th>Description</th>
                            <th>Price</th>
                            <th>Country Of Made</th>
                            <th>Add_Date</th>
                            <th>Category</th>
                            <th>UserName</th>
                            <th>Control</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php
                        foreach($rows as $row){
                            echo "<tr>";
                            echo "<td>". $row['ItemID'] . "</td>";
                            echo "<td>". $row['Name'] . "</td>";
                            echo "<td>". $row['Description'] . "</td>";
                            echo "<td>". $row['Price'] . "</td>";
                            echo "<td>". $row['Country_Made'] . "</td>";
                            echo "<td>". $row['Add_Date'] . "</td>";
                            echo "<td>". $row['cateName'] . "</td>";
                            echo "<td>". $row['UserName'] . "</td>";
                            echo "<td> <a href='item.php?do=edit&UserID=" . $row['ItemID'] . "' class='btn btn-success'><i class='fa fa-edit'></i> Edit</a> 
                                        <a href='item.php?do=delete&UserID=" . $row['ItemID'] . "' class='btn btn-danger confirm'><i class='fa fa-xmark'></i> Delete</a>";
                                        if($row['Approve']==0){
                                            echo "<a href='item.php?do=approve&UserID=" . $row['ItemID'] . "' class='btn btn-info activate'><i class='fa fa-check'></i> Approve</a>";

                                        }
                                        echo "</td>";
                            echo "</tr>";
                        }
                         ?>
                    </tbody>
                </table>
            </div>
            <a href="item.php?do=add" class="btn btn-info my-5">Add Item</a>
        </div>
   <?php

    } elseif($do=='add'){ ?>
        <h1 class="text-center">Add Item</h1>
            <div class="container">
                <form action="item.php?do=insert" method="POST">

                    <div class="row mb-3">
                        <label class="col-sm-2 control-label">Name</label>
                        <div class="col-sm-10 col-md-6 for-pos">
                            <input type="text" name="name" class="username form-control" autocomplete="off" required="required" placeholder="name of item">
                            <div class="alert alert-danger custom-alert">
                                name can not be <strong>less than 4 chars</strong>
                            </div>
                        </div>
                    </div>

                   <div class="row mb-3">
                        <label class="col-sm-2 control-label">Description</label>
                        <div class="col-sm-10 col-md-6 for-pos">
                            <input type="text" name="description" class="username form-control" autocomplete="off" required="required" placeholder="Description of item">
                            <div class="alert alert-danger custom-alert">
                                 Description can not be <strong>less than 4 chars</strong>
                            </div>
                        </div>
                    </div>

                    <div class="row mb-3">
                        <label class="col-sm-2 control-label">Price</label>
                        <div class="col-sm-10 col-md-6 for-pos">
                            <input type="text" name="price" class="email form-control" autocomplete="off" required="required" placeholder="price of item">
                            <div class="alert alert-danger custom-alert">
                                 price can not be <strong>empty</strong>
                            </div>
                        </div>
                    </div>

                    <div class="row mb-3">
                        <label class="col-sm-2 control-label">Country Of Made</label>
                        <div class="col-sm-10 col-md-6 for-pos">
                            <input type="text" name="countryMade" class="email form-control" autocomplete="off" required="required" placeholder="country of made">
                            <div class="alert alert-danger custom-alert">
                                 made of country can not be <strong>empty</strong>
                            </div>
                        </div>
                    </div>

                    <div class="row mb-3">
                        <label class="col-sm-2 control-label">Status</label>
                        <div class="col-sm-10 col-md-6 for-pos">
                            <select name='status'>
                                <option value='0'>...</option>
                                <option value='1'>New</option>
                                <option value='2'>Like New</option>
                                <option value='3'>Used</option>
                                <option value='4'>Old</option>
                            </select>
                            
                        </div>
                    </div>

                    <div class="row mb-3">
                        <label class="col-sm-2 control-label">Member</label>
                        <div class="col-sm-10 col-md-6 for-pos">
                            <select name='member'>
                                <option value='0'>...</option>
                                <?php
                                        $stmt=$con->prepare("SELECT * FROM users");
                                        $stmt->execute();
                                        $users=$stmt->fetchAll();
                                        foreach($users as $user)
                                        {
                                            echo "<option value='" . $user['UserID'] ."'>" . $user['UserName'] . "</option>";
                                        }
                                ?>
                            </select>
                        </div>
                    </div>

                    <div class="row mb-3">
                        <label class="col-sm-2 control-label">category</label>
                        <div class="col-sm-10 col-md-6 for-pos">
                            <select name='category'>
                                <option value='0'>...</option>
                                <?php
                                        $stmt=$con->prepare("SELECT * FROM categories");
                                        $stmt->execute();
                                        $cats=$stmt->fetchAll();
                                        foreach($cats as $cat)
                                        {
                                            echo "<option value='" . $cat['ID'] ."'>" . $cat['Name'] . "</option>";
                                        }
                                ?>
                            </select>
                        </div>
                    </div>


                    <div class="form-group row">
                    <label class="col-sm-2 control-label"></label><!-- just for design -->
                        <div class=" col-sm-10">
                            <input type="submit" value="ADD ITEM" class="btn btn-info btn-sm">
                        </div>
                    </div>
                </form>
            </div>

    <?php

    } elseif($do=='insert'){
        if($_SERVER['REQUEST_METHOD']=='POST')
       {
           
           $name=$_POST['name'];
           $desc=$_POST['description'];
           $price=$_POST['price'];
           $countryMade=$_POST['countryMade'];
           $status=$_POST['status'];
           $member=$_POST['member'];
           $cat=$_POST['category'];

        $errors=[];

        if(empty($name))
        {
            $errors[]="name is required";
        }elseif(!is_string($name)){
            $errors[]="name must be string";
        }elseif(strlen($name)<5)
        {
            $errors[]="name must be at less 5 chars";
        }

        if(empty($desc))
        {
            $errors[]="desc is required";
        }

        if(empty($price))
        {
            $errors[]="price is required";
        }

        if(empty($countryMade))
        {
            $errors[]="countryMade is required";
        }

        if($status == 0)
        {
            $errors[]="status is required";
        }

        if($member == 0)
        {
            $errors[]="member is required";
        }

        if($cat == 0)
        {
            $errors[]="member is required";
        }


        if(empty($errors))
        {
            $stmt= $con->prepare("INSERT INTO `items`(Name,Description,Price,Country_Made,Status,Member_ID,Cat_ID,Add_Date) VALUES (:zname, :zdesc, :zprice, :zcountryMade,:zstatus,:zmember,:zcat,now())");
            $stmt->execute(array('zname' => $name, 'zdesc' => $desc, 'zprice' => $price, 'zcountryMade' =>$countryMade, 'zstatus' =>$status, 'zmember' =>$member, 'zcat' =>$cat )); 
            $msg="<div class='alert alert-success text-center w-75 mx-auto my-5'>" . $stmt->rowCount() . "record inserted</div>";
            redHome($msg,'back');
            //header("location:dashboard.php?Inserted");
            //echo $stmt->rowCount() . ' record inserted';
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

    } elseif($do=='edit'){
        $userid=isset($_GET['UserID']) && is_numeric($_GET['UserID']) ? intval($_GET['UserID']) : 0;
        $stmt= $con->prepare("SELECT * FROM `items` WHERE ItemID = ? LIMIT 1");
        $stmt->execute(array($userid));
        $row=$stmt->fetch();
        $count=$stmt->rowCount();//PDO Distinct *
        if($count>0)
        {
        ?>
        <h1 class="text-center">Edit Item</h1>
            <div class="container">
                <form action="item.php?do=update" method="POST">
                    <input type="hidden" name="userid" value="<?php echo $userid; ?>">
                    <div class="row mb-3">
                        <label class="col-sm-2 control-label">Name</label>
                        <div class="col-sm-10 col-md-6 for-pos">
                            <input type="text" name="name" class="username form-control" value="<?php echo $row['Name']; ?>" autocomplete="off" required="required" placeholder="name of item">
                            <div class="alert alert-danger custom-alert">
                                name can not be <strong>less than 4 chars</strong>
                            </div>
                        </div>
                    </div>

                   <div class="row mb-3">
                        <label class="col-sm-2 control-label">Description</label>
                        <div class="col-sm-10 col-md-6 for-pos">
                            <input type="text" name="description" class="username form-control" value="<?php echo $row['Description']; ?>" autocomplete="off" required="required" placeholder="Description of item">
                            <div class="alert alert-danger custom-alert">
                                 Description can not be <strong>less than 4 chars</strong>
                            </div>
                        </div>
                    </div>

                    <div class="row mb-3">
                        <label class="col-sm-2 control-label">Price</label>
                        <div class="col-sm-10 col-md-6 for-pos">
                            <input type="text" name="price" class="email form-control" value="<?php echo $row['Price']; ?>" autocomplete="off" required="required" placeholder="price of item">
                            <div class="alert alert-danger custom-alert">
                                 price can not be <strong>empty</strong>
                            </div>
                        </div>
                    </div>

                    <div class="row mb-3">
                        <label class="col-sm-2 control-label">Country Of Made</label>
                        <div class="col-sm-10 col-md-6 for-pos">
                            <input type="text" name="countryMade" class="email form-control" value="<?php echo $row['Country_Made']; ?>" autocomplete="off" required="required" placeholder="country of made">
                            <div class="alert alert-danger custom-alert">
                                 made of country can not be <strong>empty</strong>
                            </div>
                        </div>
                    </div>

                    <div class="row mb-3">
                        <label class="col-sm-2 control-label">Status</label>
                        <div class="col-sm-10 col-md-6 for-pos">
                            <select name='status'>
                                <option value='1'<?php if($row['Status']==1){echo 'selected';}?>>New</option>
                                <option value='2'<?php if($row['Status']==2){echo 'selected';}?>>Like New</option>
                                <option value='3'<?php if($row['Status']==3){echo 'selected';}?>>Used</option>
                                <option value='4'<?php if($row['Status']==4){echo 'selected';}?>>Old</option>
                            </select>
                        </div>
                    </div>

                    <div class="row mb-3">
                        <label class="col-sm-2 control-label">Member</label>
                        <div class="col-sm-10 col-md-6 for-pos">
                            <select name='member'>
                                <?php
                                        $stmt=$con->prepare("SELECT * FROM users");
                                        $stmt->execute();
                                        $users=$stmt->fetchAll();
                                        foreach($users as $user)
                                        {
                                            echo "<option value='" . $user['UserID'] ."'"; if($row['Member_ID']==$user['UserID']){echo 'selected';}echo">" . $user['UserName'] . "</option>";
                                        }
                                ?>
                            </select>
                        </div>
                    </div>

                    <div class="row mb-3">
                        <label class="col-sm-2 control-label">category</label>
                        <div class="col-sm-10 col-md-6 for-pos">
                            <select name='category'>
                                <?php
                                        $stmt=$con->prepare("SELECT * FROM categories");
                                        $stmt->execute();
                                        $cats=$stmt->fetchAll();
                                        foreach($cats as $cat)
                                        {
                                            echo "<option value='" . $cat['ID'] ."'";if($row['Cat_ID']==$cat['ID']){echo 'selected';}echo">" . $cat['Name'] . "</option>";
                                        }
                                ?>
                            </select>
                        </div>
                    </div>

                    <div class="form-group row">
                    <label class="col-sm-2 control-label"></label><!-- just for design -->
                        <div class=" col-sm-10">
                            <input type="submit" value="Save" class="btn btn-info btn-sm">
                        </div>
                    </div>
                </form>
            
              </div>
   <?php }else{
      $msg="<div class='alert alert-success text-center w-75 mx-auto my-5'>this id not found</div>";
      redHome($msg,5);
   }
    } elseif($do=='update'){
        if($_SERVER['REQUEST_METHOD']=='POST')
        {
            $id=$_POST['userid'];
            $name=$_POST['name'];
            $desc=$_POST['description'];
            $price=$_POST['price'];
            $countryMade=$_POST['countryMade'];
            $status=$_POST['status'];
            $member=$_POST['member'];
            $cat=$_POST['category'];
 
         $errors=[];
 
         if(empty($name))
         {
             $errors[]="name is required";
         }elseif(!is_string($name)){
             $errors[]="name must be string";
         }elseif(strlen($name)<5)
         {
             $errors[]="name must be at less 5 chars";
         }
 
         if(empty($desc))
         {
             $errors[]="desc is required";
         }
 
         if(empty($price))
         {
             $errors[]="price is required";
         }
 
         if(empty($countryMade))
         {
             $errors[]="countryMade is required";
         }
 
         if($status == 0)
         {
             $errors[]="status is required";
         }
 
         if($member == 0)
         {
             $errors[]="member is required";
         }
 
         if($cat == 0)
         {
             $errors[]="member is required";
         }
 
 
    
    
 
         if(empty($errors))
         {
             $stmt= $con->prepare("UPDATE `items` SET Name = ?,Description = ?, Price = ?, Country_Made = ?, Status = ?, Cat_ID = ?, Member_ID = ? WHERE ItemID = ? ");
             $stmt->execute(array($name,$desc,$price,$countryMade,$status,$cat,$member,$id)); 
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

    } elseif($do=='delete'){
        $userid=isset($_GET['UserID']) && is_numeric($_GET['UserID']) ? intval($_GET['UserID']) : 0;
    $count=checkItem('ItemID','items',$userid);
    if($count > 0)
    {
        $stmt= $con->prepare("DELETE FROM `items` WHERE ItemID = :zuserid");//:zuserid=?
        $stmt->bindParam(":zuserid",$userid);//if we write ? we do not need this step
        $stmt->execute();//array($userid) inside execute if we use ?
        $msg="<div class='alert alert-success text-center w-75 mx-auto my-5'>DELETED</div>";
        redHome($msg,'back',5);
        //header("location:dashboard.php?DELETED");
    }else{ 
        $msgError="<div class='alert alert-danger text-center w-75 mx-auto my-5'>This Id Is Not Exist</div>";
        redHome($msgError,5);
    }

    } elseif($do=='approve'){
        $userid=isset($_GET['UserID']) && is_numeric($_GET['UserID']) ? intval($_GET['UserID']) : 0;
    $count=checkItem('ItemID','items',$userid);
    if($count > 0)
    {
        $stmt= $con->prepare("UPDATE `items` SET Approve = 1 WHERE ItemID = ?");
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