<?php
ob_start();
session_start();
if(isset($_SESSION['user']))
{
    header("location:index.php");
    //unset($_SESSION['username']);
}
$pageTitle='login';
include "init.php";

if($_SERVER['REQUEST_METHOD']=='POST')
    {
        if(isset($_POST['login'])){
        $user=$_POST['username'];
        $pass=$_POST['password'];
        $hashedPass=sha1($pass);
        
        $stmt= $con->prepare("SELECT UserID, UserName, Password, GroupID FROM `users` WHERE UserName = ? AND Password = ? ");
        //$stmt="SE....."; $runStmt=mysqli_query($conn,$stmt);$result=mysqli_fetch_all($runStmt,MYSQLI_ASSOC);
        $stmt->execute(array($user,$hashedPass));
        $get=$stmt->fetch();
        $count=$stmt->rowCount();//PDO Distinct *
        if($count>0)
        {
            $_SESSION['user']=$user;
            $_SESSION['uid']=$get['UserID'];
            if($get['GroupID']==1){
                header("location:admin/index.php");
            }else{
                header("location:index.php");
            }
            
            exit();
        }
    }else{
        $formError=[];
        $username=$_POST['username'];
        $password=$_POST['password'];
        $password2=$_POST['confirmPassword'];
        $email=$_POST['email'];

        if(isset($username)){
            $filterUser=filter_var($username,FILTER_SANITIZE_FULL_SPECIAL_CHARS);
            if(strlen($filterUser)<4){
                $formError[]='must be more than 4 chars';
            }
        
        }

        if(isset($password) && isset($password2)){
            if(empty($password)){
                $formError[]="password is requierd";
            }
           
            if(sha1($password) !== sha1($password2)){
                $formError[]="Must be same";
            }
        }

        if(isset($email)){
            $filterEmail=filter_var($email,FILTER_SANITIZE_EMAIL);
            if(filter_var($filterEmail,FILTER_VALIDATE_EMAIL) != true){
                $formError[]='must be more than 4 chars';
            }
        
        }
        if(empty($formError))
        {
            $count=checkItem('UserName','users',$username);
            if($count > 0){
               $formError[]="this record is already exist";
            }else{
                $stmt= $con->prepare("INSERT INTO `users`(UserName,Password,Email,RegStatus,Date) VALUES (:zuser, :zpass, :zemail,0,now()) ");
                $stmt->execute(array('zuser' => $username, 'zpass' => sha1($password), 'zemail' => $email )); 
                
                $msg="Congrats you are now registred user";
            }
        }else{
            foreach($formError as $error)
            {
                echo "<div class='alert alert-danger'>" . $error. "</div>" ;
            }
        }
    }
    }

?>

<div class='container loginPage'>
    <form class='login loginO' action="<?php echo $_SERVER['PHP_SELF']; ?>" method="POST">
        <h4 class="text-center"><span class='spanSelected' data-class='loginO' >Login</span> | <span  data-class='signupO'>SignUp</span></h4>
        <input class='form-control' type='text' name='username' autocomplete='off' placeholder="Username">
        <input class='form-control' type='password' name='password' autocomplete='new-password' placeholder="Password">
        <div class="d-grid gap-2">
          <input class="btn btn-primary btn-block" name="login" type="submit" value="login">
        </div>  
    </form>
    <form class='login signupO' action="<?php echo $_SERVER['PHP_SELF']; ?>" method="POST" >
        <h4 class="text-center"><span data-class='loginO'  >Login</span> | <span class='spanSelected' data-class='signupO'>SignUp</span></h4>
        <input class='form-control' type='text' name='username' autocomplete='off' placeholder="Username">
        <input class='form-control' type='password' name='password' autocomplete='new-password' placeholder="Password">
        <input class='form-control' type='password' name='confirmPassword' autocomplete='new-password' placeholder="Confirm Your Password">
        <input class='form-control' type='email' name='email' autocomplete='off' placeholder="Email">
        <div class="d-grid gap-2">
          <input class="btn btn-success btn-block" name="signup" type="submit" value="signUp">
        </div>  
    </form>
    <div class='text-center the-errors'>
        <?php
        if(! empty($formError)){
            foreach($formError as $error){
                echo "<div class='msg error'>" . $error . "</div>";
            }
        }
        if(isset($msg)){
            echo "<div class='msg success'>" . $msg . "</div>";
        }
        
        
        
        ?>

    </div>
</div>




<?php 
ob_end_flush();
include $tpl."footer.php"; 
?>