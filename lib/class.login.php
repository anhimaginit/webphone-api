<?php
require_once 'class.common.php';
require_once 'class.acl.php';
//require_once 'class.affiliate.php';
//require_once 'class.employee.php';

class Login extends Common{
    public function validate_register_fields($first_name,$last_name){
        $error = false;
        $errorMsg = "";

        if(!$error && empty($first_name)){
            $error = true;
            $errorMsg = "Address1 is required.";
        }

        if(!$error && empty($last_name)){
            $error = true;
            $errorMsg = "name is required.";
        }

        return array('error'=>$error,'errorMsg'=>$errorMsg);
    }

   //-----------------------------------------------------------
    public function loginEmailPass($u_email,$u_password){

        $query ="Select * from user_short as u
        Where u.u_email ='{$u_email}' AND u.u_password='$u_password' LIMIT 1";

        $result = mysqli_query($this->con,$query);
        $list = array();
        $u_id ='';
        if($result){
            while ($row = mysqli_fetch_assoc($result)) {
                $list[] = $row;
                $u_id = $row["u_id"];
            }
        }

        if($u_id !=""){
            $acl_ob = new ACL();
            $acl_temp = $acl_ob->get_ACL($u_id);
            $acl = $acl_temp['acl'];
            //print_r($acl); die();
            $role = $acl_temp['role'];
           return array('response'=>$list,'acl'=>$acl,'role'=>$role,'ERROR'=>'');
        }else{
            return array('response'=>array(),'acl'=>'','role'=>'','ERROR'=>'Wrong email or password');
        }

        return $list;
    }
  //////////////////////////////
}