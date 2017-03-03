<?php
?>
<form action="options.php" method="post">
	<?php
		//settings_fields( UI_SLIDESHOW_OPTIONS );
		//do_settings_sections( UI_SLIDESHOW_SETTINGS );
		//submit_button();
	?>
</form>
<hr>

<form method="post" action="<?php echo $_SERVER['REQUEST_URI']; ?>">

	<?php submit_button(); ?>

</form>
