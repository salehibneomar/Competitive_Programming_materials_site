<?php
    include 'includes/header.php';

    if(!$obj->session_check()){
        header("Location: index.php");
        exit();
    }

    $cid=base64_decode($_GET['cid']);
    $g_arr2[0]=$cid;
    $result=$obj->getData("SELECT title, status FROM category WHERE id=?",$g_arr2);
    $exists=$result->num_rows;
    $v=$result->fetch_assoc();

    $status=array("Archived", "Unarchived");

    if(!isset($_GET['cid']) || $exists!=1){
        header("Location: index.php");
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
            <form class="ui form" method="POST" action="update.php">
                <div class="field">
                    <label>New title&emsp;(<span id="chr_count_disp"></span>/70)</label>
                    <input type="text" id="input_val" name="new_title" value="<?=$v['title'];?>" placeholder="Title">
                </div>
                <div class="ui toggle checkbox inline-chkbox">
                    <input type="checkbox" value="1" name="tgl" <?php if($v['status']==0){echo "checked";} ?>>
                    <label><?=$status[$v['status']];?></label>
                </div>
                <input type="hidden" value="<?=$_GET['cid'];?>" name="hidden_input">
                <br>
                <button type="submit" class="ui teal button btn2" name="edit_category">Update&emsp;<i class="sync alternate icon"></i></button>
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
        var inputvalue = document.getElementById("input_val");
        var chrcountdisp = document.getElementById("chr_count_disp");
        
        chrcountdisp.innerHTML=inputvalue.value.length;
        
        inputvalue.addEventListener('keyup', function(){
                let len=inputvalue.value.length;
                if(len>=70){document.getElementById('chr_count_disp').style.setProperty("color","red","important");}
                else{document.getElementById('chr_count_disp').style.setProperty("color","rgba(52, 73, 94,1.0)","important");}
                chrcountdisp.innerHTML=len;
        });
    </script>

</body>
</html>

<?php
ob_end_flush();
?>