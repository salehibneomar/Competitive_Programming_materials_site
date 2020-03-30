<?php

require 'config/config.php';

if(isset($_GET['cid']) && isset($_GET['mid']) && $obj->session_check()){
    $q="DELETE FROM materials WHERE id=? LIMIT 1";
    $s_arr[0]=base64_decode($_GET['mid']);
    $exists=$obj->getData("SELECT id FROM materials WHERE id=?",$s_arr)->num_rows;
    if($exists!=1){
        header("Location: index.php");
        exit();
    }
    $aff_row=$obj->setData($q,$s_arr)->affected_rows;

    if($aff_row==1){
        $_SESSION['deleted']=true;
    }
    else if($aff_row<0){
        $_SESSION['deleted']=false;
    }

    header("Location: materials.php?cid={$_GET['cid']}");
}
else{
    header("Location: index.php");
}

ob_end_flush();
?>