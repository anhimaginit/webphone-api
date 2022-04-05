<?php
require_once 'class.common.php';
//require_once 'class.salesman.php';
//require_once 'class.affiliate.php';
//require_once 'class.employee.php';

class Company extends Common{
    public function companies($u_type,$u_id,$limit,$offset,$text_search){
        if($u_type=="company_manager"){
            $query = "select * from company as c
            where c.c_id in (
            select b.c_id from branch as b
            where u_idContact ='{$u_id}'
            )";

            $query_count = "select * from company as c
            where c.c_id in (
            select b.c_id from branch as b
            where u_idContact ='{$u_id}'
            )";

            if($text_search !=''){
                $query .= " and (c_name like '%{$text_search}%' or c_phone like '%{$text_search}%' or c_website like '%{$text_search}%' )";
                $query_count .= " and (c_name like '%{$text_search}%' or c_phone like '%{$text_search}%' or c_website like '%{$text_search}%' )";
            }
        }elseif($u_type=="branch_manager"){
            $query = "select * from company as c
            where c.c_id = (
            select b.c_id from branch as b
            where u_idContact ='{$u_id} limit 1'
            )";

            $query_count = "select * from company as c
            where c.c_id = (
            select b.c_id from branch as b
            where u_idContact ='{$u_id} limit 1'
            )";

            if($text_search !=''){
                $query .= " and (c_name like '%{$text_search}%' or c_phone like '%{$text_search}%' or c_website like '%{$text_search}%' )";
                $query_count .= " and (c_name like '%{$text_search}%' or c_phone like '%{$text_search}%' or c_website like '%{$text_search}%' )";
            }
        }else{
            $query = "select * from company as c";
            $query_count = "select * from company as c";

            if($text_search !=''){
                $query .= "where c_name like '%{$text_search}%' or c_phone like '%{$text_search}%' or c_website like '%{$text_search}%'";
                $query_count .= " where c_name like '%{$text_search}%' or c_phone like '%{$text_search}%' or c_website like '%{$text_search}%'";
            }
        }

        $query .= " order by c.c_id DESC";
        if($limit !=''){
            $query.= " LIMIT {$limit} ";
        }
        if($offset !=''){
            $query.= " OFFSET {$offset} ";
        }

        $result = mysqli_query($this->con,$query);

        $list = array();
        if($result){
            if($u_type=="branch_manager"){
                while ($row = mysqli_fetch_assoc($result)) {
                    $row["branch"] = $this->branch_uid($u_id);
                    $list[] = $row;
                }
            }else{
                while ($row = mysqli_fetch_assoc($result)) {
                    $row["branch"] = $this->branch_cid($row["c_id"]);
                    $list[] = $row;
                }
            }

        }

        $result = mysqli_query($this->con,$query_count);
        //die($query);
        $row_cnt = mysqli_num_rows($result);

        return array("results"=>$list,"row_cnt"=>$row_cnt);
    }

    //-----------------------------------------------------------
    public function branch_cid($c_id){
        $query = "select * from branch_short where c_id ='{$c_id}'";

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
    public function branch_uid($u_id,$text_search=null){
        $query = "select * from branch_short where u_idContact ='{$u_id}'";

        if($text_search !=''){
            $query .= " and (b_name like '%{$text_search}%' or b_name like '%{$text_search}%')";
        }

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
    public function company_shorts_bid($b_ids){
        $query = "select * from company_short where b_id in ({$b_ids}) order by c_id";

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
    public function branch_bid($b_id){
        $query ="select * from branch_short
            where b_id ='{$b_id}'";

        $result = mysqli_query($this->con,$query);

        $list = array();
        if($result){
            while ($row = mysqli_fetch_assoc($result)) {
                $list[] = $row;
            }
        }

        return array("results"=>$list);
    }

    //-----------------------------------------------------------
    public function branch_bids($b_ids){
        $query ="select * from branch_short
            where b_id in ({$b_ids}) order by b_id";

        $result = mysqli_query($this->con,$query);

        $list = array();
        if($result){
            while ($row = mysqli_fetch_assoc($result)) {
                $list[] = $row;
            }
        }

        return array("results"=>$list);
    }

  //////////////////////////////
}