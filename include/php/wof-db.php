<?php
	
class wofDB
{
    private $db;
    
    private $host;
    private $port;
    private $user;
    private $pass;
    private $name;

    public function __construct($db_host, $db_port, $db_user, $db_pass, $db_name ) 
    {
        $this->host = $db_host;
        $this->port = $db_port; 
        $this->user = $db_user;
        $this->pass = $db_pass;
        $this->name = $db_name;
    }
    
    public function get_user_data($login)
    {
        $db = $this->connect_to_database();
	    
	    $result = $db->query('SELECT * FROM `authme` WHERE `realname` = "'.$login.'"');
        
        mysqli_close($db);
        
	    return $result->fetch_assoc();
    }
    
    public function update_last_spin($player_name)
    {
        $now = new DateTime("now");
        $now = $now->format("Y-m-d H:i:s");

        $db = mysqli_connect($this->host, $this->user, $this->pass, $this->name);
        $sql = 'UPDATE `authme` SET `LastSpin` = "'.$now.'" WHERE `realname` = "'.$player_name.'"';
        mysqli_query($db, $sql);
        mysqli_close($db);

        return true;
    }
    
    private function connect_to_database()
    {
        return mysqli_connect($this->host, $this->user, $this->pass, $this->name, $this->port);
    }
}