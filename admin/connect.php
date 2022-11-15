<?php
 
// PDO

    $dsn='mysql:host=localhost;dbname=shop';
    $user='root';
    $pass='';
    $option=[PDO::MYSQL_ATTR_INIT_COMMAND=>'SET NAMES utf8',];

    try{
        $con=new PDO($dsn,$user,$pass,$option);
        $con->setAttribute(PDO::ATTR_ERRMODE,PDO::ERRMODE_EXCEPTION);
    }
    catch(PDOException $e)
    {
        echo "error" . $e->getMessage();
    }


/*
// mysqli

    $conn=mysqli_connect("localhost","root","","shop");
    if(!$conn)
    {
        die("connect failed" . mysqli_connect_error());
    }echo "connected";

    $query="";
    $runQuery=mysqli_query($conn,$query);
    $result=mysqli_fetch_all($runQuery,MYSQLI_ASSOC);
*/


?>