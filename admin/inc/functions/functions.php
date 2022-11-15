<?php

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