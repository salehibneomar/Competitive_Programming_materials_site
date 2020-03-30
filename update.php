<?php
require 'config/config.php';

if(!$obj->session_check() || (!isset($_POST['edit_admin']) && !isset($_POST['edit_category']) && !isset($_POST['edit_material']))){
    header("Location: index.php");
    exit();
}

if(isset($_POST['edit_admin'])){
    $aid=base64_decode($_POST['hidden_input1']);
    $a_stat=$_POST['hidden_input2'];

    $s_arr=array($obj->input_clean($_POST['pwd']), 1, $aid);

    if(!isset($_POST['tgl'])){
        $s_arr[1]=0;
    }

    if($s_arr[1]==$a_stat && empty($s_arr[0])){
        $_SESSION['msg']="No change!";
        $_SESSION['msg_type']="info";
    }
    else if(!empty($s_arr[0]) && mb_strlen($s_arr[0])<6){
        $_SESSION['err']="Password cannot be less than 6 characters!";
    }
    else{
        
        $q="UPDATE admin SET pwd=?, activity_status=? WHERE id=? LIMIT 1";
        
        if(empty($s_arr[0])){
            $q="UPDATE admin SET activity_status=? WHERE id=? LIMIT 1";
            unset($s_arr[0]);
            $s_arr=array_values($s_arr);
        }
        else{
            $s_arr[0]=$obj->pwd_encrypt($s_arr[0]);
        }
        
        $aff_row=$obj->setData($q,$s_arr)->affected_rows;

        if($aff_row<0){
            $_SESSION['msg']="Error occured!";
            $_SESSION['msg_type']="negative";
        }
        else if($aff_row==0){
            $_SESSION['msg']='No change!';
            $_SESSION['msg_type']="info";
        }
        else if($aff_row==1){
            $_SESSION['msg']='Successfully updated!';
            $_SESSION['msg_type']="success";
        }
    }
    
    $aid=base64_encode($aid);
    header("Location: editadmins.php?aid={$aid}");
}
else if(isset($_POST['edit_category'])){
    $cid=base64_decode($_POST['hidden_input']);
    $s_arr=array($obj->input_clean($_POST['new_title']),1,$cid);

    if(isset($_POST['tgl'])){
        $s_arr[1]=0;
    }

    if(empty($s_arr[0])){
        $_SESSION['err']="Empty fields found!";
    }
    else if(mb_strlen($s_arr[0])>70){
        $_SESSION['err']="More than 70 characters are not allowed!";
    }
    else{

        $g_arr=array($s_arr[0]);
        $exists=$obj->getData("SELECT title FROM category WHERE title=? AND status=1 AND id!='$cid'",$g_arr)->num_rows;

        if($exists!=0){
            $_SESSION['msg']="Duplicate title detected! same as an active category";
            $_SESSION['msg_type']="negative";
        }
        else{
            $q="UPDATE category SET title=?, status=? WHERE id=? LIMIT 1";
            $aff_row = $obj->setData($q,$s_arr)->affected_rows;
    
            if($aff_row<0){
                $_SESSION['msg']="Error occured!";
                $_SESSION['msg_type']="negative";
            }
            else if($aff_row==1){
                $_SESSION['msg']='Successfully updated!';
                $_SESSION['msg_type']="success";
            }
            else if($aff_row==0){
                $_SESSION['msg']="No change!";
                $_SESSION['msg_type']="info";
            }
        }
    }
    $cid=base64_encode($cid);
    header("Location: editcategory.php?cid={$cid}");
}
else if(isset($_POST['edit_material'])){
    $mid=base64_decode($_POST['hidden_input1']);
    $cid=base64_decode($_POST['hidden_input2']);

    $s_arr=array(base64_decode($_POST['n_category']), 
    $obj->input_clean($_POST['n_title']), 
    $obj->input_clean($_POST['n_link']), 
    $_POST['n_imp'],
    ucwords(strtolower($obj->input_clean($_POST['mtype']))),
    $mid);

    if(empty($s_arr[1]) || empty($s_arr[2])){
        $_SESSION['err']="Empty fields found!";
    }
    else if(mb_strlen($s_arr[1])>100){
        $_SESSION['err']="Title exceedes 80 characters!";
    }
    else if(mb_strlen($s_arr[2])<10 || mb_strlen($s_arr[2])>200){
        $_SESSION['err']="Invalid URL!";
    }
    else{
        $exists=0;

        if($s_arr[0]!=$cid){
        $g_arr=array($s_arr[0],$s_arr[1]);
        $exists=$obj->getData("SELECT title FROM materials WHERE cid=? AND title=?",$g_arr)->num_rows;
        }
        
        if($exists!=0){
            $_SESSION['msg']="Duplicate title detected for the new category!";
            $_SESSION['msg_type']="negative";
        }
        else{
            $q="UPDATE materials SET cid=?, title=?, link=?, importance=?, type=? WHERE id=? LIMIT 1";
            $aff_row=$obj->setData($q,$s_arr)->affected_rows;
            if($aff_row<0){
                $_SESSION['msg']="Error occured!";
                $_SESSION['msg_type']="negative";
            }
            else if($aff_row==1){
                $_SESSION['msg']='Successfully updated! Taking you to the material list. 
                <span class="ui tiny active centered inline loader"></span>';
                $_SESSION['msg_type']="success";
            }
            else if($aff_row==0){
                $_SESSION['msg']="No change!";
                $_SESSION['msg_type']="info";
            }
        }
    }

    $mid=base64_encode($mid);
    $cid=base64_encode($cid);
    if($exists==0){
        $cid=base64_encode($s_arr[0]);
    }

    header("Location: editmaterial.php?cid={$cid}&mid={$mid}");
}

ob_end_flush();
?>
