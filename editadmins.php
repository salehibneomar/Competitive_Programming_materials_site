<?php 
    include 'includes/header.php';

    if(!$obj->session_check()){
        header("Location: index.php");
        exit();
    }
    else if($obj->session_check() && $_SESSION['admin_type']!=2){
        header("Location: index.php");
        exit();
    }

    $aid=base64_decode($_GET['aid']);
    $g_arr2[0]=$aid;
    $result=$obj->getData("SELECT name, activity_status, admin_type FROM admin WHERE id=?",$g_arr2);
    $exists=$result->num_rows;
    $v=$result->fetch_assoc();
    $status=array("Blocked", "Active");

    if(!isset($_GET['aid']) || $exists!=1 || $v['admin_type']!=1){
        header("Location: adminlist.php");
        exit();
    }

?>

<!--Main Section-->
<section>
    <div class="wrapper2">
        <?php if(isset($_SESSION['msg'])){ ?>
        <div class="ui <?=$_SESSION['msg_type'];?> message">
            <p><b><?= $_SESSION['msg'];?></b></p>
        </div>
        <?php } 
        unset($_SESSION['msg']); 
        unset($_SESSION['msg_type']);
        ?>
        <div class="ui card card-c2">
            <form class="ui form" method="POST" action="update.php" autocomplete="off">
                <div class="field">
                    <label>Name</label>
                    <input type="text" placeholder="Name" value="<?=$v['name'];?>" disabled>
                </div>
                <div class="field">
                    <label>Password <span class="mini ui button" id="pwdgen">Generate</span></label>
                    <input type="text" id="pwdfield" name="pwd"  placeholder="Password">
                </div>
                <div class="ui toggle checkbox inline-chkbox">
                    <input type="checkbox" name="tgl" value="1" <?php if($v['activity_status']==1){ echo 'checked'; } ?> >
                    <label><?=$status[$v['activity_status']];?></label>
                </div>
                <input type="hidden" value="<?=$_GET['aid'];?>" name="hidden_input1">
                <input type="hidden" value="<?=$v['activity_status'];?>" name="hidden_input2">
                <br>
                <button type="submit" class="ui teal button btn2" name="edit_admin">Update&emsp;<i class="sync alternate icon"></i></button>
            </form>
        </div>
        <?php if(isset($_SESSION['err'])){ ?>
        <div class="ui negative message">
            <p><b><?=$_SESSION['err'];?></b></p>
        </div>
        <?php } unset($_SESSION['err']); ?>                  
    </div>
</section>
<!--Main Section-->

<?php 
    include 'includes/scripts.php';
?>

    <script>
        document.getElementById("pwdgen").addEventListener("click",function(){
            let pwd = Math.random().toString(26).slice(-6);
            document.getElementById("pwdfield").value=pwd;
        });
    </script>

</body>
</html>

<?php
ob_end_flush();
?>