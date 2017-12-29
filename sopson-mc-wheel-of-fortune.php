<?php
/*
Plugin Name: SopsoN MC Wheel Of Fortune
Plugin URI: http://sopsondottell.tk
Version: 1.0
Author: Michał 'SopsoN' Sobczak
Author URI: http://sopsondottell.tk
Description: Provides login system, wheel of fortune feature, add item to player in MC
*/

include( plugin_dir_path( __FILE__ ).'include/php/Rcon.php' );

class SopsoNMCWheelOfFortune
{
    private static $instance;
    
    private $config;
    private $MCuser;

    public static function get_instance() 
    {
        if ( self::$instance == null ) 
            self::$instance = new SopsoNMCWheelOfFortune();
            
        return self::$instance;
    } 
    
    private function __construct()
    {
		ob_start();
			include('config.json');
			$tmp = ob_get_clean();
		ob_end_clean();
		
		$this->config = json_decode($tmp, true);
		
		add_action( 'wp_enqueue_scripts', array( $this, 'sopson_add_css_and_js' ) );
		
		add_shortcode( 'kolo_fortuny', array( $this, 'sopson_show_wheel_of_fortune' ) );
	}
	
	public function sopson_add_css_and_js()
	{
		// CSS
		wp_enqueue_style( 'sopson-fortune-of-wheel', plugin_dir_url( __FILE__ ).'include/css/sopson-fortune-of-wheel.css' );

		// JavaScript
		wp_enqueue_script( 'TweenMax.min', plugin_dir_url( __FILE__ ).'include/js/TweenMax.min.js' );
		wp_enqueue_script( 'Winwheel.min', plugin_dir_url( __FILE__ ).'include/js/Winwheel.min.js' );
	}
	
	public function sopson_show_wheel_of_fortune()
	{
		$html = '';
		$errors = array();

		/*if(!empty($_POST))
		{
			if(empty($_POST['sopson-login']))
				$errors['empty-login'] = true;
			
			if(empty($_POST['sopson-password']))
				$errors['empty-password'] = true;
				
			if(empty($errors))
			{
				$wofDB = new wofDB($config["db_user"], $config["db_pass"], $config["db_name"], $config["host"]);
				$user = $wofDB->check_user($_POST['sopson-login'], $_POST['sopson-pass']);
				
				if(!empty($user))
				{
					
					$this->MCuser = $_POST['sopson-login'];
						
					if($user->last_spin < DateTime("now"))
					{
						$show = "wheel";
					}
					else
					{
						$show = "spinned";	
					}
				}
				else
				{
					$errors['no-user'] = true;
				}
			}
		}
		
		if(!empty($errors))
		{
			$show = false;
		}*/

		ob_start();
			include( plugin_dir_path(__FILE__).'shortcode/content.php');
			$html = ob_get_contents();
		ob_end_clean();
		
		return $html;
	}
	
	public function sopson_send_item_to_player($player_name, $item_name)
	{	
		$rcon = new Rcon( $config['host'], $config['port'], $config['password'], $config['timeout'] );
		
		if ($rcon->connect())
		{
			$rcon->sendCommand("/give {$player_name} {$item_name}");
		}
		else
		{
			
		}
	}
}
add_action( 'plugins_loaded', array( 'SopsoNMCWheelOfFortune', 'get_instance' ) );