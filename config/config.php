<?php
    date_default_timezone_set('Asia/Dhaka');
    //error_reporting(0);
    ob_start();
    session_cache_limiter(false);
    session_start();

    include 'class.php';

    $obj= new Project();
    $obj->connect_to_db();

?>