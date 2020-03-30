<?php
    include 'includes/header.php';

    if(!$obj->session_check()){
        header("Location: index.php");
        exit();
    }

    $q="SELECT name, join_date, admin_type FROM admin WHERE id=?";
    $g_arr2[0]=$_SESSION['admin_id'];
    $user_data=$obj->getData($q,$g_arr2)->fetch_assoc();
    $arr=array("", "Moderator", "Super Admin");

?>

<!--Main Section-->
<section>
    <div class="wrapper2">
        <div class="ui card card-c3">
            <p class="avatar-icon">
                <i class="ui centered blue user circle icon massive"></i>
            </p>
            <div class="content">
                <div class="header header-c2"><?=$user_data['name'];?></div>
                <div class="meta">
                    <span class="date"><b>Date joined:&ensp;<?=$user_data['join_date'];?></b></span> <br>
                    <span class="date"><b>Type:&ensp;<?=($arr[$user_data['admin_type']]);?></b></span>
                </div>
            </div>
            <div class="extra content">
                <a class="ui right floated teal button btn2" href="changepassword.php"><i class="edit icon"></i>Change password</a>
            </div>
        </div>                  
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