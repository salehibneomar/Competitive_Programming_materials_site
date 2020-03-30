<?php 
    include 'includes/header.php';

    if(!$obj->session_check()){
        header("Location: index.php");
        exit();
    }

    $g_arr2=array();
    $category=$obj->getData("SELECT id,title FROM category WHERE status=1",$g_arr2);
    $type=$obj->getData("SELECT DISTINCT(type) FROM materials",$g_arr2);
    $s_arr=array_fill(0,7,"");

    if(isset($_POST['add_material'])){
        $s_arr=array(
            $obj->input_clean($_POST['title']), //0
            $obj->input_clean($_POST['link']), //1
            $_POST['imp'], //2
            $_SESSION['admin_name'], //3
            date('m/d/Y'),//4
            $_POST['ctgry'],//5
            ucwords(strtolower($obj->input_clean($_POST['mtype'])))); //6

            if(empty($s_arr[0]) || empty($s_arr[1]) || empty($s_arr[5])){
                $_SESSION['err']="Empty fields found!";
            }
            else if(mb_strlen($s_arr[0])>100){
                $_SESSION['err']="Title exceeds 100 characters!";
            }
            else if(mb_strlen($s_arr[1])<10 || mb_strlen($s_arr[1])>200){
                $_SESSION['err']="Invalid URL!";
            }
            else{
                $cid=$s_arr[5];
                $s_arr[5]=base64_decode($s_arr[5]);
                $g_arr3=array($s_arr[0],$s_arr[5]);
                $exists=$obj->getData("SELECT title FROM materials WHERE title=? AND cid=?",$g_arr3)->num_rows;

                if($exists!=0){
                    $_SESSION['msg']="Duplicate title found for the selected category!";
                    $_SESSION['msg_type']="negative";
                }
                else{
                    $q="INSERT INTO materials(title, link, importance, uploaded_by, date_uploaded, cid, type) VALUES(?,?,?,?,?,?,?)";
                    $aff_row=$obj->setData($q,$s_arr)->affected_rows;

                    if($aff_row<0){
                        $_SESSION['msg']="Error occured!";
                        $_SESSION['msg_type']="negative";
                    }
                    else if($aff_row==1){
                        $_SESSION['msg']='Successfully added! Taking you to the material list. 
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
                header("Refresh:1, url=materials.php?cid={$cid}");
            } ?>
        </div>
        <?php } 
        unset($_SESSION['msg']); 
        unset($_SESSION['msg_type']);
        ?>
        <div class="ui card card-c2">
            <form class="ui form" method="POST" autocomplete="off">
                <div class="field">
                    <label>Category</label>
                    <select class="ui fluid search selection dropdown" name="ctgry">
                        <option value="">--Scroll or Search--</option>
                        <?php while($c=$category->fetch_assoc()){ ?>
                            <option value="<?=base64_encode($c['id']);?>"><?=$c['title'];?></option>
                        <?php } ?>
                    </select>
                </div>
                <div class="field">
                    <label>Title&ensp;(<span id="chr_count_disp"></span>/100)</label>
                    <input type="text" name="title" id="input_val" value="<?=$s_arr[0];?>" placeholder="Title" required>
                </div>
                <div class="field">
                    <label>Paste link (<a href="https://tinyurl.com/" target="_blank"><i class="hand pointer outline icon"></i>short the link first if it seems too large</a>)</label>
                    <input type="text" name="link" value="<?=$s_arr[1];?>" placeholder="link" required>
                </div>
                <div class="field">
                    <label>Type (<small>You can also add custom option if not found)</small></label>
                    <select class="ui search dropdown mtype-drpd" name="mtype">
                        <?php while($t=$type->fetch_assoc()){ ?>
                            <option value="<?=$t['type'];?>"><?=$t['type'];?></option>
                        <?php } ?>
                    </select>
                </div>
                <div class="field">
                    <label>Importance <span class="ui left pointing blue label" id="range_slider_value"></span></label>
                    <input id="range_slider" type="range" min="1" max="10" value="<?=$s_arr[2];?>" name="imp">
                </div>
                    <button type="submit" class="ui green button btn2" name="add_material">Submit&emsp;<i class="check circle icon"></i></button>
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
        var slider=document.getElementById("range_slider");
        var val=document.getElementById("range_slider_value");
        val.innerHTML=slider.value;
        
        chrcountdisp.innerHTML=inputvalue.value.length;
        
        inputvalue.addEventListener('keyup', function(){
                let len=inputvalue.value.length;
                if(len>=100){document.getElementById('chr_count_disp').style.setProperty("color","red","important");}
                else{document.getElementById('chr_count_disp').style.setProperty("color","rgba(52, 73, 94,1.0)","important");}
                chrcountdisp.innerHTML=len;
        });

        slider.addEventListener('input',function(){
            val.innerHTML=slider.value;
        });

        $('.mtype-drpd').dropdown({
            allowAdditions: true
        });

    </script>

</body>
</html>

<?php
ob_end_flush();
?>