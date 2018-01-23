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
include( plugin_dir_path( __FILE__ ).'include/php/AuthmeHash.php' );
include( plugin_dir_path( __FILE__ ).'include/php/wof-db.php' );

class SopsoNMCWheelOfFortune
{
    private static $instance;
    
    private $config;
    private $MCuser;
    private $wofDB;

    public static function get_instance() 
    {
        if ( self::$instance == null ) 
            self::$instance = new SopsoNMCWheelOfFortune();
            
        return self::$instance;
    } 
    
    private function __construct()
    {
		ob_start();
			include(plugin_dir_path(__FILE__).'config.json');
			$tmp = ob_get_clean();
		ob_end_clean();
		
		$this->config = json_decode($tmp, true);
		
		$this->wofDB = new wofDB(
		    $this->config["db_ip"], 
		    $this->config["db_port"], 
		    $this->config["db_user"], 
		    $this->config["db_pass"], 
            $this->config["db_name"]
        );
        
        unset($tmp);
		
		add_action( 'wp_enqueue_scripts', array( $this, 'sopson_add_css_and_js' ) );
		add_action( 'wp_head', array( $this, 'global_js_variables' ) );
		
		
		add_action( 'wp_ajax_nopriv_update_last_spin', array( $this, 'update_last_spin_and_give_item' ) );
		add_action( 'wp_ajax_update_last_spin', array( $this, 'update_last_spin_and_give_item' ) );

		add_shortcode( 'kolo_fortuny', array( $this, 'sopson_show_wheel_of_fortune' ) );
		
		
		// Admin Panel
        add_action( 'admin_menu', array( $this, 'sopson_wof_admin_panel' ) );
        add_action( 'admin_enqueue_scripts', array( $this, 'sopson_wof_admin_panel_styles_js' ) );

	}
	
	public function sopson_add_css_and_js()
	{
		// CSS
		wp_enqueue_style( 'sopson-fortune-of-wheel', plugin_dir_url( __FILE__ ).'include/css/sopson-fortune-of-wheel.css' );
		wp_enqueue_style( 'remodal', plugin_dir_url( __FILE__ ).'include/css/remodal.css' );
		wp_enqueue_style( 'remodal-default-theme', plugin_dir_url( __FILE__ ).'include/css/remodal-default-theme.css' );

		// JavaScript
		wp_enqueue_script( 'TweenMax.min', plugin_dir_url( __FILE__ ).'include/js/TweenMax.min.js' );
		wp_enqueue_script( 'Winwheel.min', plugin_dir_url( __FILE__ ).'include/js/Winwheel.min.js' );
		wp_enqueue_script( 'remodal.min', plugin_dir_url( __FILE__ ).'include/js/remodal.min.js', array('jquery') );
	}
	
	public function global_js_variables()
	{
		?>
		<script>
			var sopson_ajax_url = <?php echo json_encode( admin_url( 'admin-ajax.php' ) ); ?>;
		</script>
		<?php
	}
		
