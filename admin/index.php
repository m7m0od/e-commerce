<?php 
session_start();
if(isset($_SESSION['username']))
{
    header("location:dashboard.php");
    //unset($_SESSION['username']);
}
$noNav='';
$pageTitle='login';
include "init.php";

    if($_SERVER['REQUEST_METHOD']=='POST')
    {
        $username=$_POST['user'];
        $password=$_POST['pass'];
        $hashedPass=sha1($password);
        
        $stmt= $con->prepare("SELECT UserID, UserName, Password FROM `users` WHERE UserName = ? AND Password = ? AND GroupID = 1 LIMIT 1");
        //$stmt="SE....."; $runStmt=mysqli_query($conn,$stmt);$result=mysqli_fetch_all($runStmt,MYSQLI_ASSOC);
        $stmt->execute(array($username,$hashedPass));
        $row=$stmt->fetch();
        $count=$stmt->rowCount();//PDO Distinct *
        if($count>0)
        {
            $_SESSION['username']=$username;
            $_SESSION['UserID']=$row['UserID'];
            header("location:dashboard.php");
            exit();
        }
    }
?>

    <form class="login" action="<?php echo $_SERVER['PHP_SELF']; ?>" method="POST">
        <h4 class="text-center">Admin Login</h4>
        <input class="form-control" type="text" name="user" placeholder="Username" autocomplete="off">
        <input class="form-control" type="password" name="pass" placeholder="Password" autocomplete="new-password">
        <div class="d-grid gap-2">
          <input class="btn btn-primary btn-block" type="submit" value="login">
        </div>  
    </form>
    

<?php include $tpl."footer.php";
?>