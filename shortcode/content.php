<?php
	$show = "wheel";
	
	if($show == "wheel"):
?>

<canvas id='canvas' width='880' height='600'>Canvas nie jest wspierane, użyj innej przeglądarki.</canvas>
<button id="spin-button" onClick="theWheel.startAnimation(); this.disabled=true;">Zakręć</button>

<script src="<?php echo plugin_dir_url( __FILE__ ).'../include/js/sopson-wheel-of-fortune.js'; ?>"></script>
<?php
	elseif($show == "spinned"):
?>
<div id="wof-spinned">
	<h3>Można zakręcić kołem tylko raz dziennie. <?php echo $this->MCuser; ?> poczekaj do jutra :)</h3>
	<h4>Zostało już tylko</h4>
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
