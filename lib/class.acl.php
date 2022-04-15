<?php
require_once 'class.common.php';
//require_once 'class.salesman.php';
//require_once 'class.affiliate.php';
//require_once 'class.employee.php';

class ACL extends Common{
    public function getFieldsTable(){
        $query = "SELECT table_name
        FROM information_schema.tables
        WHERE table_type='BASE TABLE'
        AND table_schema='dblnef5hfuazae' and table_name <> 'call_log' and table_name <> 'roles'";

        $result = mysqli_query($this->con,$query);

        $tables = array();
        if($result){
            while ($row = mysqli_fetch_assoc($result)) {
                $tables[] = $row['table_name'];
            }
        }
        //print_r($tables); die();
        $all_tables_fields =array();

        foreach($tables as $item){
            $query = "SELECT `COLUMN_NAME`
                  FROM `INFORMATION_SCHEMA`.`COLUMNS`
                  WHERE `TABLE_SCHEMA`='dblnef5hfuazae'
                  AND `TABLE_NAME`= '{$item}'";
            $result = mysqli_query($this->con,$query);

            $fileds = array();
            if($result){
                while ($row = mysqli_fetch_assoc($result)) {
                    $fileds[] = $row['COLUMN_NAME'];
                }
            }

            $all_tables_fields[$item] = $fileds;

        }

        /*
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
        "user_table"=>$user_fileds);*/

        return array("tables"=>$tables,"table_field"=>$all_tables_fields) ;
    }

