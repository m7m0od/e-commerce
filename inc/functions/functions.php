<?php

function getAll($tableName,$where=NULL){
    global $con;

    $sql = $where == NULL ? '' : $where;

    $stmt=$con->prepare("SELECT * FROM $tableName $sql");

    $stmt->execute();

    $all=$stmt->fetchAll();

    return $all;
}

function getCategories(){
    global $con;

    $stmt=$con->prepare("SELECT * FROM categories ORDER BY ID ASC");

    $stmt->execute();

    $Cats=$stmt->fetchAll();

    return $Cats;
}

function getItems($where,$value,$approve = NULL){
    global $con;

    if($approve == NULL){
        $sql='AND Approve = 1'; 
    }else{
        $sql = NULL ;
    }

    $stmt=$con->prepare("SELECT * FROM items WHERE $where = ? $sql ORDER BY ItemID DESC");

    $stmt->execute(array($value));

    $items=$stmt->fetchAll();

    return $items;
}


function checkUserActive($user){
    global $con;
    $stmt= $con->prepare("SELECT UserName, RegStatus FROM `users` WHERE UserName = ? AND RegStatus = 0 ");
    $stmt->execute(array($user));
    $count=$stmt->rowCount();
    return $count;
}



function getTitle()
{
    global $pageTitle;
    if(isset($pageTitle)){echo $pageTitle;}
    else{echo 'default';}
}

function redHome($msgError,$url=null,$sec=5)
{
    if($url===null)
    {
        $url='index.php';
        $link='homepage';
    }/*elseif($url){
        $url=$url;
        //$link=explode('.',$url,1);
        $link='hello';
    }*/else{
        if(isset($_SERVER['HTTP_REFERER']) && $_SERVER['HTTP_REFERER'] !=='' )
        {
            $url=$_SERVER['HTTP_REFERER'];//request you come from == back
            $link='prev page';
        }else{
            $url='index.php';
            $link='homepage';
        }
    }
    echo $msgError;
    echo "<div class='alert alert-info text-center w-75 mx-auto my-5'>you will redirect to $link after $sec Seconds.</div>";
    header("refresh:$sec;url=$url");
    exit();
}

function checkItem($select,$from,$value)
{
    global $con;
    $stmtTwo=$con->prepare("SELECT $select FROM $from WHERE $select = ?");
    $stmtTwo->execute(array($value));
    $count=$stmtTwo->rowCount();
    return $count;
}


function countItems($item,$table){
    global $con;
    $stmt=$con->prepare("SELECT COUNT($item) FROM $table");
    $stmt->execute();
    return $stmt->fetchColumn();
}

function getLatest($select,$table,$order,$limit=5){
    global $con;

    $stmt=$con->prepare("SELECT $select FROM $table ORDER BY $order DESC LIMIT $limit ");

    $stmt->execute();

    $row=$stmt->fetchAll();

    return $row;
}




?>