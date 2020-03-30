<?php
    include 'includes/header.php';

    $q="SELECT * FROM category ORDER BY id ASC";
    $gd_arr2=array();
    if(!$obj->session_check())
    {$q="SELECT * FROM category WHERE status=? ORDER BY id ASC"; $gd_arr2[0]=1;}

    $result=$obj->getData($q,$gd_arr2);
    $arr=array('Archived','Active');
?>

<!--Main Section-->
<section>
    <div class="wrapper1">
        <?php while($v=$result->fetch_assoc()){?>
        <div class="card-con">
            <div class="ui card card-c1">
                <div class="content">
                <?php if($obj->session_check()){?>
                    <a class="right floated only-icon" href="editcategory.php?cid=<?=base64_encode($v['id']);?>">
                        <i class="fas fa-edit icon-c1"></i>
                    </a>
                <?php } ?>
                
                    <div class="header header-c1"><?=$v['title'];?></div>
                    <div class="meta">
                        Created:&ensp;<?=$v['date_created'];?>

                        <?php
                            if($obj->session_check()){
                                echo "&ensp;Status:&ensp;".$arr[$v['status']];
                            }
                        ?>
                        
                        <br>
                        Added by:&ensp;<?=$v['added_by'];?> 
                    </div>
                </div>
                <div class="extra content c-footer">
                    <a class="btn1" href="materials.php?cid=<?=base64_encode($v['id']);?>">Enter</a>
                </div>
            </div>
        </div>  
        <?php } ?>                                  
    </div>
</section>
<!--Main Section-->

<button class="circular ui icon twitter button site-info">
  <i class="icon question"></i>
</button>


<?php 
    include 'includes/scripts.php';
?>

<script>
    let curr_year=(new Date()).getFullYear();
     $('.site-info').on('click', function(){
        Swal.fire({
            type: 'info',
            html: '<p style="line-height:30px;">'+
            'Designed and Developed by <br> Saleh Ibne Omar <br>'+
            'Email: <a href="mailto:salehibneomar@gmail.com">salehibneomar@gmail.com</a> <br>'+
            'Beta version: 2.0 <br>'+
            'If you spot any filthy bugs then email me <br>'+
            '&copy; '+curr_year+' Saleh Ibne Omar'+'</p>',
            showConfirmButton: false,
        });
    });
</script>

</body>
</html>

<?php
ob_end_flush();
?>