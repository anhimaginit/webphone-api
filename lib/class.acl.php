<?php
require_once 'class.common.php';
//require_once 'class.salesman.php';
//require_once 'class.affiliate.php';
//require_once 'class.employee.php';

class ACL extends Common{
    public function getFieldsTable(){
        $user_table = "SELECT `COLUMN_NAME`
                  FROM `INFORMATION_SCHEMA`.`COLUMNS`
                  WHERE `TABLE_SCHEMA`='dblnef5hfuazae'
                  AND `TABLE_NAME`='user' AND COLUMN_NAME <> 'u_id'";

        $result = mysqli_query($this->con,$user_table);

        $user_fileds = array();
        if($result){
            while ($row = mysqli_fetch_assoc($result)) {
                $user_fileds[] = $row['COLUMN_NAME'];
            }
        }

        $integration_table = "SELECT `COLUMN_NAME`
                  FROM `INFORMATION_SCHEMA`.`COLUMNS`
                  WHERE `TABLE_SCHEMA`='dblnef5hfuazae'
                  AND `TABLE_NAME`='integrations' AND COLUMN_NAME <> 'i_id'";

        $result = mysqli_query($this->con,$integration_table);

        $integration_fileds = array();
        if($result){
            while ($row = mysqli_fetch_assoc($result)) {
                $integration_fileds[] = $row['COLUMN_NAME'];
            }
        }

        $company_table = "SELECT `COLUMN_NAME`
                  FROM `INFORMATION_SCHEMA`.`COLUMNS`
                  WHERE `TABLE_SCHEMA`='dblnef5hfuazae'
                  AND `TABLE_NAME`='company' AND COLUMN_NAME <> 'c_id'";

        $result = mysqli_query($this->con,$company_table);

        $company_fileds = array();
        if($result){
            while ($row = mysqli_fetch_assoc($result)) {
                $company_fileds[] = $row['COLUMN_NAME'];
            }
        }

        $branch_table = "SELECT `COLUMN_NAME`
                  FROM `INFORMATION_SCHEMA`.`COLUMNS`
                  WHERE `TABLE_SCHEMA`='dblnef5hfuazae'
                  AND `TABLE_NAME`='branch' AND COLUMN_NAME <> 'b_id'";

        $result = mysqli_query($this->con,$branch_table);

        $branch_fileds = array();
        if($result){
            while ($row = mysqli_fetch_assoc($result)) {
                $branch_fileds[] = $row['COLUMN_NAME'];
            }
        }

        $assigned_integration_table = "SELECT `COLUMN_NAME`
                  FROM `INFORMATION_SCHEMA`.`COLUMNS`
                  WHERE `TABLE_SCHEMA`='dblnef5hfuazae'
                  AND `TABLE_NAME`='assigned_integration' AND COLUMN_NAME <> 'ai_id'";

        $result = mysqli_query($this->con,$assigned_integration_table);

        $assigned_integration_fileds = array();
        if($result){
            while ($row = mysqli_fetch_assoc($result)) {
                $assigned_integration_fileds[] = $row['COLUMN_NAME'];
            }
        }

        return array("assgn_itg_table"=>$assigned_integration_fileds,
        "branch_table"=>$branch_fileds,
        "company_table"=>$company_fileds,
        "integration_table"=>$integration_fileds,
        "user_table"=>$user_fileds);
    }

