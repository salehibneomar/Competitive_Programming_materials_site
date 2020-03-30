<?php 
    require 'config/config.php';

    $gd_arr1=array(date('m/d/Y'));
    $uptoday=$obj->getData("SELECT COUNT(id) AS today FROM materials WHERE date_uploaded=?",$gd_arr1)->fetch_assoc();
    $gd_arr1=array();
    $total_materials=$obj->getData("SELECT COUNT(id) AS total FROM materials",$gd_arr1)->fetch_assoc();
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <meta name="description" content="Competitive programming materials collection web app">
    <meta name="keywords" content="cp materials, competitive programming, competitive programming materials, saleh ibne omar, contest programming">
    <meta name="author" content="Saleh Ibne Omar">
    <title><?=$obj->pageTitle();?></title>
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.11.2/css/all.min.css" rel="stylesheet">
    <link rel="stylesheet" href="css_js/semantic.min.css">
    <link href="https://fonts.googleapis.com/css?family=Inconsolata:400,700&display=swap" rel="stylesheet"> 
    <link rel="stylesheet" href="css_js/style.css">
    <link rel="icon" href="css_js/icon.png" type="image/x-icon"> 
</head>
<body>
<div id="preloader"><i class="fas fa-circle-notch fa-spin fast-spin loader-icon"></i></div>
<!--Navigation-->
<header id="hd">
    <nav class="ui stackable fixed menu nav-bar-c">
        <a class="item item-c1" href="index.php"><i class="teal microsoft large icon"></i>Home</a>
        <a class="item item-c1" href="javascript:void(0);"><i class="blue archive large icon"></i>Total<span class="ui green label"><?=$total_materials['total'];?></span></a>
        <a class="item item-c1" href="materials.php?q=recent"><i class="orange calendar outline large icon"></i>Recent</a>
        <a class="item item-c1" href="materials.php?q=today"><i class="violet bell bell outline large icon"></i>Added today<span class="ui red label"><?=$uptoday['today'];?></span></a>
        <a class="item item-c1 right" id="src-open"><i class="primary search icon"></i> Search</a>
        <a class="item item-c1 right" id="src-close"><i class="red close icon"></i> Close</a>
        <?php if($obj->session_check()){?>
            <div class="ui right dropdown item item-c1" style="margin:0 !important;">
            <i class="pink user circle large icon"></i><span class="drpd-text"><?=(strtoupper(explode(" ",$_SESSION['admin_name'])[0]));?></span><i class="dropdown icon"></i>
                <div class="menu drpd-menu">
                    <a class="item" href="adminprofile.php"><i class="fas fa-user-alt"></i>&emsp;Profile</a>
                    <a class="item" href="addcategory.php"><i class="fas fa-folder"></i>&emsp;Add category</a>
                    <a class="item" href="addmaterial.php"><i class="fas fa-link"></i>&emsp;Add material</a>
                    <?php if($_SESSION['admin_type']===2){?>
                    <a class="item" href="addnewadmin.php"><i class="fas fa-user-cog"></i>&emsp;Add admin</a>
                    <?php } ?>
                    <a class="item" href="adminlist.php"><i class="fas fa-list-alt"></i>&emsp;Admin list</a>
                    <a class="item" href="logout.php"><i class="fas fa-sign-out-alt"></i>&emsp;Logout</a>
                </div>
            </div>
        <?php } else{?>
            <a class="item item-c1 right" href="adminlogin.php" style="margin:0 !important;"><i class="green sign in alternate icon"></i>login</a>
        <?php } ?>
    </nav>
</header>
<div class="main-search-field">
    <div class="ui card">
        <div class="content">
        <form method="post" class="ui form" autocomplete="off">
            <input type="text" class="src-inp" placeholder="Type here...">
        </form>
        </div>
        <div class="content cont-con" style="border-top:none !important; padding: 0 10px 10px 30px !important;">
            <div class="src-content"></div>
        </div>
    </div>
</div>

<!--Navigation-->