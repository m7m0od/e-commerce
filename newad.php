<?php 
ob_start();
session_start();
$pageTitle='New Item';
include "init.php";
if(isset($_SESSION['user'])){   
    
    if($_SERVER['REQUEST_METHOD'] === 'POST'){
        $formErrors=[];

        $name=filter_var($_POST['name'],FILTER_SANITIZE_FULL_SPECIAL_CHARS);
        $desc=filter_var($_POST['description'],FILTER_SANITIZE_FULL_SPECIAL_CHARS);
        $price=filter_var($_POST['price'],FILTER_SANITIZE_NUMBER_INT);
        $countryMade=filter_var($_POST['countryMade'],FILTER_SANITIZE_FULL_SPECIAL_CHARS);
        $status=filter_var($_POST['status'],FILTER_SANITIZE_NUMBER_INT);
        $cat=filter_var($_POST['category'],FILTER_SANITIZE_NUMBER_INT);

        if(strlen($name)<4){
            $formErrors[]='must be more than 4';
        }

        if(strlen($desc)<10){
            $formErrors[]='must be more than 10';
        }

        if(strlen($name)<2){
            $formErrors[]='must be more than 2';
        }

        
        if(empty($price)){
            $formErrors[]='Requierd';
        }

        if(empty($price)){
            $formErrors[]='Requierd';
        }

        if(empty($price)){
            $formErrors[]='Requierd';
        }

        if(empty($formErrors))
        {
            $stmt= $con->prepare("INSERT INTO `items`(Name,Description,Price,Country_Made,Status,Cat_ID,Member_ID,Add_Date) VALUES (:zname, :zdesc, :zprice, :zcountryMade,:zstatus,:zcat,:zmember,now())");
            $stmt->execute(array('zname' => $name, 'zdesc' => $desc, 'zprice' => $price, 'zcountryMade' => $countryMade, 'zstatus' => $status, 'zcat' => $cat, 'zmember' => $_SESSION['uid'] )); 
            echo "<div class='alert alert-success text-center w-75 mx-auto my-5'>record inserted</div>";
        }
    }
?>

<h1 class='text-center'>New Item</h1>
<div class='create--ad block pb-3'>
    <div class='container'>
        <div class='panel panel-primary bg-info'>
            <div class='panel-heading p-2'>New Item</div>
            <div class='panel-body'>
                <div class='row'>
                    <div class='col-md-8'>
                        <form action="<?php echo $_SERVER['PHP_SELF']?>" method="POST">
                            <div class="row mb-3">
                                <label class="col-sm-2 control-label">Name</label>
                                <div class="col-sm-10 col-md-8 for-pos">
                                    <input pattern=".{4,}" title="This field Require at least 4 charaters" type="text" name="name" class="username form-control live-name" autocomplete="off" required="required" placeholder="name of item">
                                    <div class="alert alert-danger custom-alert">
                                        name can not be <strong>less than 4 chars</strong>
                                    </div>
                                </div>
                            </div>

                            <div class="row mb-3">
                                <label class="col-sm-2 control-label">Description</label>
                                <div class="col-sm-10 col-md-8 for-pos">
                                    <input type="text" name="description" class="username form-control live-desc" autocomplete="off" required="required" placeholder="Description of item">
                                    <div class="alert alert-danger custom-alert">
                                        Description can not be <strong>less than 4 chars</strong>
                                    </div>
                                </div>
                            </div>

                            <div class="row mb-3">
                                <label class="col-sm-2 control-label">Price</label>
                                <div class="col-sm-10 col-md-8 for-pos">
                                    <input type="text" name="price" class="email form-control live-price" autocomplete="off" required="required" placeholder="price of item">
                                    <div class="alert alert-danger custom-alert">
                                        price can not be <strong>empty</strong>
                                    </div>
                                </div>
                            </div>

                            <div class="row mb-3">
                                <label class="col-sm-2 control-label">Country Of Made</label>
                                <div class="col-sm-10 col-md-8 for-pos">
                                    <input type="text" name="countryMade" class="email form-control" autocomplete="off" required="required" placeholder="country of made">
                                    <div class="alert alert-danger custom-alert">
                                        made of country can not be <strong>empty</strong>
                                    </div>
                                </div>
                            </div>

                            <div class="row mb-3">
                                <label class="col-sm-2 control-label">Status</label>
                                <div class="col-sm-10 col-md-8 for-pos">
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
                                <label class="col-sm-2 control-label">category</label>
                                <div class="col-sm-10 col-md-8 for-pos">
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
                    <div class='col-md-4'>
                        <div class='thumbnail item-box live-preview'>
                           <span class='price'>0</span>
                           <?php
                             $getUser = $con->prepare("SELECT * FROM users WHERE UserName = ?");
                             $getUser->execute(array($_SESSION['user']));
                             $info=$getUser->fetch();
                             if(empty($info['avatar'])){
                                 echo "<img src='admin/uploads/images/mmm.jpg'  class='img-fluid'>";
                             }else{
                                 echo "<img src='admin/uploads/images/".$info['avatar']."'  class='img-fluid'>";
                             }
                           
                           ?>
                           <div class='caption'>
                                <h3>title</h3>
                                <p>Description</p>
                           </div>
                        </div>
                    </div>
                </div>
                <?php
                    if(! empty($formErrors)){
                        foreach($formErrors as $error){
                            echo "<div class='alert alert-danger'>". $error . "</div>";

                        }
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
ob_end_flush();
?>