<?php
class dbConnect{
    // specify your own database credentials
    //private $host = "http://dev.at1ts.com";
    private $host = "localhost";
    private $db_name = "dblnef5hfuazae";
    private $username = "uwjf1gulv9aun";
    private $password = "bv*@fN&~3i.1";
    protected $con;

    function __construct(){
        $this->con=new mysqli($this->host,$this->username,$this->password,$this->db_name);

        //$this->con->set_charset('utf8');
        mysqli_query($this->con,"SET NAMES 'utf8'");
        mysqli_query($this->con,"SET CHARACTER SET 'utf8' COLLATE 'utf8_unicode_ci'");
        if ($this->con->connect_error){
            die ("Failed to connect to MySQL: " . mysqli_connect_error());
        }

    }
}?>