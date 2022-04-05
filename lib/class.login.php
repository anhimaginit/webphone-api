<?php
require_once 'class.common.php';
//require_once 'class.salesman.php';
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
    public function addRgister($MiddleName,$FirstName,
                               $FamilyName,$Language,$Email,$PrimaryPhoneNumber,
                               $Password,$UserTypeID,$UniqueID,$UID,$BirthDate,$IdentificationNumber){

        $Password =$Password.'Khung';
        $Password = hash('sha256', $Password);
        $Password =base64_encode($Password);

        $PrimaryPhoneNumber = preg_replace('/\++|\s+|-+|\(+|\)+/', '',$PrimaryPhoneNumber);
        $PrimaryPhoneNumber =trim($PrimaryPhoneNumber);

        $CreatedDate= date("Y-m-d H:i:s");
        $DisplayName = "";
        if($FamilyName !="") $DisplayName= $FamilyName;
        if($MiddleName !="") $DisplayName= $DisplayName." ".$MiddleName;
        if($FirstName !="") $DisplayName= $DisplayName." ".$FirstName;

        $fields = "MiddleName,FirstName,FamilyName,Language,
        Email,PrimaryPhoneNumber,Password,UserType,UniqueID,
        UID,DisplayName,IdentificationNumber";

        $values = "'{$MiddleName}','{$FirstName}','{$FamilyName}','{$Language}',
                '{$Email}','{$PrimaryPhoneNumber}','{$Password}','{$UserTypeID}','{$UniqueID}',
                '{$UID}','{$DisplayName}','{$IdentificationNumber}'";

        if($BirthDate !=''){
            $fields = $fields.",BirthDate";
            $values = $values.",'{$BirthDate}'";
        }

        $command = "INSERT INTO User ({$fields}) VALUES({$values})";

        $selectCommand ="SELECT COUNT(*) AS NUM FROM User WHERE  `PrimaryPhoneNumber` ='{$PrimaryPhoneNumber}' AND
                                `PrimaryPhoneNumber`<>''";
        //if($this->checkExists($selectCommand)) return array('userID'=>'','UserReg'=>'The phone is used');

        $selectCommand1 ="SELECT COUNT(*) AS NUM FROM User WHERE  `Email` ='{$Email}' AND
                                 `Email`<>''";
        if($this->checkExists($selectCommand1)) return array('userID'=>'','UserReg'=>'The email is used');

        mysqli_query($this->con,$command);
        $userID = mysqli_insert_id($this->con);
        if(is_numeric($userID) && !empty($userID)){
            //update field Guardian
            $User_id = $this->return_id("Select * from User where `UniqueID` ='{$Guardian_UniqueID}' AND UniqueID<>'' limit 1",'User_id');
            if($User_id!=''){
                //$field_assign_value,field_update,table, condition_field,value_compare
                $this->update_field($User_id,'Guardian','User', 'userID',$userID);
            }
            return array('userID'=>$userID,'UserReg'=>'');
        }else{
            $err = mysqli_error($this->con);
            return array('userID'=>'','UserReg'=>$err);
        }



        /*
        if(is_numeric($userID) && !empty($userID)){
            //create history
            $fields = "PatientIdentification,CreatedDate";
            $values = "'{$userID}','{$CreatedDate}'";

            $command = "INSERT INTO History ({$fields}) VALUES({$values})";
            mysqli_query($this->con,$command);
            return array('userID'=>$userID,'UserReg'=>'');
        }else{
            $err = mysqli_error($this->con);
            return array('userID'=>'','UserReg'=>$err);
        }*/

    }
   //-----------------------------------------------------------
    public function loginEmailPass($u_email,$u_password){
        /*$query ="Select * from user as u
                 left join UserType AS ut on ut.UserType_ID =u.UserTypeID
                 Where u.Email ='{$Email}' AND u.Password='$Password' LIMIT 1";
        */
        //$u_email = 'brandon@at1ts.com'; $u_password='19fe20dbfd7ca1eee13a732964c482cb';

        $query ="Select * from user_short as u
        Where u.u_email ='{$u_email}' AND u.u_password='$u_password' LIMIT 1";

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