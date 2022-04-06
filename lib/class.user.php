<?php
require_once 'class.common.php';
//require_once 'class.salesman.php';
//require_once 'class.affiliate.php';
//require_once 'class.employee.php';

class User extends Common{
    public function search_users($u_name){
        $query ="select u_id,concat(u_fname,' ',u_lname) as user_name from user
        where u_fname like '%{$u_name}%' or u_lname like '%{$u_name}%'";

        $result = mysqli_query($this->con,$query);
        $list = array();
        if($result){
            while ($row = mysqli_fetch_assoc($result)) {
                $list[] = $row;
            }
        }

        return $list;
    }

    //-----------------------------------------------------------

  //////////////////////////////
}