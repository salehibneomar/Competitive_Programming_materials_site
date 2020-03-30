<?php 
    include 'includes/header.php';

    if($obj->session_check()){
        header("Location: index.php");
        exit();
    }

    if(isset($_POST['login'])){
        $g_arr2=array($obj->input_clean($_POST['name']),$obj->input_clean($_POST['pwd']));

        if(empty($g_arr2[0]) || empty($g_arr2[1])){
            $_SESSION['err']="Empty fields found!";
        }
        else{
            $g_arr2[1]=$obj->pwd_encrypt($g_arr2[1]);
            $q="SELECT * FROM admin WHERE name=? AND pwd=?";
            $user_data=$obj->getData($q,$g_arr2);
            if($user_data->num_rows!=1){
                $_SESSION['msg']="Invalid login information!";
                $_SESSION['msg_type']="negative";
            }
            else{
                $user_data=$user_data->fetch_assoc();
                if($user_data['activity_status']!=1){
                    $_SESSION['msg']="You're locked contact a super admin!";
                    $_SESSION['msg_type']="negative";
                }
                else{
                    $_SESSION['logged_in']=1;
                    $_SESSION['admin_name']=$user_data['name'];
                    $_SESSION['admin_type']=$user_data['admin_type'];
                    $_SESSION['admin_id']=$user_data['id'];
                    header("Location: index.php");
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
        </div>
        <?php } 
        unset($_SESSION['msg']); 
        unset($_SESSION['msg_type']);
        ?>
        <div class="ui card card-c2">
            <form class="ui form" method="POST" autocomplete="off">
                <div class="field">
                    <label>Name</label>
                    <input type="text" name="name" placeholder="Name" required>
                </div>
                <div class="field">
                    <label>Password</label>
                    <input type="password" name="pwd"  placeholder="Password" required>
                </div>
                    <button type="submit" class="ui green button btn2" name="login">Login&emsp;<i class="sign in alternate icon"></i></button>
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

</body>
</html>

<?php
ob_end_flush();
?>