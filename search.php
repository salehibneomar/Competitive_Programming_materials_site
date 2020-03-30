<?php
    require 'config/config.php';

    if(isset($_POST['src_value'])){
        $v=$obj->input_clean($_POST['src_value']);
        $g_arr=array();
        $q="SELECT link, title FROM materials WHERE title LIKE '%$v%' OR type LIKE '%$v%' OR date_uploaded LIKE '%$v%'";
        $result=$obj->getData($q,$g_arr);
        
        if($result->num_rows>0){

            echo "<span class='ui blue vertical label'>".$result->num_rows." records found."."</span>"."<br/>";

            while($d=$result->fetch_assoc()){
                echo '<a class="anchorlink" href="'.$d['link'].'"style="display: inline-block; margin-top: 10px;">'.$d['title'].
                "</a><br/>";
            }
        }
        else{
            echo "<span class='ui yellow vertical label'>No records found."."</span>";
        }
    }

?>