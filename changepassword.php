<?php
    include 'includes/header.php';

    if(!$obj->session_check()){
        header("Location: index.php");
        exit();
    }

    if(isset($_POST['update_pwd'])){

        $s_arr=array($obj->input_clean($_POST['pwd_old']), $obj->input_clean($_POST['pwd_new']));

        if(empty($s_arr[0]) || empty($s_arr[1])){
            $_SESSION['err']="Empty fields found!";
        }
        else if(mb_strlen($s_arr[1])<6){
            $_SESSION['err']="Password length cannot be less than 6";
        }
        else{
            $g_arr2[0]=$_SESSION['admin_id'];
            $old_pwd=$obj->getData("SELECT pwd FROM admin WHERE id=?", $g_arr2)->fetch_assoc()['pwd'];

            $s_arr[0]=$obj->pwd_encrypt($s_arr[0]);
            $s_arr[1]=$obj->pwd_encrypt($s_arr[1]);

            if($s_arr[0]!=$old_pwd){
                $_SESSION['msg']="Old password didn't match!";
                $_SESSION['msg_type']="negative";
            }
            else if($s_arr[1]==$old_pwd){
                $_SESSION['msg']="Same as before!";
                $_SESSION['msg_type']="info";
            }
            else{
                $s_arr[0]=$s_arr[1];
                $s_arr[1]=$g_arr2[0];

                $q="UPDATE admin SET pwd=? WHERE id=? LIMIT 1";

                $aff_row = $obj->setData($q,$s_arr)->affected_rows;

                if($aff_row<0){
                    $_SESSION['msg']="Error occured!";
                    $_SESSION['msg_type']="negative";
                }
                else if($aff_row==1){
                    $_SESSION['msg']='Successfully updated! you will be logged out within a moment. 
                    <span class="ui tiny active centered inline loader"></span>';
                    $_SESSION['msg_type']="success";
                }
            }
        }
    }
    
?>

<!--Main Section-->
<section>
    <div class="wrapper2">
        <?php if(isset($_SESSION['msg'])){ ?>
        <div class="ui <?=$_SESSION['msg_type'];?> message">
            <p><b><?= $_SESSION['msg'];?></b></p>
            <?php if($_SESSION['msg_type']=='success'){
                session_unset();
                session_destroy();
                header("Refresh:2, url=adminlogin.php");
            } ?>
        </div>
        <?php } 
        unset($_SESSION['msg']); 
        unset($_SESSION['msg_type']);
        ?>
        <div class="ui card card-c2">
            <form class="ui form" method="POST">
                <div class="field">
                    <label>Old Password</label>
                    <input type="password" name="pwd_old" placeholder="Old password" required>
                </div>
                <div class="field">
                    <label>New Password</label>
                    <input type="password" id="n_pwd" name="pwd_new" placeholder="New password" required>
                    <span style="display:inline-block;margin-top:5px; color:red" id="pwd_msg"></span>
                </div>
                <button type="submit" class="ui teal button btn2" name="update_pwd">Update&emsp;<i class="sync alternate icon"></i></button>
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
    var pwd = document.getElementById("n_pwd");
    var pwdmsg = document.getElementById("pwd_msg");
    pwd.addEventListener('keyup',function(){
        let len=pwd.value.trim().length;
        if(len<6 && len!=0){
            pwdmsg.innerHTML="Weak password and not accepted";
        }
        else if(len>=6 && len<=10){
            pwdmsg.innerHTML="Medium password";
        }
        else if(len>10){
            pwdmsg.innerHTML="Strong password";
        }
        else{
            pwdmsg.innerHTML="";
        }
    });
</script>

</body>
</html>

<?php
ob_end_flush();
?>