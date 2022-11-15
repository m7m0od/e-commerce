<?php 
ob_start();
session_start();
$pageTitle='HomePage';
include "init.php";
?>
<div class='container pt-5'>
    <div class='row'>
    <?php
    $stmt= $con->prepare("SELECT items.*,users.avatar
    FROM `items` 
    INNER JOIN users ON users.UserID=items.Member_ID
    ");
    $stmt->execute();
    $allItems=$stmt->fetchAll();  
        foreach($allItems as $item){
            echo "<div class='col-md-4 col-sm-6'>";
                echo "<div class='thumbnail item-box'>";
                if($item['Approve']==0){echo "<span class='approve-status'>Not Approve</span>";}
                echo "<span class='price'>" . $item['Price'] . "</span>";
                if(empty($item['avatar'])){
                    echo "<img src='admin/uploads/images/mmm.jpg' class='img-fluid'>";
                }else{
                    echo "<img src='admin/uploads/images/".$item['avatar']."' class='img-fluid'>";
                }
                echo "<div class=''caption";
                echo '<h3><a class="dropdown-item" href="item.php?Itemid='. $item['ItemID'] .'">' . $item['Name'] . '<a></h3>';
                echo "<p>" . $item['Description'] . "</p>";
                echo "<div class='date'>" . $item['Add_Date'] . "</div>";
                echo "</div>";
                echo "</div>";
            echo "</div>";
        }  
    ?>
    </div>
</div>

<?php
include $tpl."footer.php";
ob_end_flush();
?>