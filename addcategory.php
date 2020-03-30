<?php 
    include 'includes/header.php';

    if(!$obj->session_check()){
        header("Location: index.php");
        exit();
    }

    if(isset($_POST['new_cat'])){
        $s_arr=array($obj->input_clean($_POST['category_name']), date('m/d/Y'), $_SESSION['admin_name']);
        if(empty($s_arr[0])){
            $_SESSION['err']="Empty fields found!";
        }
        else if(mb_strlen($s_arr[0])>70){
            $_SESSION['err']="More than 70 characters are not allowed!";
        }
        else{
            $g_arr2=array($s_arr[0]);
            $exists=$obj->getData("SELECT title FROM category WHERE title=? AND status=1",$g_arr2)->num_rows;
            if($exists!=0){
                $_SESSION['msg']="Duplicate title detected!";
                $_SESSION['msg_type']="negative";
            }
            else{
                $q="INSERT INTO category (title,date_created,added_by) VALUES(?,?,?)";
                $aff_row = $obj->setData($q,$s_arr)->affected_rows;

                if($aff_row<0){
                    $_SESSION['msg']="Error occured!";
                    $_SESSION['msg_type']="negative";
                }
                else if($aff_row==1){
                    $_SESSION['msg']='Successfully created! taking you to home page. 
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
                header("Refresh:1, url=index.php");
            } ?>
        </div>
        <?php } 
        unset($_SESSION['msg']); 
        unset($_SESSION['msg_type']);
        ?>
        <div class="ui card card-c2">
            <form class="ui form" method="POST" autocomplete="off">
                <div class="field">
                    <label>Title&emsp;(<span id="chr_count_disp"></span>/70)</label>
                    <input type="text" id="input_val" name="category_name" placeholder="Title" required>
                </div>
                    <button type="submit" class="ui blue button btn2" name="new_cat">Add&emsp;<i class="plus icon"></i></button>
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
        
        chrcountdisp.innerHTML=0;
        
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