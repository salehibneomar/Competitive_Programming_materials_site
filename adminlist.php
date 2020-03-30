<?php
    include 'includes/header.php';

    if(!$obj->session_check()){
        header("Location: index.php");
        exit();
    }

    $color=array("red","green");
    $adm_type=array("", "Moderator", "Super Admin");

    $g_arr2=array();
    $total_data=$obj->getData("SELECT COUNT(id) as total FROM admin",$g_arr2)->fetch_assoc()['total'];
    
    $q="SELECT name,admin_type,join_date,activity_status FROM admin ORDER BY activity_status DESC";
    if($_SESSION['admin_type']==2){
        $q="SELECT id,name,admin_type,join_date,activity_status FROM admin ORDER BY activity_status DESC";
    }
    $result=$obj->getData($q,$g_arr2);

?>

<!--Main Section-->
<section>
    <div class="wrapper3">
        <div class="ui card card-c2">
            <div class="content table-con">
                <div class="header header-c2">Admin list</div>
                <span class="ui green vertical label"><?=$total_data." records found.";?></span>
            <table class="ui unstackable celled table">
                    <thead>
                        <tr>
                            <th width="5%">#</th>
                            <th>Name</th>
                            <th width="10%">Joined</th>
                            <th width="15%">Type</th>
                            <th width="5%">Athurity</th>
                            <?php if($_SESSION['admin_type']==2){?>
                            <th width="5%">Edit</th>
                            <?php }?>
                        </tr>
                    </thead>
                    <tbody>
                    <?php $i=1; while($v=$result->fetch_assoc()){?>
                        <tr>
                            <td><span class="ui circular label"><?=$i;?></span></td>
                            <td><?=$v['name'];?></td>
                            <td><span class="ui horizontal large label"><?=$v['join_date'];?></span></td>
                            <td><?=($adm_type[$v['admin_type']]);?></td>
                            <td style="text-align:center; color:<?=$color[$v['activity_status']];?>"><i class="far fa-dot-circle"></i></td>
                            <?php if($_SESSION['admin_type']==2){?>
                                <?php if($_SESSION['admin_id']==$v['id'] || $_SESSION['admin_type']==$v['admin_type']){
                                    echo '<td><a class="tiny ui teal button"><i class="fas fa-user-alt"></i></a></td>';
                                } else{?>
                                <td>
                                    <a href="editadmins.php?aid=<?=base64_encode($v['id']);?>" class="tiny ui yellow button"><i class="fas fa-edit"></i></a>
                                </td>
                                <?php }?>
                            <?php }?>
                        </tr>
                    <?php $i++; }?>
                    </tbody>
                </table>
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