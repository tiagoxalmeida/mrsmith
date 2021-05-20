<?php
//Crounjob executed by the server in intervals of 5 seconds
include 'con_inc.php';
$sql = "DELETE FROM online WHERE o_feedfoward = 0";
if(!mysqli_query($conn,$sql)){
    echo 'Error in feedfoward\n';
    exit;
}
$sql = "UPDATE online SET o_feedfoward = 0 WHERE o_feedfoward = 1;";
if(!mysqli_query($conn,$sql)){
    echo 'Error in feedfoward\n';
    exit;
}
echo 'Updated\n';
?>