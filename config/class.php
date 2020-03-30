<?php

    class Project{
        private $server='127.0.0.1', $user='root', $pwd='', $db='comp_pro';
        //private $server='sql200.epizy.com', $user='epiz_25141657', $pwd='MQVelEmCRKKv2lW', $db='epiz_25141657_comp_pro';
        //private $server='localhost', $user='id12122214_xsalehon', $pwd='Skidrow901s', $db='id12122214_comp_pro';

        private $conn=null;
        
        public function connect_to_db(){
            $this->conn=new mysqli($this->server,$this->user,$this->pwd,$this->db);
            if($this->conn->connect_error || $this->conn==null){
                header("Location: error503.html");
            }
        }

        public function session_check(){
            if(isset($_SESSION['logged_in'])){
                return true;
            }
            else{
                return false;
            }
        }

        public function input_clean($v){
            $v=strip_tags(trim($v));
            return $v;
        }

        public function pwd_encrypt($v){
            return md5(SHA1($v));
        }

        public function getData($sql, &$arr){
            $arr_size=count($arr);
            $stmt=$this->conn->stmt_init();
            $stmt->prepare($sql);
            switch($arr_size){
                case 1:
                    $stmt->bind_param('s',$arr[0]);
                break;
                case 2:
                    $stmt->bind_param('ss',$arr[0],$arr[1]);
                break;
                case 3:
                    $stmt->bind_param('sss',$arr[0],$arr[1],$arr[2]);
                break;
            }
            $stmt->execute();
            return $stmt->get_result();
        }

        public function setData($sql, &$arr){
            $arr_size=count($arr);
            $stmt=$this->conn->stmt_init();
            $stmt->prepare($sql);
            switch($arr_size){
                case 1:
                    $stmt->bind_param('s',$arr[0]);
                break;
                case 2:
                    $stmt->bind_param('ss',$arr[0],$arr[1]);
                break; 
                case 3:
                    $stmt->bind_param('sss',$arr[0],$arr[1],$arr[2]);
                break; 
                case 4:
                    $stmt->bind_param('ssss',$arr[0],$arr[1],$arr[2],$arr[3]);
                break; 
                case 5:
                    $stmt->bind_param('sssss',$arr[0],$arr[1],$arr[2],$arr[3],$arr[4]);
                break;
                case 6:
                    $stmt->bind_param('ssssss',$arr[0],$arr[1],$arr[2],$arr[3],$arr[4],$arr[5]);
                break; 
                case 7:
                    $stmt->bind_param('sssssss',$arr[0],$arr[1],$arr[2],$arr[3],$arr[4],$arr[5],$arr[6]);
                break; 
            }
            $stmt->execute();
            return $stmt;
        }

        public function pageTitle(){
            $page=substr(basename(parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH)),0,-4);
            $title="";
            if($page=="materials"){
                $title="Materials";
            }
            else if($page=="addmaterial"){
                $title="Add material";
            }
            else if($page=="adminprofile"){
                $title="Profile";
            }
            else if($page=="addcategory"){
                $title="Add category";
            }
            else if($page=="addnewadmin"){
                $title="Add admin";
            }
            else if($page=="adminlist"){
                $title="Admin list";
            }
            else if($page=="adminlogin"){
                $title="Login";
            }
            else if($page=="editadmins"){
                $title="Edit admin";
            }
            else if($page=="editcategory"){
                $title="Edit category";
            }
            else if($page=="editmaterial"){
                $title="Edit material";
            }
            else{
                $title="CP Materials";
            }

            return $title;
        }

        /*public function closeConn(){
            if($this->conn!=null){
                $this->conn->close();
            }
        }*/

    }

?>