    //-----------------------------------------------------------
    public function create_acl($g_name,$role,$u_ids=null){
        $table = $this->getFieldsTable();
        $assgn_itg =array();
        $branch =array();
        $company =array();
        $integration =array();
        $user =array();
        $permission_table = array();
        switch ($role){
            case "super_admin":
                foreach($table["assgn_itg_table"] as $key =>$v){
                    $assgn_itg[$v] = array("view"=>true,"add"=>true,"edit"=>true);
                }
                foreach($table["branch_table"] as $key =>$v){
                    $branch[$v] = array("view"=>true,"add"=>true,"edit"=>true);
                }
                foreach($table["company_table"] as $key =>$v){
                    $company[$v] = array("view"=>true,"add"=>true,"edit"=>true);
                }
                foreach($table["integration_table"] as $key =>$v){
                    $integration[$v] = array("view"=>true,"add"=>true,"edit"=>true);
                }
                foreach($table["user_table"] as $key =>$v){
                    $user[$v] = array("view"=>true,"add"=>true,"edit"=>true);
                }

                $permission_table = array("show_asg_itg"=>array("view"=>true,"add"=>true,"edit"=>true),
                    "show_branch"=>array("view"=>true,"add"=>true,"edit"=>true),
                    "show_company"=>array("view"=>true,"add"=>true,"edit"=>true),
                    "show_itg"=>array("view"=>true,"add"=>true,"edit"=>true),
                    "show_user"=>array("view"=>true,"add"=>true,"edit"=>true));

                break;
            case "company_manager":
                foreach($table["assgn_itg_table"] as $key =>$v){
                    $assgn_itg[$v] = array("view"=>true,"add"=>true,"edit"=>true);
                }
                foreach($table["branch_table"] as $key =>$v){
                    $branch[$v] = array("view"=>true,"add"=>true,"edit"=>true);
                }
                foreach($table["company_table"] as $key =>$v){
                    $company[$v] = array("view"=>true,"add"=>true,"edit"=>true);
                }
                foreach($table["integration_table"] as $key =>$v){
                    $integration[$v] = array("view"=>true,"add"=>true,"edit"=>true);
                }
                foreach($table["user_table"] as $key =>$v){
                    $user[$v] = array("view"=>true,"add"=>true,"edit"=>true);
                }

                $permission_table = array("show_asg_itg"=>array("view"=>true,"add"=>true,"edit"=>true),
                    "show_branch"=>array("view"=>true,"add"=>true,"edit"=>true),
                    "show_company"=>array("view"=>true,"add"=>true,"edit"=>true),
                    "show_itg"=>array("view"=>true,"add"=>true,"edit"=>true),
                    "show_user"=>array("view"=>true,"add"=>true,"edit"=>true));

                break;
            case "branch_manager":
                foreach($table["assgn_itg_table"] as $key =>$v){
                    $assgn_itg[$v] = array("view"=>true,"add"=>true,"edit"=>true);
                }
                foreach($table["branch_table"] as $key =>$v){
                    $branch[$v] = array("view"=>true,"add"=>true,"edit"=>true);
                }
                foreach($table["company_table"] as $key =>$v){
                    $company[$v] = array("view"=>true,"add"=>false,"edit"=>false);
                }
                foreach($table["integration_table"] as $key =>$v){
                    $integration[$v] = array("view"=>true,"add"=>true,"edit"=>true);
                }
                foreach($table["user_table"] as $key =>$v){
                    $user[$v] = array("view"=>true,"add"=>true,"edit"=>true);
                }

                $permission_table = array("show_asg_itg"=>array("view"=>true,"add"=>true,"edit"=>true),
                    "show_branch"=>array("view"=>true,"add"=>true,"edit"=>true),
                    "show_company"=>array("view"=>true,"add"=>false,"edit"=>false),
                    "show_itg"=>array("view"=>true,"add"=>true,"edit"=>true),
                    "show_user"=>array("view"=>true,"add"=>true,"edit"=>true));

                break;
            default:
                foreach($table["assgn_itg_table"] as $key =>$v){
                    $assgn_itg[$v] = array("view"=>true,"add"=>false,"edit"=>false);
                }
                foreach($table["branch_table"] as $key =>$v){
                    $branch[$v] = array("view"=>true,"add"=>false,"edit"=>false);
                }
                foreach($table["company_table"] as $key =>$v){
                    $company[$v] = array("view"=>false,"add"=>false,"edit"=>false);
                }
                foreach($table["integration_table"] as $key =>$v){
                    $integration[$v] = array("view"=>true,"add"=>false,"edit"=>false);
                }
                foreach($table["user_table"] as $key =>$v){
                    $user[$v] = array("view"=>true,"add"=>true,"edit"=>true);
                }

                $permission_table = array("show_asg_itg"=>array("view"=>true,"add"=>false,"edit"=>false),
                    "show_branch"=>array("view"=>true,"add"=>false,"edit"=>false),
                    "show_company"=>array("view"=>true,"add"=>false,"edit"=>false),
                    "show_itg"=>array("view"=>true,"false"=>true,"edit"=>false),
                    "show_user"=>array("view"=>true,"add"=>false,"edit"=>true));

                break;
        }

        $data = array("assgn_itg_table_permission"=>$assgn_itg,
            "branch_table_permission"=>$branch,
            "company_table_permission"=>$company,
            "integration_table_permission"=>$integration,
            "user_table_permission"=>$user,
            "permission"=>$permission_table);

        $data = json_encode($data);
        $u_ids = explode(",",$u_ids);
        $u_ids = json_encode($u_ids);

        $fields="g_name,g_role,u_id, acl";
        $values ="'{$g_name}','{$role}','{$u_ids}','{$data}'";

        $insert = "INSERT INTO groups({$fields}) VALUES({$values})";
       // print_r($insert); die();

        mysqli_query($this->con,$insert);
        $g_id = mysqli_insert_id($this->con);
        if(is_numeric($g_id) && !empty($g_id)){
            return $g_id;
        }else{
            return mysqli_error($this->con);
        }

    }

