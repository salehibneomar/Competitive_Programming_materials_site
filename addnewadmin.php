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

    if(isset($_POST['new_adm'])){
        $s_arr=array($obj->input_clean(ucwords(strtolower($_POST['name']))),$obj->input_clean($_POST['pwd']),date('m/d/Y'));

        $g_arr2[0]=$s_arr[0];

        $exists=$obj->getData("SELECT name FROM admin WHERE name=?",$g_arr2)->num_rows;

        if(empty($s_arr[0]) || empty($s_arr[1])){
            $_SESSION['err']="Empty fields found!";
        }
        else if($exists!=0){
            $_SESSION['msg']="Name already exists!";
            $_SESSION['msg_type']="negative";
        }
        else if(mb_strlen($s_arr[0])<3 || mb_strlen($s_arr[0])>50 || preg_match('~[0-9]~',$s_arr[0])){
            $_SESSION['err']="Invalid name!";
        }
        else if(mb_strlen($s_arr[1])<6){
            $_SESSION['err']="Password length cannot be less than 6 characters";
        }
        else{
            $s_arr[1]=$obj->pwd_encrypt($s_arr[1]);
            $q="INSERT INTO admin (name,pwd,join_date) VALUES(?,?,?)";
            $aff_rows=$obj->setData($q,$s_arr)->affected_rows;
            if($aff_rows<1){
                $_SESSION['msg']="Error occured!";
                $_SESSION['msg_type']="negative";
            }
            else if($aff_rows>0){
                $_SESSION['msg']="Successfully created account!";
                $_SESSION['msg_type']="success";
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
            <form class="ui form" method="POST" action="" autocomplete="off">
                <div class="field">
                    <label>Name</label>
                    <input type="text" name="name" placeholder="Name" required>
                </div>
                <div class="field">
                    <label>Password <span class="mini ui button" id="pwdgen">Generate</span></label>
                    <input type="text" id="pwdfield" name="pwd"  placeholder="Password" required>
                    <span style="display:inline-block;margin-top:5px; color:red" id="pwd_msg"></span>
                </div>
                    <button type="submit" class="ui blue button btn2" name="new_adm">Add&emsp;<i class="plus icon"></i></button>
            </form>
        </div>  
        <?php if(isset($_SESSION['err'])){ ?>
        <div class="ui negative message">
            <p><b><?=$_SESSION['err'];?></b></p>
            <?php unset($_SESSION['err']); ?>
        </div>
        <?php }?>                
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