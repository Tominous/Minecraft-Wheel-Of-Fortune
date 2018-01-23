<?php
	if($show == "wheel"):
?>

<canvas id='canvas' width='880' height='600'>Canvas nie jest wspierane, użyj innej przeglądarki.</canvas>
<div class="spin-info-container">
	<h4>Witaj!</h4>
<!--	<h5><?php echo $this->MCuser->realname; ?></h5>-->
	<h5><?php echo $this->MCuser['realname']; ?></h5>
	<button id="spin-button" onClick="theWheel.startAnimation(); this.disabled = true; this.style.display='none';">Zakręć</button>
</div>

<div class="remodal" data-remodal-id="modal">
	<button data-remodal-action="close" class="remodal-close"></button>
	
	<h1>Gratulacje! :D</h1>
	<h3 id="wof-result">Może najpierw zakręć? 😂</h3>
	<p>Wygrany przedmiot trafi do twojego ekwipunku automatycznie.</p>
	<p>Wpadnij jutro, żeby zakręcić kołem jeszcze raz!</p>
</div>

<?php
    ob_start();
		include(plugin_dir_path(__FILE__).'../commands.json');
	$tmp = ob_get_clean();
		
	$items = json_decode($tmp, true);
	unset($tmp);
?>

<script>
var theWheel = new Winwheel({
    'numSegments' : <?php echo count($items['item_name']); ?>,
    'segments'    :
    [
    <?php
        for($i = 0; $i < count($items['item_name']); $i++)
        {
            
            if($items['item_size'][$i] != null || $items['item_size'][$i] != '' || $items['item_size'][$i] < 0)
            {
                $size = ",'size': ".$items['item_size'][$i];
            }
            else
            {
                $size = '';
            }
                
            echo '{ \'fillStyle\': \''.$this->random_color().'\', \'text\': \''.$items['item_name'][$i]. '\' '.$size.'},'."\n";  
        }
    ?>
    ],							
    'animation' :                   
    {
        'type'     : 'spinToStop',  
        'duration' : 5,
        'spins'    : 8,
        'callbackFinished' : 'alertPrize()',
        'callbackAfter' : 'drawTriangle()'
    },   
    'pointerAngle'   : 90,  
});

    
</script>

<script src="<?php echo plugin_dir_url( __FILE__ ).'../include/js/sopson-wheel-of-fortune.js'; ?>"></script>
<?php
	elseif($show == "spinned"):
?>
<div id="wof-spinned">
<!--	<h3>Można zakręcić kołem tylko raz dziennie. <?php echo $this->MCuser->realname; ?> poczekaj do jutra :)</h3> -->
	<h3>Można zakręcić kołem tylko raz dziennie. <?php echo $this->MCuser['realname']; ?> poczekaj do jutra :)</h3>
	<h4>Zostało tylko</h4>
	<div id="wof-timecounter"></div>
</div>
<script src="<?php echo plugin_dir_url( __FILE__ ).'../include/js/wof-timecounter.js'; ?>"></script>

<?php
	else:
?>
	<form id="sopson-wof-login" action="" method="post" name="sopson-wof-login">
		<h3>Wpisz dane z serwera gry, żeby zakręcić kołem</h3>
		<?php
			if(!empty($errors))
			{
				echo '<div id="sopson-wof-error-div">Wystąpił błąd logowania, wprowadź poprawny login i hasło</div>';
			}
		?>
		<label>
			<span>Login:</span><br/>
			<input type="text" name="sopson-login" 
				<?php echo !empty($errors['empty-login']) ? 'class="sopson-wof-error"' : ''; ?> 
				value="<?php echo !empty($_POST['sopson-login']) ? $_POST['sopson-login'] : ''; ?>"/>
		</label>
		
		<label>
			<span>Hasło:</span><br/>
			<input type="password" name="sopson-password" <?php echo !empty($errors['empty-password']) ? 'class="sopson-wof-error"' : ''; ?> />
		</label>
		
		<input type="submit" value="Zaloguj"/>
	</form>
<?php
	endif;
?>
