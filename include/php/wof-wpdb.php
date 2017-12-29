<?php
	
class wofDB
{
    private $db;

    public function __construct($db_user, $db_pass, $db_name, $db_host) 
    {
        try 
        {
            $this->db = new wpdb($db_user, $db_pass, $db_name, $db_host);
            $this->db->show_errors();
        } 
        catch (Exception $e) 
        {
            echo $e->getMessage();
        }
    }
    
    public function check_user($login, $pass)
    {
	    return $this->db->get_results("SELECT * FROM `` WHERE `login` = {$login} AND `password` = {$pass}");
    }

    public function update_student_id($student_id, $student_name) 
    {
        return $this->db->update(
            'students',
            array('student_name' => $student_name),
            array('student_id' => $student_id),
            array('%s'), array('%d')
        );
    }
}