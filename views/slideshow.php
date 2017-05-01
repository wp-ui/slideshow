<?php
	// available context: $atts, $options
	// variables
	$target = "ui-slideshow-". $atts['id'];
?>
<?php
	// display error(s)
	if( isset($_SESSION['ui_slideshow_error']) ){
		echo '<div class="error">'. $_SESSION['ui_slideshow_error'] .'</div>';
	}
?>
<div id="<?php echo $target ?>" class="ui-slideshow no-captions">
	<a class="prev arrow"></a>
	<a class="next arrow"></a>
	<?php if( empty($options['autoloop']) ){ ?>
	<ul class="nav">
	<?php foreach( $data as $slide ){ ?>
		<li><a href="#"></a></li>
	<?php } ?>
	</ul>
	<?php } ?>
	<div class="wrapper">
	<?php foreach( $data as $i => $slide ){ ?>
		<div class="slide slide-<?php echo ($i+1) ?>"><!-- --></div>
	<?php } ?>
	</div>
</div>
