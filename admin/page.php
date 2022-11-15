<?php

//$do=''; if(isset($_GET['do'])){$do=$_GET['do'];}else{ $do='manage';}

$do=isset($_GET['do'])?$_GET['do']:'manage';

if($do=='manage'){ echo 'manage';}
elseif($do=='add'){echo 'add';}
elseif($do=='update'){echo 'update';}
elseif($do=='delete'){echo 'delete';}
else{echo 'not found';}



?>