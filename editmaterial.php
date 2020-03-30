<?php
    include 'includes/header.php';

    if(!$obj->session_check()){
        header("Location: index.php");
        exit();
    }

    $mid=base64_decode($_GET['mid']);
    $g_arr2=array($mid,base64_decode($_GET['cid']));
    $result=$obj->getData("SELECT title,link,importance,uploaded_by,cid,type FROM materials WHERE id=? AND cid=?",$g_arr2);
    $exists=$result->num_rows;
    $cid=$_GET['cid'];
    
    if(!isset($_GET['cid']) || !isset($_GET['mid']) || $exists!=1){
        header("Location: materials.php?cid={$cid}");
        exit();
    }
    $g_arr3=array();
    $category=$obj->getData("SELECT id,title FROM category",$g_arr3);
    $type=$obj->getData("SELECT DISTINCT(type) FROM materials",$g_arr3);
    $v=$result->fetch_assoc();

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
            <form class="ui form" method="POST" action="update.php">
                <div class="field">
                    <label>Category</label>
                    <select class="ui search dropdown" name="n_category">
                        <?php while($c=$category->fetch_assoc()){ ?>
                            <?php if($v['cid']==$c['id']){?>

                                <option value="<?=base64_encode($c['id']);?>" selected><?=$c['title'];?></option>
                            <?php } else{?>

                                <option value="<?=base64_encode($c['id']);?>"><?=$c['title'];?></option>
                            <?php } ?>
                        <?php } ?>

                    </select>
                </div>
                <div class="field">
                    <label>Title&ensp;(<span id="chr_count_disp"></span>/100)</label>
                    <input type="text" value="<?=$v['title'];?>" name="n_title" id="input_val" placeholder="Title" required>
                </div>
                <div class="field">
                    <label>Paste link (<a href="https://tinyurl.com/" target="_blank"><i class="hand pointer outline icon"></i>short the link first if it seems too large</a>)</label>
                    <input type="text" value="<?=$v['link'];?>" name="n_link" placeholder="link" required>
                </div>
                <div class="field">
                    <label>Type (<small>You can also add custom option if not found)</small></label>
                    <select class="ui search dropdown mtype-drpd" name="mtype">
                        <?php while($t=$type->fetch_assoc()){ ?>
                            <?php if($v['type']==$t['type']){?>
                                <option value="<?=$t['type'];?>" selected><?=$t['type'];?></option>
                            <?php } else{?>
                                <option value="<?=$t['type'];?>"><?=$t['type'];?></option>
                            <?php }?>
                        <?php } ?>
                    </select>
                </div>
                <div class="field">
                    <label>Importance <span class="ui left pointing blue label" id="range_slider_value"></span></label>
                    <input id="range_slider" type="range" min="1" max="10" value="<?=$v['importance'];?>" name="n_imp">
                </div>
                <input type="hidden" value="<?=$_GET['mid'];?>" name="hidden_input1">
                <input type="hidden" value="<?=$cid;?>" name="hidden_input2">
                <button type="submit" class="ui teal button btn2" name="edit_material">Update&emsp;<i class="sync alternate icon"></i></button>
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