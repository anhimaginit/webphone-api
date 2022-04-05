<?php
require_once 'db.php';

class Common extends dbConnect
{
    const API_FOLDER_NAME = "api";
    protected $users_child = array();
    protected $parent_repeat = array();
    //----------------------------------------------------------
    public function protect($dirty_string)
    {
        $clean_string =  mysqli_real_escape_string($this->con,$dirty_string);
        return $clean_string;
    }
    //----------------------------------------------------------
    public function checkExists($sqlText)
    {
       $check = mysqli_query($this->con,$sqlText);
        $row = mysqli_fetch_row($check);
        if ($row[0] > 0)
            return true;
        else
            return false; 
    }

    //----------------------------------------------------------
    public function return_id($sqlText,$field)
    {
        $result = mysqli_query($this->con,$sqlText);
        $id = '';
        if($result){
            while ($row = mysqli_fetch_assoc($result)) {
                $id = $row[$field];
            }
        }
        return $id;
    }

    //----------------------------------------------------------
    public function checkExisting($sqlText)
    {
        $check = mysqli_query($this->con,$sqlText);
        $row = mysqli_fetch_row($check);

        if ($row[0] > 0)
            return 1;
        else
            return "";
    }
    //----------------------------------------------------------
    public function existRow($sqlText){
        $result = mysqli_query($this->con,$sqlText);
        $exists = false;
        if($result){
            while ($row = mysqli_fetch_assoc($result)) {
                $exists = true;
                break;
            }            
        }
        return $exists;
    }
    //----------------------------------------------------------
    public function getRow($sqlText){ 
        //mysql_query("SET NAMES 'utf8'");  
        $result = mysqli_query($this->con,$sqlText);
        $info = array();
        if($result){
            while ($row = mysqli_fetch_assoc($result)) {
                $info = $row;
                break;
            }            
        }
        return $info;
    }

    //----------------------------------------------------------
    public function totalRecords($sqlText)
    {
        $result = mysqli_query($this->con,$sqlText);
        return mysqli_num_rows($result);
        /*$row = mysqli_fetch_row($result);

        $value=0;
        if ($row[0] > 0){
            $value =$row[0];
        }*/
    }
    //----------------------------------------------------------
    public function getValue($sqlText,$defaulValue)
    {
        $result = mysqli_query($this->con,$sqlText);
        $value = $defaulValue;
        if($result){
            while ($row = mysqli_fetch_assoc($result)) {
                foreach($row as $key=>$v){
                    $value = $v;
                    break;                    
                }
                break;
            }            
        }
        if(empty($value)){
            $value = $defaulValue;
        }
        return $value;        
    }

    //----------------------------------------------------------
    public function getList($sqlText){
        $result = mysqli_query($this->con,$sqlText);
        $list = array();
        if($result){
            while ($row = mysqli_fetch_assoc($result)) {
                $list[] = $row;
            }            
        }
        return $list;
    }


    //----------------------------------------------------------
    public function columnsFilterOr($colums,$value)
    {
        $criteria = "";
        $i=0;
        foreach($colums as $item){
            if($i==0){
                $criteria .= " {$item} LIKE '%{$value}%' ";
            }else{
                $criteria .= " OR {$item} LIKE '%{$value}%' ";
            }
            $i++;
        }
        return $criteria;
    }

    //----------------------------------------------------------
    public function columnFilterAnd($criteriaData)
    {
         $criteria = "";
         foreach($criteriaData as $key=>$value){
             $criteria .= empty($criteria) ? "" : " AND ";
             $criteria .= " ({$key} LIKE '%{$value}%') ";
         }

         return $criteria;
    }

   //----------------------------------------------------------
    public function basicAuth($vallue){
        if($vallue=='d2FycmFudHlfYnJhbmRvbl9wcm9qZWN0'){
            return true;

        }else{
            return false;
        }
    }

    //------------------------------------------------------------
    public function  is_Date($date){
        $m_temp = explode(" ",$date);
        if(isset($m_temp[1])){
            $format = 'Y-m-d H:i:s';
        }else{
            $format = 'Y-m-d';
        }

        $d = DateTime::createFromFormat($format, $date);
        if($d && $d->format($format) === $date){
            return $date;
        }else{
            return "";
        }
    }

    //----------------------------------------------------------
    public function close_conn(){
        mysqli_close($this->con);
    }

    //----------------------------------------------------------
    public function mail_to($from,$receiver_name,$email,$subject,$content, $id_tracking=null,$attachment=null, $file_name=null){
        date_default_timezone_set('Etc/UTC');

        //$Ob_manager = new EmailAdress();
        //$api_path = $Ob_manager->api_path;
        //require 'PHPMailer-5.2.27/PHPMailerAutoload.php';
        $body = '';
        if(!empty($id_tracking)){
            //$body .= "<img src='".$api_path."/trackonline.php?id=".$id_tracking."' border='0' width='1' height='1' alt=''>";
        }
        $body.=$content;
        $mail = new PHPMailer;
        $mail->isSMTP();
        $mail->Host = 'smtp.mandrillapp.com';
        $mail->Port = 587;
        $mail->SMTPSecure = 'tls';////Set the encryption system to use - ssl (deprecated) or tls
        $mail->SMTPAuth = true;
        $mail->Username = "marketing@freedomhw.com";
        $mail->Password = "aL9zuKiIRK44voh1Jx0hsA";
        $mail->setFrom("marketing@freedomhw.com", $from);
        $mail->addAddress($email, $receiver_name);

        $mail->Subject = $subject;

        if(!empty($attachment)){
            //$mail->addAttachment($attachment, $file_name);
        }

        $tempDate = date("Y-m-d H:i:s");

        //$mail->Body    = $link;
        $mail->IsHTML(true); // send as HTML
        $mail->MsgHTML($body);

        if (!$mail->send()) {
            //unset($Ob_manager);
            return $mail->ErrorInfo;
        } else {
            //unset($Ob_manager);
            return 1;
        }

    }
   //-------------------------------------------------------
    public function remove_dot_inStr($value){
        $pattern = '/\./i';
        return preg_replace($pattern, '', $value);
    }

    //-------------------------------------------------------
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

        $table = array("assgn_itg_table"=>$assigned_integration_fileds,
            "branch_table"=>$branch_fileds,
            "company_table"=>$company_fileds,
            "integration_table"=>$integration_fileds,
            "user_table"=>$user_fileds);

        foreach($table as $key =>$value){
            foreach ($value as $k=>$v){
              print_r($v);
            }
        }
        //die();
    }

    //////////
}  
