<?php
    include 'includes/header.php';
    
    $g_arr2=array();
    $q=$type=false;
    
    if(isset($_GET['q'])){
        $q=true;
        if($_GET['q']=='recent'){
            $result=$obj->getData("SELECT c.title AS ctitle, m.id, m.title AS title, m.link, m.importance, m.uploaded_by, m.date_uploaded, m.cid, m.type
                                    FROM materials m, category c 
                                    WHERE c.id=m.cid ORDER BY m.id DESC LIMIT 10",$g_arr2);

            $title="Recent materials (Maximum 10)";
            $total_data=$result->num_rows;
        }
        else if($_GET['q']=='today'){
            $title="Added today";
            $g_arr2[0]=date('m/d/Y');
            $result=$obj->getData("SELECT c.title AS ctitle, m.id, m.title AS title, m.link, m.importance, m.uploaded_by, m.date_uploaded, m.cid, m.type
                                    FROM materials m, category c 
                                    WHERE c.id=m.cid AND m.date_uploaded=? ORDER BY importance DESC",$g_arr2);
                                    
            $total_data=$result->num_rows;
        }
        else{
            header("Location: index.php");
        }
    }
    else{
        $cid=base64_decode($_GET['cid']);
        $g_arr2=array($cid);
        $query=$obj->getData("SELECT title,status FROM category WHERE id=?",$g_arr2);
        $exists=$query->num_rows;
        $category=$query->fetch_assoc();
        $title=$category['title'];
        $status=$category['status'];
        
        if(isset($_GET['cid']) && $exists==1 && ($status==1 || $obj->session_check())){
            $limit=10;
            $first_page=1;
            $curr_page=1;
            $types=$obj->getData("SELECT DISTINCT(type) FROM materials WHERE cid=?",$g_arr2);
            $total_data=$obj->getData("SELECT COUNT(id) as total FROM materials WHERE cid=?",$g_arr2)->fetch_assoc()['total'];

            if(isset($_GET['page'])){
                $first_page=max(1,$_GET['page']-1);
                $curr_page=$obj->input_clean($_GET['page']);
                if($curr_page<1 || $curr_page>$total_data || empty($curr_page)){
                    header("Location: materials.php?cid=".base64_encode($cid));
                }
            }

            $offset=($curr_page-1)*$limit;
            
            if(isset($_GET['type'])){
                $type=$obj->input_clean($_GET['type']);
                $total_data=$obj->getData("SELECT COUNT(id) as total FROM materials WHERE cid=? AND type='$type'",$g_arr2)->fetch_assoc()['total'];
                $result=$obj->getData("SELECT * FROM materials WHERE cid=? AND type='$type' ORDER BY date_uploaded DESC LIMIT $offset, $limit",$g_arr2);

                if(!$type || $total_data<1 || $curr_page>$total_data){
                    header("Location: materials.php?cid=".base64_encode($cid));
                }  
            }
            else{
                $result=$obj->getData("SELECT * FROM materials WHERE cid=? ORDER BY date_uploaded DESC LIMIT $offset, $limit",$g_arr2);
            }

            $total_pages=ceil($total_data/$limit);

        }
        else{
            header("Location: index.php");
        }
    }
?>

<!--Main Section-->
<section>
    <div class="wrapper3">
        <div class="ui card card-c2">
            <div class="content table-con">
            <div class="header header-c2"><?=$title;?></div>
            <span class="ui green vertical label"><?=$total_data." records found.";?></span>
        <?php if($total_data>0){?>
            <?php if(!$q){?>
            <div class="inline-filter-con">
                <form action="" method="get" class="ui form" id="type-drpd">
                    <input type="hidden" name="cid" value="<?=base64_encode($cid);?>">
                    <select name="type">
                        <option>All</option>
                    <?php while($t=$types->fetch_assoc()){ ?>

                        <?php if($type==$t['type']){?>

                            <option value="<?=$t['type'];?>" selected><?=$t['type'];?></option>
                        <?php } else{?>

                            <option value="<?=$t['type'];?>"><?=$t['type'];?></option>
                        <?php } ?>
                    <?php } ?>

                    </select>
                </form>
            </div>
            <?php } ?>
            <table class="ui unstackable celled table">
                    <thead>
                        <tr>
                            <th width="5%">#</th>
                            <?php if($q){ ?>
                            <th>Category</th>
                            <?php } ?>
                            
                            <th>Link</th>
                            <th width="15%">Type</th>
                            <th width="15%">Importance</th>
                            <th width="10%">Date</th>
                            <th width="15%">Added by</th>
                            <?php if($obj->session_check()){?>
                            <th width="5%">Edit</th>
                            <th width="5%">Delete</th>
                            <?php }?>

                        </tr>
                    </thead>
                    <tbody>
                    <?php $count=1; while($v=$result->fetch_assoc()){ ?>

                        <tr>
                            <td><span class="ui circular label"><?=$count;?></span></td>
                            <?php if($q){ ?>
                            <td><?=$v['ctitle'];?></td>
                            <?php } ?>

                            <td><a href="<?=$v['link'];?>" class="anchorlink"><?=$v['title'];?></a></td>
                            <td><?=$v['type'];?></td>
                            <td>
                            <span class="ui teal progress" >
                                <span class="bar" style="width:<?=$v['importance']*10;?>%!important;" data-tooltip="<?=$v['importance'];?>/10" data-position="top right"></span>
                            </span>
                            </td>
                            <td><span class="ui horizontal large label"><?=$v['date_uploaded'];?></span></td>
                            <td><?=$v['uploaded_by'];?></td>
                            <?php if($obj->session_check()){?>
                            <td>
                                <a href="editmaterial.php?cid=<?=base64_encode($v['cid'])?>&mid=<?=base64_encode($v['id'])?>" class="tiny ui yellow button"><i class="fas fa-edit"></i></a>
                            </td>
                            <td>
                                <a href="delete.php?cid=<?=base64_encode($v['cid'])?>&mid=<?=base64_encode($v['id'])?>" class="tiny ui red button delete_btn"><i class="far fa-trash-alt"></i></a>
                            </td>
                            <?php }?>

                        </tr>
                    <?php $count++; }?>

                    </tbody>
                </table>
                <?php if(!$q){?>
                <div class="ui pagination menu">

                    <?php if($type){?>
                        <a class="item" href="materials.php?cid=<?=base64_encode($cid);?>&type=<?=$type;?>&page=<?=max(1,$curr_page-1);?>" class="item">&larr;</a>

                    <?php } else{?>

                        <a href="materials.php?cid=<?=base64_encode($cid);?>&page=<?=max(1,$curr_page-1);?>" class="item">&larr;</a>
                    <?php }?>

                    <?php for($i=$first_page; $i<=min($total_pages,$first_page+3); $i++){ ?>
                        <?php if($type){?>
                            <?php if($i==$curr_page){?>

                                <a class="active-link item" href="materials.php?cid=<?=base64_encode($cid);?>&type=<?=$type;?>&page=<?=$i;?>"><?=$i;?></a>

                            <?php } else{?>
                                <a href="materials.php?cid=<?=base64_encode($cid);?>&type=<?=$type;?>&page=<?=$i;?>" class="item"><?=$i;?></a>
                            <?php }?>
                        <?php } else{?>

                            <?php if($i==$curr_page){?>

                                <a class="active-link item" href="materials.php?cid=<?=base64_encode($cid);?>&page=<?=$i;?>"><?=$i;?></a>
                            <?php } else{?>

                                <a href="materials.php?cid=<?=base64_encode($cid);?>&page=<?=$i;?>" class="item"><?=$i;?></a>
                            <?php }?>
                        <?php }?>
                    <?php }?>

                    <?php if($type){?>

                        <a class="item" href="materials.php?cid=<?=base64_encode($cid);?>&type=<?=$type;?>&page=<?=min($total_pages,$curr_page+1);?>">&rarr;</a>

                    <?php } else{?>

                        <a href="materials.php?cid=<?=base64_encode($cid);?>&page=<?=min($total_pages,$curr_page+1);?>" class="item">&rarr;</a>
                    <?php }?>
        
                </div>
                <?php }?>

            <?php }?>

            </div>
        </div>                  
    </div>
</section>
<!--Main Section-->

<?php 
    include 'includes/scripts.php';
?>

<script>

    $('#type-drpd').on('change',function(){
        $(this).submit();
    });

    $('.delete_btn').on('click', function(e){
      e.preventDefault();
        let link=$(this).attr('href');
        Swal.fire({
                title: 'Are you sure?',
                text: "You won't be able to revert this!",
                type: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#3085d6',
                cancelButtonColor: '#d33',
                confirmButtonText: 'Okay'
        }).then((result) => {
            if (result.value){
                $(location).attr('href',link);
            }
        });
    });

</script>

<?php if(isset($_SESSION['deleted'])){?>
    <?php if($_SESSION['deleted']==true){?>
        <script>
            Swal.fire({
                title: 'Successfully deleted',
                position: 'top-end',
                type: 'success',
                showConfirmButton: false,
                timer: 3000
            });
        </script>
    <?php } else if($_SESSION['deleted']==false){?>
        <script>
            Swal.fire({
                title: 'Error occured',
                position: 'top-end',
                type: 'error',
                showConfirmButton: false,
                timer: 3000
            });
        </script>
    <?php }?>
<?php } unset($_SESSION['deleted']);?>

</body>
</html>

<?php
ob_end_flush();
?>