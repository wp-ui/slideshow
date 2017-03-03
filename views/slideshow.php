<?php
	// variables
	$id = $attr['id'];
	$target = "ui-slideshow-$id";
	$fn = "UISlideshow_$id";
	$options['el'] = "#$target";
	// add touch option if dependency met
	if( wp_script_is('backbone-input-touch') ) $options['monitor'] = array("touch");
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
<!-- styles -->
<link rel="stylesheet" id="ui-slideshow-styles" href="<?php echo $styles ?>" type="text/css" media="all">
<!-- logic -->
<script type="text/javascript">
	function <?php echo $fn ?>() {
		var view = new Backbone.UI.Slideshow(<?php echo json_encode($options, JSON_NUMERIC_CHECK) ?>);
		view.render();
	}
	window.onload = <?php echo $fn ?>;
</script>