    //-----------------------------------------------------------
    public function create_group_default($g_id,$g_name,$g_role,$u_id=null,$u_name=null){
        $table = $this->getFieldsTable();
        $assgn_itg =array();
        $branch =array();
        $company =array();
        $integration =array();
        $user =array();
        $permission_table=array();
        $data = array();
        $data['permission']="";

        //print_r($table['tables']); die();
        switch ($g_role){
            case "super_admin":
                $permission_table['ACL'] =array("view"=>true,"add"=>false,"edit"=>true);
                foreach($table['tables'] as $it){
                    $permission_table[$it] =array("view"=>true,"add"=>true,"edit"=>true);
                    $it_arr = array();
                    foreach($table["table_field"][$it] as $key =>$v){
                        $it_arr[$v] = array("view"=>true,"add"=>true,"edit"=>true);
                    }

                    $data[$it]=$it_arr;
                }

                break;
            case "company_manager":
                $permission_table['ACL'] =array("view"=>true,"add"=>false,"edit"=>true);
                foreach($table['tables'] as $it){
                    $permission_table[$it] =array("view"=>true,"add"=>true,"edit"=>true);
                    $it_arr = array();
                    foreach($table["table_field"][$it] as $key =>$v){
                        $it_arr[$v] = array("view"=>true,"add"=>true,"edit"=>true);
                    }

                    $data[$it]=$it_arr;
                }
                break;
            case "branch_manager":
                $permission_table['ACL'] =array("view"=>false,"add"=>false,"edit"=>false);
                foreach($table['tables'] as $it){
                    if($it !="company"){
                        $permission_table[$it] =array("view"=>true,"add"=>true,"edit"=>true);
                    }else{
                        $permission_table[$it] =array("view"=>true,"add"=>false,"edit"=>false);
                    }

                    $it_arr = array();
                    foreach($table["table_field"][$it] as $key =>$v){
                        if($it !="company"){
                            $it_arr[$v] = array("view"=>true,"add"=>true,"edit"=>true);
                        }else{
                            $it_arr[$v] = array("view"=>true,"add"=>false,"edit"=>false);
                        }

                    }

                    $data[$it]=$it_arr;
                }

                break;
            default:
                $permission_table['ACL'] =array("view"=>false,"add"=>false,"edit"=>false);
                foreach($table['tables'] as $it){
                    if($it !="user"){
                        $permission_table[$it] =array("view"=>true,"add"=>false,"edit"=>false);
                    }else{
                        $permission_table[$it] =array("view"=>true,"add"=>true,"edit"=>true);
                    }

                    $it_arr = array();
                    foreach($table["table_field"][$it] as $key =>$v){
                        if($it !="user"){
                            $it_arr[$v] = array("view"=>true,"add"=>false,"edit"=>false);
                        }else{
                            $it_arr[$v] = array("view"=>true,"add"=>true,"edit"=>true);
                        }

                    }

                    $data[$it]=$it_arr;
                }

                break;
        }

        $data['permission']=$permission_table;

       // print_r($data); die();

        $data = json_encode($data);
        $u_id = explode(",",$u_id);
        $u_id = json_encode($u_id);

        if($g_id==""){
            $fields="g_name,g_role,u_id,u_name,acl";
            $values ="'{$g_name}','{$g_role}','{$u_id}','{$u_name}','{$data}'";

            $insert = "INSERT INTO groups({$fields}) VALUES({$values})";

            mysqli_query($this->con,$insert);
            $g_id_new = mysqli_insert_id($this->con);

            if(is_numeric($g_id_new) && !empty($g_id_new)){
                return array("Save"=>true,"ERROR"=>"","g_id"=>$g_id_new);
            }else{
                return array("Save"=>false,"ERROR"=>mysqli_error($this->con),"g_id"=>"");
            }
        }else{
            $updateCommand = "UPDATE `groups`
                SET g_name = '{$g_name}',
                 u_id = '{$u_id}',
                 u_name = '{$u_name}'";

            $g_role_old = $this->return_id("Select * from groups where `g_id` ='{$g_id}' AND g_id<>'' limit 1",'g_role');
            if($g_role !=$g_role_old){
                $updateCommand =$updateCommand.",g_role = '{$g_role}'".",
                 acl = '{$data}'";
            }

            $updateCommand =$updateCommand." where g_id = '{$g_id}'";

            $update = mysqli_query($this->con,$updateCommand);

            if($update){
                return array("Update"=>true,"ERROR"=>"");
            }else{
                return array("Update"=>false,"ERROR"=>mysqli_error($this->con));
            }
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
            where g_role = 'user' and g_name='user_default'";

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
    public function updateACL($g_id,$u_id,$acl_update){
       //$rsl = $this->get_ACL($u_id);
       //$acl_permission = $rsl['acl'];
        //groups($g_id,"","","","",1);
       //$rsl =$this->groups($g_id,"","","","",1);
        //print_r($rsl); die();
        //$acl_grp =array();
        //if(count($rsl['results']) >0) $acl_grp = $rsl['results'][0]['acl'];

        //check fields were permited
        $acl = array();

        foreach ($acl_update as $t_key_0=>$t_value_0){
            foreach($t_value_0 as $k0=>$v0){
                foreach($v0 as $v0_k=>$v0_v){
                    //convert "true" =>true,"false"=>false
                    if($v0_v==="true"){
                        $v0[$v0_k] = true;
                    }elseif($v0_v==="false"){
                        $v0[$v0_k] = false;
                    }
                }

                $t_value_0[$k0] = $v0;
            }
            /*
            if(isset($acl_permission[$t_key_0])){
                $t_value_i = $acl_permission[$t_key_0];
                if(count($t_value_0)>0 && count($t_value_i)>0){
                    foreach($t_value_0 as $k0=>$v0){
                        foreach($v0 as $v0_k=>$v0_v){
                            //convert "true" =>true,"false"=>false
                            if($v0_v==="true"){
                                $v0[$v0_k] = true;
                            }elseif($v0_v==="false"){
                                $v0[$v0_k] = false;
                            }

                            if(isset($t_value_i[$k0][$v0_k])){
                                if($t_value_i[$k0][$v0_k]==""){
                                    if(isset($acl_grp[$t_key_0][$k0][$v0_k])){
                                        $v0[$v0_k] = $acl_grp[$t_key_0][$k0][$v0_k];
                                    }else{
                                        //$v0[$v0_k] = false;
                                    }

                                }

                            }else{
                                //$v0[$v0_k] = false;
                            }
                        }
                        $t_value_0[$k0] = $v0;
                    }
                }elseif(count($t_value_0) == 0 && count($t_value_i)>0){
                    //$t_value_0 = $t_value_i;
                }
            }else{
                foreach($t_value_0 as $k0=>$v0){
                    foreach($v0 as $v0_k=>$v0_v){
                        //convert "true" =>true,"false"=>false
                        if($v0_v==="true"){
                            $v0[$v0_k] = true;
                        }elseif($v0_v==="false"){
                            $v0[$v0_k] = false;
                        }
                    }

                    $t_value_0[$k0] = $v0;
                }
                ///
            }
            */
            $acl[$t_key_0] =$t_value_0;
        }

        //print_r($acl); die();
        $acl = json_encode($acl);

        $update ="update `groups`
                  SET  acl = '{$acl}'
                 where g_id = '{$g_id}'";

        $update = mysqli_query($this->con,$update);

        if($update){
            return array("Update"=>true,"ERROR"=>"");
        }else{
            return array("Update"=>false,"ERROR"=>mysqli_error($this->con));
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

    //----------------------------------------------------------
    public function groups($g_id=null,$limit=null,$offset=null,$g_name=null,$member=null,$all=null){
        if($all==1){
            $query = "select * from groups";
            $query_count = "select * from groups";
            $where =" where";
        }else{
            $query = "select * from groups where g_name <> 'super_admin_default' and g_name <> 'user_default'";
            $query_count = "select * from groups where g_name <> 'super_admin_default' and g_name <> 'user_default'";
            $where=" and";
        }

        if($g_name !=''){
            $query .= $where." g_name like '%{$g_name}%'";
            $query_count .= $where." g_name like '%{$g_name}%'";
            $where=" and";
        }

        if($member !=''){
            $query .= $where." u_name like '%{$member}%'";
            $query_count .=$where. " u_name like '%{$member}%'";
            $where=" and";
        }

        if($g_id !=''){
            $query .= $where." g_id = '{$g_id}'";
            $query_count .= $where." g_id = '{$g_id}'";
        }

        $query .= " order by g_id DESC";
        if($limit !=''){
            $query.= " LIMIT {$limit} ";
        }
        if($offset !=''){
            $query.= " OFFSET {$offset} ";
        }
        //print_r($query); die();
        $result = mysqli_query($this->con,$query);

        $list = array();
        if($result){
            while ($row = mysqli_fetch_assoc($result)) {
                $row['acl'] = json_decode($row['acl'],true);
                $list[] = $row;
            }
        }

        $result = mysqli_query($this->con,$query_count);
        //die($query);
        $row_cnt = mysqli_num_rows($result);

        return array("results"=>$list,"row_cnt"=>$row_cnt);
   }

    //----------------------------------------------------------
    public function getGrp_gID($g_id){
        $query = "select * from groups where g_id = '{$g_id}'";
        $result = mysqli_query($this->con,$query);

        $list = array();
        $acl_update = array();
        if($result){
            while ($row = mysqli_fetch_assoc($result)) {
                $row['acl'] = json_decode($row['acl'],true);
                $acl_update = $row['acl'];
                $list[] = $row;
            }
        }

        if(count($acl_update)>0){
            $new_acl = $this->acl_default();
            //print_r($new_acl);die();
            //merge or delete field
            $diff = array_diff_key($new_acl,$acl_update);
            if(count($diff) >0) {
                $acl_update = array_merge($acl_update,$diff);
            }
            //delete
            $diff = array_diff_key($acl_update,$new_acl);
            if(count($diff) >0) {
                foreach($diff as $k=>$v){
                    unset($acl_update[$k]);
                }
            }

            $acl = array();

            foreach ($acl_update as $t_key_0=>$t_value_0){
                //print_r($t_key_0."---------");
                $t_value_i = $new_acl[$t_key_0];
                if(count($t_value_0)>0 && count($t_value_i)>0){
                    //find  key in a1 differently a2
                    $diff = array_diff_key($t_value_i,$t_value_0);
                    if(count($diff) >0) {
                        $t_value_0 = array_merge($t_value_0,$diff);
                    }
                    //find  key in a2 differently a1
                    $diff = array_diff_key($t_value_0,$t_value_i);
                    if(count($diff) >0) {
                        foreach($diff as $k0=>$v0){
                            unset($t_value_0[$k0]);
                        }
                    }

                }elseif(count($t_value_0) == 0 && count($t_value_i)>0){
                    $t_value_0 = $t_value_i;
                }

                $acl[$t_key_0] =$t_value_0;

            }

            $list[0]['acl'] =$acl;
        }



        return array("results"=>$list);
    }

    //-----------------------------------------------------------
    public function acl_default(){
        $table = $this->getFieldsTable();
        $permission_table=array();

        $data = array();
        $data['permission']="";
        $permission_table['ACL'] =array("view"=>false,"add"=>false,"edit"=>false);
        foreach($table['tables'] as $it){
            $permission_table[$it] =array("view"=>false,"add"=>false,"edit"=>false);
            $it_arr = array();
            foreach($table["table_field"][$it] as $key =>$v){
                $it_arr[$v] = array("view"=>false,"add"=>false,"edit"=>false);
            }

            $data[$it]=$it_arr;
        }

        $data['permission']=$permission_table;

        //print_r($data); die();
        return $data;
    }

    //----------------------------------------------------------
    public function deleteGroup_gID($g_id){
        $query ="delete from groups where g_id = '{$g_id}'";
        $delete = mysqli_query($this->con,$query);
        if($delete){
            return true;
        } else {
            return false;
        }

    }

    //----------------------------------------------------------
    public function groupsByRole($g_role,$g_name=null,$all=null){
        if($all==1){
            $query ="select * from groups where g_role = '{$g_role}'";
        }else{
            $query ="select * from groups where g_role = '{$g_role}' and g_name <> 'super_admin_default' and g_name <> 'user_default'";
        }

        if($g_name !=""){
            $query .= " and g_name like '%{$g_name}%'";
        }
        //die($query);
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