    //----------------------------------------------------------
    public function get_ACL($u_id){
        $query ="select acl,g_role from groups
        where JSON_SEARCH(u_id, 'all', '{$u_id}') IS NOT NULL";

        $result = mysqli_query($this->con,$query);

        $list = array(); $roles =array();
        if($result){
            while ($row = mysqli_fetch_assoc($result)) {
                $list[] = json_decode($row["acl"],true);
                $roles[] = $row["g_role"];
            }
        }

        $role="user";
        foreach ($roles as $k=>$v){
            if($v=="super_admin"){
                $role = "super_admin";
                break;
            }elseif($v=="company_manager"){
                $role = "company_manager";
            }elseif($v=="branch_manager"){
                if($role !="company_manager"){
                    $role = "branch_manager";
                }
            }
        }

        if(count($list)>1){
            return array("acl"=>$this->processACL_again($list),"role"=>$role);
        }elseif(count($list)==1){
            return array("acl"=>$list[0],"role"=>$role);
        }else{
            $query ="select acl from groups
            where g_role = 'user' and g_name='user'";

            $result = mysqli_query($this->con,$query);

            $list = array();
            if($result){
                while ($row = mysqli_fetch_assoc($result)) {
                    $list[] = json_decode($row["acl"],true);
                }
            }

            if(count($list) >0){
                return array("acl"=>$list[0],"role"=>$role);
            }else{
                return array("acl"=>$list,"role"=>$role);
            }
        }
    }
    //----------------------------------------------------------
    public function processACL_again($acl_temp){
        if(count($acl_temp)>1){
            $acl = array();
            //acl
            $temp_0 = $acl_temp[0];
            //print_r($temp_0); die();
            for($i=1;$i<count($acl_temp);$i++){
                $diff = array_diff_key($acl_temp[$i],$temp_0);
                if(count($diff) >0) {
                    $temp_0 = array_merge($temp_0,$diff);
                }
            }

            foreach ($temp_0 as $t_key_0=>$t_value_0){
                //print_r($t_key_0."---------");
                for($i=1; $i<count($acl_temp);$i++){
                    $t_value_i = $acl_temp[$i][$t_key_0];
                    if(count($t_value_0)>0 && count($t_value_i)>0){
                        $diff = array_diff_key($t_value_i,$t_value_0);
                        if(count($diff) >0) {
                            $t_value_0 = array_merge($t_value_0,$diff);
                        }

                        foreach($t_value_0 as $k0=>$v0){
                            //print_r($k0);echo "=";
                            //print_r($t_value_i[$k0]);echo "-----";
                            foreach($v0 as $v0_k=>$v0_v){
                                if(isset($t_value_i[$k0][$v0_k])){
                                    $v0[$v0_k] = $t_value_0[$k0][$v0_k] || $t_value_i[$k0][$v0_k];
                                }else{
                                    $v0[$v0_k] = $t_value_0[$k0][$v0_k];
                                }
                            }
                            $t_value_0[$k0] = $v0;
                        }
                    }elseif(count($t_value_0) == 0 && count($t_value_i)>0){
                        $t_value_0 = $t_value_i;
                    }

                }
                $acl[$t_key_0] =$t_value_0;

            }

            return $acl;
           //print_r($acl); die();
        }
    }

    //----------------------------------------------------------
    public function roles(){
        $query ="select * from roles";

        $result = mysqli_query($this->con,$query);
        $list = array();
        if($result){
            while ($row = mysqli_fetch_assoc($result)) {
                $list[] = $row;
            }
        }

        return $list;
    }

  //////////////////////////////
}