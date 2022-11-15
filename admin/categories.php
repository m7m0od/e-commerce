<?php
ob_start();
session_start();


if(isset($_SESSION['username']))
{
    $pageTitle='Categories';
    include "init.php"; 

    $do=isset($_GET['do'])?$_GET['do']:'manage';

    if($do=='manage'){

        $sort='ASC';
        $sort_array=array('ASC','DESC');

        if(isset($_GET['sort']) && in_array($_GET['sort'],$sort_array)){$sort=$_GET['sort'];}

        $stmt= $con->prepare("SELECT * FROM `categories` ORDER BY Oerdering $sort ");
        $stmt->execute();
        $rows=$stmt->fetchAll();
        ?>
        <h1 class="text-center">Manage Categories</h1>
        <div class='container categories'>
            <div class='panel panel-default'>
                <div class='panel-heading'>
                     <i class='fa fa-edit'></i> Manage Categories
                     <div class='ordering pull-right'>
                         <i class='fa fa-sort'></i> Oerdering: [
                         <a href='categories.php?do=manage&sort=ASC' class='<?php if($sort == 'ASC'){echo 'active'; } ?>' >ASC</a> |
                         <a href='categories.php?do=manage&sort=DESC' class='<?php if($sort == 'DESC'){echo 'active'; } ?>' >DESC</a> ] 
                         <i class='fa fa-eye'></i> View: [
                         <span class='active' data-view='full'>Full</span> |
                         <span data-view='classic'>Classic</span> ]
                     </div>
                     <div class='clr'></div>
                </div>
                <div class='panel-body'>
                    <?php
                        foreach($rows as $row)
                        {
                            echo "<div class='cat'>";
                                echo "<div class='hidden-buttons'>";
                                    echo "<a href='categories.php?do=edit&UserID=" . $row['ID'] . "' class='btn btn-xs btn-primary'><i class='fa fa-edit'></i> Edit</a>";
                                    echo "<a href='categories.php?do=delete&UserID=" . $row['ID'] . "' class='btn btn-xs btn-danger confirm'><i class='fa fa-close'></i> Delete</a>";
                                echo "</div>";
                                echo "<h3>" . $row['Name'] . "</h3>";
                                echo "<div class='full-view'>";
                                    echo "<p>"; if($row['Description']==''){echo 'empty description';}else{echo $row['Description'];} echo "</p>";
                                    if($row['Visibility'] == 1){echo "<span class='visibility'><i class='fa fa-eye'></i> Hidden</span>";}
                                    if($row['Allow_Comment'] == 1){echo "<span class='commenting'><i class='fa fa-close'></i> commenting is disable</span>";}
                                    if($row['Allow_Ads'] == 1){echo "<span class='advertises'><i class='fa fa-eye'></i> advertises is disable</span>";}
                                echo "</div>";
                            echo "</div>";
                            echo "<hr>";
                        }
                    ?> 
                </div>
            </div>
            <a href="categories.php?do=add" class="btn btn-info my-5">Add categories</a>
        </div>

        <h1 class="text-center">Manage Categories</h1>
        <div class="container">
            <div class="table-responsive">
                <table class="main-table text-center table table-bordered">
                    <thead>
                        <tr>
                            <th>#ID</th>
                            <th>Name</th>
                            <th>Description</th>
                            <th>Oerdering</th>
                            <th>Visibility</th>
                            <th>Allow_Comment</th>
                            <th>Allow_Ads</th>
                            <th>Control</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php
                        foreach($rows as $row){
                            echo "<tr>";
                            echo "<td>". $row['ID'] . "</td>";
                            echo "<td>". $row['Name'] . "</td>";
                            echo "<td>". $row['Description'] . "</td>";
                            echo "<td>". $row['Oerdering'] . "</td>";
                            echo "<td>"; if($row['Visibility'] == 1){echo "<span>Hidden</span>";}else{echo '';} echo "</td>";
                            echo "<td>"; if($row['Allow_Comment'] == 1){echo "<span>commenting is disable</span>";}else{echo '';} echo "</td>";
                            echo "<td>"; if($row['Allow_Ads'] == 1){echo "<span>advertises is disable</span>";}else{echo '';} echo "</td>";
                            echo "<td> <a href='categories.php?do=edit&UserID=" . $row['ID'] . "' class='btn btn-primary'><i class='fa fa-edit'></i> Edit</a> 
                                        <a href='categories.php?do=delete&UserID=" . $row['ID'] . "' class='btn btn-danger confirm'><i class='fa-solid fa-xmark'></i> Delete</a></td>";
                            echo "</tr>";
                        }
                         ?>
                    </tbody>
                </table>
            </div>
            <a href="categories.php?do=add" class="btn btn-info my-5">Add categories</a>
        </div>
        
   <?php }elseif($do=='add'){ ?>
        <h1 class="text-center">Add Category</h1>
            <div class="container">
                <form action="categories.php?do=insert" method="POST">
                    <div class="row mb-3">
                        <label class="col-sm-2 control-label">Name</label>
                        <div class="col-sm-10 col-md-6 for-pos">
                            <input type="text" name="name" class="username form-control" autocomplete="off" required="required" placeholder="name of category">
                            <div class="alert alert-danger custom-alert">
                                name can not be <strong>less than 4 chars</strong>
                            </div>
                        </div>
                    </div>

                    <div class="row mb-3">
                        <label class="col-sm-2 control-label">Description</label>
                        <div class="col-sm-10 col-md-6 for-pos">
                            <input type="text" name="description" class="form-control" placeholder="Write the description of category">
                        </div>
                    </div>

                    <div class="row mb-3">
                        <label class="col-sm-2 control-label">Ordering</label>
                        <div class="col-sm-10 col-md-6 for-pos">
                            <input type="text" name="ordering" class="form-control" placeholder="To Arrange the categories">
                        </div>
                    </div>

                    <div class="row mb-3">
                        <label class="col-sm-2 control-label">Visibile</label>
                        <div class="col-sm-10 col-md-6 for-pos">
                            <div>
                                <input id='yesVisibile' type='radio' name='visibile' value='0' checked />
                                <label for='yesVisibile'>Yes</label>
                            </div>
                            <div>
                                <input id='noVisibile' type='radio' name='visibile' value='1'/>
                                <label for='noVisibile'>No</label>
                            </div>
                        </div>
                    </div>

                    <div class="row mb-3">
                        <label class="col-sm-2 control-label">Allow Commenting</label>
                        <div class="col-sm-10 col-md-6 for-pos">
                            <div>
                                <input id='yesComment' type='radio' name='commenting' value='0' checked />
                                <label for='yesComment'>Yes</label>
                            </div>
                            <div>
                                <input id='noComment' type='radio' name='commenting' value='1'/>
                                <label for='noComment'>No</label>
                            </div>
                        </div>
                    </div>

                    <div class="row mb-3">
                        <label class="col-sm-2 control-label">Allow Ads</label>
                        <div class="col-sm-10 col-md-6 for-pos">
                            <div>
                                <input id='yesAds' type='radio' name='ads' value='0' checked />
                                <label for='yesAds'>Yes</label>
                            </div>
                            <div>
                                <input id='noAds' type='radio' name='ads' value='1'/>
                                <label for='noAds'>No</label>
                            </div>
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
    } elseif($do=='insert'){
        if($_SERVER['REQUEST_METHOD']=='POST')
       {
           
           $name=$_POST['name'];
           $desc=$_POST['description'];
           $visibile=$_POST['visibile'];
           $order=$_POST['ordering'];
           $comment=$_POST['commenting'];
           $ads=$_POST['ads'];

        $errors=[];

        if(empty($name))
        {
            $errors[]="name is required";
        }elseif(!is_string($user)){
            $errors[]="name must be string";
        }elseif(strlen($user)<4)
        {
            $errors[]="name must be at less 4 chars";
        }


        if(empty($errors))
        {
            $count=checkItem('Name','categories',$name);
            if($count > 0){
               $msgError="<div class='alert alert-danger text-center w-75 mx-auto my-5'>this record is already exist</div>";
               redHome($msgError,5);
            }else{
                $stmt= $con->prepare("INSERT INTO `categories`(Name,Description,Oerdering,Visibility,Allow_Comment,Allow_Ads) VALUES (:zname, :zdesc, :zorder, :zvisibile,:zcomment,:zads) ");
                $stmt->execute(array('zname' => $name, 'zdesc' => $desc, 'zorder' => $order, 'zvisibile' =>$visibile,'zcomment' => $comment, 'zads' => $ads )); 
                $msg="<div class='alert alert-success text-center w-75 mx-auto my-5'>" . $stmt->rowCount() . "record inserted</div>";
                redHome($msg,'back');
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

    } elseif($do=='edit'){
        $userid=isset($_GET['UserID']) && is_numeric($_GET['UserID']) ? intval($_GET['UserID']) : 0;
        $stmt= $con->prepare("SELECT * FROM `categories` WHERE ID = ? LIMIT 1");
        $stmt->execute(array($userid));
        $row=$stmt->fetch();
        $count=$stmt->rowCount();//PDO Distinct *
        if($count>0)
        {
        ?>
            <h1 class="text-center">Edit category</h1>
            <div class="container">
                <form action="categories.php?do=update" method="POST">
                <input type="hidden" name="userid" value="<?php echo $userid; ?>">
                    <input type="hidden" name="userid" value="<?php echo $userid; ?>">
                    <div class="row mb-3">
                        <label class="col-sm-2 control-label">Name</label>
                        <div class="col-sm-10 col-md-6 for-pos">
                            <input type="text" name="name" value="<?php echo $row['Name']; ?>" class="username form-control" autocomplete="off" required="required">
                            <div class="alert alert-danger custom-alert">
                                name can not be <strong>less than 4 chars</strong>
                            </div>
                        </div>
                    </div>

                    <div class="row mb-3">
                        <label class="col-sm-2 control-label">Description</label>
                        <div class="col-sm-10 col-md-6 for-pos">
                            <input type="text" name="desc" class=" form-control" autocomplete="off" value="<?php echo $row['Description']; ?>" placeholder="descripe your category">
                        </div>
                    </div>

                    <div class="row mb-3">
                        <label class="col-sm-2 control-label">Ordering</label>
                        <div class="col-sm-10 col-md-6 for-pos">
                            <input type="text" name="ordering" class="form-control" value="<?php echo $row['Oerdering']; ?>" placeholder="To Arrange the categories">
                        </div>
                    </div>

                    <div class="row mb-3">
                        <label class="col-sm-2 control-label">Visibile</label>
                        <div class="col-sm-10 col-md-6 for-pos">
                            <div>
                                <input id='yesVisibile' type='radio' name='visibile' value='0' <?php if($row['Visibility'] == 0){echo 'checked';} ?>/>
                                <label for='yesVisibile'>Yes</label>
                            </div>
                            <div>
                                <input id='noVisibile' type='radio' name='visibile' value='1' <?php if($row['Visibility'] == 1){echo 'checked';} ?>/>
                                <label for='noVisibile'>No</label>
                            </div>
                        </div>
                    </div>

                    <div class="row mb-3">
                        <label class="col-sm-2 control-label">Allow Commenting</label>
                        <div class="col-sm-10 col-md-6 for-pos">
                            <div>
                                <input id='yesComment' type='radio' name='commenting' value='0' <?php if($row['Allow_Comment'] == 0){echo 'checked';} ?> />
                                <label for='yesComment'>Yes</label>
                            </div>
                            <div>
                                <input id='noComment' type='radio' name='commenting' value='1' <?php if($row['Allow_Comment'] == 1){echo 'checked';} ?> />
                                <label for='noComment'>No</label>
                            </div>
                        </div>
                    </div>

                    <div class="row mb-3">
                        <label class="col-sm-2 control-label">Allow Ads</label>
                        <div class="col-sm-10 col-md-6 for-pos">
                            <div>
                                <input id='yesAds' type='radio' name='ads' value='0' <?php if($row['Allow_Ads'] == 0){echo 'checked';} ?> />
                                <label for='yesAds'>Yes</label>
                            </div>
                            <div>
                                <input id='noAds' type='radio' name='ads' value='1' <?php if($row['Allow_Ads'] == 1){echo 'checked';} ?>/>
                                <label for='noAds'>No</label>
                            </div>
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

    } elseif($do=='update'){
        if($_SERVER['REQUEST_METHOD']=='POST')
       {
            $id=$_POST['userid'];
            $name=$_POST['name'];
            $desc=$_POST['desc'];
            $visibile=$_POST['visibile'];
            $order=$_POST['ordering'];
            $comment=$_POST['commenting'];
            $ads=$_POST['ads'];

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
   
   

        if(empty($errors))
        {
            $stmt= $con->prepare("UPDATE `categories` SET Name = ?,Description = ?, Oerdering = ?, Visibility = ?, Allow_Comment = ?, Allow_Ads = ? WHERE ID = ? ");
            $stmt->execute(array($name,$desc,$order,$visibile,$comment,$ads,$id)); 
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
        $count=checkItem('ID','categories',$userid);
        if($count > 0)
        {
            $stmt= $con->prepare("DELETE FROM `categories` WHERE ID = :zuserid");//:zuserid=?
            $stmt->bindParam(":zuserid",$userid);//if we write ? we do not need this step
            $stmt->execute();//array($userid) inside execute if we use ?
            $msg="<div class='alert alert-success text-center w-75 mx-auto my-5'>DELETED</div>";
            redHome($msg,5);
            //header("location:dashboard.php?DELETED");
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