	public function sopson_show_wheel_of_fortune()
	{
		$html = '';
		$errors = array();
		$show = '';
   		
		if(!empty($_POST))
		{
			if(empty($_POST['sopson-login']))
				$errors['empty-login'] = true;
			
			if(empty($_POST['sopson-password']))
				$errors['empty-password'] = true;
				
			if(empty($errors))
			{
				$this->MCuser = $this->wofDB->get_user_data(sanitize_text_field($_POST['sopson-login']));
				
				if($this->MCuser)
				{
					$pass_db = $this->MCuser['password'];
					
					$super_hash = new Sha256();
					
					if($super_hash->isValidPassword($_POST['sopson-password'],$pass_db))
					{
						$yesterday = new DateTime("today");
						$spin = new DateTime($this->MCuser['LastSpin']);
	
						if($yesterday >= $spin)
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
		}
		
		if(!empty($errors))
		{
			$show = false;
		}

		ob_start();
			include( plugin_dir_path(__FILE__).'shortcode/content.php');
			$html = ob_get_contents();
		ob_end_clean();
		
		return $html;
	}
	
	public function update_last_spin_and_give_item()
	{       
    	
        ob_start();
    		include(plugin_dir_path(__FILE__).'commands.json');
    	$tmp = ob_get_clean();
        $items = json_decode($tmp, true);
    	unset($tmp);

    	
        if($this->wofDB->update_last_spin($_POST['player_name']))
        {
            $info = array();
    		$rcon = new Rcon($this->config['host'], $this->config['port'], $this->config['password'], 3);

			$rcon->connect();
			
			if($rcon->isConnected())
			{
            	$index = $_POST['item_index']-1;
                // give <player_name> <item_name> <item_qunatitiy> 
                $command = "give ".$_POST['player_name']." ".$items['item_code'][$index] ." ".$items['item_count'][$index];			
    			
                $rcon->sendCommand($command);	
                $rcon->disconnect();		
                
                wp_send_json_success();
			}
			else
			{
    			$rcon->disconnect();	
	            wp_send_json_error(array('Błąd w trakcie łączenia z serwerem. Spróbuj zakręcić później', $dbResult));	
			}
        }
        else
        {
            wp_send_json_error(array('Błąd w trakcie łączenia z bazą danych. Spróbuj zakręcić później', $dbResult));
        }
		
		wp_die();
	}
	
	public function sopson_wof_admin_panel()
	{
    	add_menu_page(
    	    'MC Wheel of fortune', 
    	    'MC Wheel of fortune', 
    	    'manage_options', 
    	    'wof-managment-page', 
    	    array( $this, 'show_wof_admin_content' ),
            plugin_dir_url( __FILE__ ).'include/frameworks/bootstrap/font/png-24px/starburst-outline.png'
        );
    	
    	add_submenu_page( 
    	    'wof-managment-page', 
    	    'Connection', 
    	    'Connection', 
    	    'manage_options', 
    	    'wof-managment-page-connections', 
    	    array( $this, 'wof_admin_panel_conn' )
        );
	}
	
	public function sopson_wof_admin_panel_styles_js()
	{
        wp_enqueue_style( 'wof-admin-style', plugin_dir_url(__FILE__).'include/css/wof-admin-style.css' );

    	if(
    	    'http://'.$_SERVER['HTTP_HOST'].$_SERVER['REQUEST_URI'] == admin_url( 'admin.php?page=wof-managment-page')
            || 'http://'.$_SERVER['HTTP_HOST'].$_SERVER['REQUEST_URI'] == admin_url( 'admin.php?page=wof-managment-page-connections')
        )
    	{
            wp_enqueue_style( 'bootstrap', plugin_dir_url(__FILE__).'include/frameworks/bootstrap/css/bootstrap.css' );
            wp_enqueue_style( 'typicons', plugin_dir_url(__FILE__).'include/frameworks/bootstrap/font/typicons.min.css' );
            wp_enqueue_script( 'bootstrap', plugin_dir_url(__FILE__).'include/frameworks/bootstrap/js/bootstrap.js' );
        }
	}
	
	public function show_wof_admin_content()
	{
    	$html = '';
    	
    	$classes = array();
            	
    	if(!empty($_POST))
    	{
            $errors = $this->validate_wof_items($classes);

            if(empty($errors))
        	{
            	if(file_put_contents(plugin_dir_path(__FILE__).'commands.json', json_encode($_POST, true)))
            	{
                	echo '<br/><div class="alert alert-success" role="alert">Items has been added</div>';
            	}
            	else
            	{
                	echo '<br/><div class="alert alert-danger" role="alert">Error occured</div>';                	
            	}
            }
    	}
    	else
    	{
        	$this->null_classes_wof_items($classes);
    	}

    	ob_start();
			include(plugin_dir_path(__FILE__).'commands.json');
			$tmp = ob_get_clean();
		
		$items = json_decode($tmp, true);
		unset($tmp);
    	
    	ob_start();
    	    include(plugin_dir_path(__FILE__).'shortcode/admin-content.php');
    	   $html = ob_get_contents();
		ob_end_clean();
		
		echo $html;
	}
	
	public function wof_admin_panel_conn()
	{
    	$html = '';
                
        $classes = array();
            	
    	if(!empty($_POST))
    	{
            $errors = $this->validate_wof_admin_settings_post($classes);

            if(empty($errors))
        	{
            	if(file_put_contents(plugin_dir_path(__FILE__).'config.json', json_encode($_POST, true)))
            	{
                	echo '<br/><div class="alert alert-success" role="alert">Connection settings has been saved</div>';
            	}
            	else
            	{
                	echo '<br/><div class="alert alert-danger" role="alert">Error occured</div>';                	
            	}
            }
    	}
    	else
    	{
        	$this->null_wof_admin_settings_post($classes);
    	}
    	
    	ob_start();
    	    include(plugin_dir_path(__FILE__).'shortcode/admin-settings.php');
    	   $html = ob_get_contents();
		ob_end_clean();
		
		echo $html;
	}
	
	private function validate_wof_admin_settings_post(&$classes)
	{
    	$errors = array();
    	
        $errors['db_ip'] = empty($_POST['db_ip']);
        $errors['db_port'] = empty($_POST['db_port']);
        $errors['db_name'] = empty($_POST['db_name']);
        $errors['db_user'] = empty($_POST['db_user']);
        $errors['db_pass'] = empty($_POST['db_pass']);
        $errors['host'] = empty($_POST['host']);
        $errors['port'] = empty($_POST['port']);
        $errors['password'] = empty($_POST['password']);
        
        $classes['host'] = $errors['host'] ? 'is-invalid' : 'is-valid';
        $classes['port'] = $errors['port'] ? 'is-invalid' : 'is-valid';
        $classes['password'] = $errors['password'] ? 'is-invalid' : 'is-valid';
        $classes['db_ip'] = $errors['db_ip'] ? 'is-invalid' : 'is-valid';
        $classes['db_port'] = $errors['db_port'] ? 'is-invalid' : 'is-valid';
        $classes['db_user'] = $errors['db_user'] ? 'is-invalid' : 'is-valid';
        $classes['db_pass'] = $errors['db_pass'] ? 'is-invalid' : 'is-valid';
        $classes['db_name'] = $errors['db_name'] ? 'is-invalid' : 'is-valid';

    	return $errors;
	}
	
	private function null_wof_admin_settings_post(&$classes)
	{
    	$classes['host'] = '';
        $classes['port'] = '';
        $classes['password'] = '';
        $classes['db_ip'] = '';
        $classes['db_port'] = '';
        $classes['db_user'] = '';
        $classes['db_pass'] = '';
        $classes['db_name'] = '';
	}
	
	private function validate_wof_items(&$classes)
	{
    	
	}
	
	private function null_classes_wof_items(&$classes)
	{
    	
	}
	
	private function random_color_part() 
	{
        return str_pad( dechex( mt_rand( 0, 255 ) ), 2, '0', STR_PAD_LEFT);
    }

    private function random_color() 
    {
        return '#'.$this->random_color_part() . $this->random_color_part() . $this->random_color_part();
    }
}
add_action( 'plugins_loaded', array( 'SopsoNMCWheelOfFortune', 'get_instance' ) );