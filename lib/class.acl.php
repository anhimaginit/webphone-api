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

                $permission_table = array("show_asg_itg"=>true,"show_branch"=>true,
                "show_company"=>true,"show_itg"=>true,"show_user"=>true);

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

                $permission_table = array("show_asg_itg"=>true,"show_branch"=>true,
                    "show_company"=>true,"show_itg"=>true,"show_user"=>true);

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

                $permission_table = array("show_asg_itg"=>true,"show_branch"=>true,
                    "show_company"=>true,"show_itg"=>true,"show_user"=>true);

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

                $permission_table = array("show_asg_itg"=>true,"show_branch"=>true,
                    "show_company"=>true,"show_itg"=>true,"show_user"=>true);

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


        return array("results"=>$list,"row_cnt"=>$row_cnt);
    }

//----------------------------------------------------------
    public function processACL_again($acl_temp){
        if(count($acl_temp)>1){
            //acl
            $temp1 = $acl_temp[0];
            $assgn_itg_table_permission_0 = array();
            if(isset($temp1['assgn_itg_table_permission'])){
                $assgn_itg_table_permission_0 = $temp1['assgn_itg_table_permission'] ;
            }
            $branch_table_permission_0 =array();
            if(isset($temp1['branch_table_permission'])){
                $branch_table_permission_0 =$temp1['branch_table_permission'] ;
            }
            $company_table_permission_0 =array();
            if(isset($temp1['company_table_permission'] )){
                $company_table_permission_0 =$temp1['company_table_permission'] ;
            }
            $integration_table_permission_0 =array();
            if(isset($temp1['integration_table_permission'])){
                $integration_table_permission_0 =$temp1['integration_table_permission'] ;
            }
            $user_table_permission_0 =array();
            if(isset($temp1['user_table_permission'])){
                $user_table_permission_0 =$temp1['user_table_permission'] ;
            }
            $permission_0 =array();
            if(isset($temp1['permission'])){
                $permission_0 =$temp1['permission'];
            }
            //level
            $level_0= $acl_temp[0]['level'] ;
            $unit_0= $acl_temp[0]['unit'] ;
            for($i=1;$i<count($acl_temp);$i++){
                $temp2 = $acl_temp[$i]['acl_rules'][0];
                $level_0= $acl_temp[$i]['level'] ;
                //process claim acl
                $ClaimForm_i =array();
                if(isset($temp2['ClaimForm'])){
                    $ClaimForm_i =$temp2['ClaimForm'] ;
                }

                if(count($ClaimForm_0)>0 && count($ClaimForm_i)>0){
                    $diff = array_diff_key($ClaimForm_i,$ClaimForm_0);
                    if(count($diff) >0) {
                        $ClaimForm_0 = array_merge($ClaimForm_0,$diff);
                    }

                    foreach($ClaimForm_0 as $k0=>$v0){
                        //print_r($k0);echo "=";
                        //print_r($ClaimForm_i[$k0]);echo "-----";
                        foreach($v0 as $v0_k=>$v0_v){
                            if($v0_k!="display"){
                                if(isset($ClaimForm_i[$k0][$v0_k])){
                                    $v0[$v0_k] = false || $ClaimForm_i[$k0][$v0_k];
                                }else{
                                    $v0[$v0_k] = false;
                                }
                            }
                        }
                        $ClaimForm_0[$k0] = $v0;
                    }
                }else{
                    $ClaimForm_0 = $ClaimForm_i;
                }

                //--
            }

            $rtn=  Array
            (
                Array
                (
                    'unit' => $unit_0,
                    'level' => $level_0,
                    'acl_rules' => Array
                    (
                        Array
                        (
                            'ClaimForm' => $ClaimForm_0,
                            'OrderForm' => $OrderForm_0,
                            'ContactForm' => $ContactForm_0,
                            'InvoiceForm' => $InvoiceForm_0,
                            'ProductForm' => $ProductForm_0,
                            'WarrantyForm' => $WarrantyForm_0,
                            'Dashboard' => $Dashboard_0,
                            'GroupForm' => $GroupForm_0,
                            'Navigation'=>$Navigation_0,
                            'CompanyForm'=>$CompanyForm_0,
                            'BillingTemplateForm'=>$BillingTemplateForm_0,
                            'DiscountForm'=>$DiscountForm_0,
                            'SettingForm'=>$SettingForm_0,
                            'PermissionForm'=>$PermissionForm_0,
                            'TaskForm'=>$TaskForm_0,
                            'ControlListForm'=>$ControlListForm_0
                        )
                    ),
                    'group_name' => ''
                )
            );

            if(count($ClaimForm_0)<1) {
                unset($rtn[0]['acl_rules'][0]['ClaimForm']);
            }

            if(count($OrderForm_0)<1) {
                unset($rtn[0]['acl_rules'][0]['OrderForm']);
            }

            if(count($ContactForm_0)<1) {
                unset($rtn[0]['acl_rules'][0]['ContactForm']);
            }

            if(count($InvoiceForm_0)<1) {
                unset($rtn[0]['acl_rules'][0]['InvoiceForm']);
            }

            if(count($ProductForm_0)<1) {
                unset($rtn[0]['acl_rules'][0]['ProductForm']);
            }

            if(count($WarrantyForm_0)<1) {
                unset($rtn[0]['acl_rules'][0]['WarrantyForm']);
            }

            if(count($CompanyForm_0)<1) {
                unset($rtn[0]['acl_rules'][0]['CompanyForm']);
            }

            if(count($Dashboard_0)<1) {
                unset($rtn[0]['acl_rules'][0]['Dashboard']);
            }

            if(count($GroupForm_0)<1) {
                unset($rtn[0]['acl_rules'][0]['GroupForm']);
            }

            if(count($Navigation_0)<1) {
                unset($rtn[0]['acl_rules'][0]['Navigation']);
            }

            if(count($BillingTemplateForm_0)<1) {
                unset($rtn[0]['acl_rules'][0]['BillingTemplateForm']);
            }
            if(count($DiscountForm_0)<1) {
                unset($rtn[0]['acl_rules'][0]['DiscountForm']);
            }
            if(count($SettingForm_0)<1) {
                unset($rtn[0]['acl_rules'][0]['SettingForm']);
            }
            if(count($PermissionForm_0)<1) {
                unset($rtn[0]['acl_rules'][0]['PermissionForm']);
            }

            if(count($TaskForm_0)<1) {
                unset($rtn[0]['acl_rules'][0]['TaskForm']);
            }
            if(count($ControlListForm_0)<1) {
                unset($rtn[0]['acl_rules'][0]['ControlListForm']);
            }
            return $rtn;

        }else{
            return array();
        }
    }

  //////////////////////////////
}