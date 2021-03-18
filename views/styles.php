/* dynamically generated stylesheet */
<?php
// variables
$el = $options['el'];
// randomize data if param set
$randomize = (array_key_exists("randomize", $params)) ? (bool)$params['randomize'] : false;
if($randomize) shuffle($data);
?>

/* Media queries */

/* Ultra-wide, retina and above */
@media screen and (min-width: 1921px) {

	/* 3200 px */

	<?php foreach( $data as $i => $slide ) { ?>
		<?php if( empty($slide[3200]) ) continue; ?>
	/*<?php echo $el ?> .slide:nth-child(<?php echo ($i+1); ?>) { */
	<?php echo $el ?> .slide-<?php echo ($i+1); ?> {
		background-image: url(<?php echo $slide[3200] ?>);
	}
	<?php } ?>

}

/* Full HD */
@media screen and (min-width: 1201px) and (max-width: 1920px) {

	/* 1200 px */

	<?php foreach( $data as $i => $slide ) { ?>
		<?php if( empty($slide[1200]) ) continue; ?>
	/*<?php echo $el ?> .slide:nth-child(<?php echo ($i+1); ?>) { */
	<?php echo $el ?> .slide-<?php echo ($i+1); ?> {
		background-image: url(<?php echo $slide[1200] ?>);
	}
	<?php } ?>

}

@media screen and (min-width: 800px) and (max-width: 1200px) {

	/* 1024 px */

	<?php foreach( $data as $i => $slide ) { ?>
		<?php if( empty($slide[1024]) ) continue; ?>
	/*<?php echo $el ?> .slide:nth-child(<?php echo ($i+1); ?>) { */
	<?php echo $el ?> .slide-<?php echo ($i+1); ?> {
		background-image: url(<?php echo $slide[1024] ?>);
	}
	<?php } ?>

}

@media screen and (min-width: 500px ) and (max-width: 799px ) {

	/* 800 px */

	<?php foreach( $data as $i => $slide ) { ?>
		<?php if( empty($slide[800]) ) continue; ?>
	/*<?php echo $el ?> .slide:nth-child(<?php echo ($i+1); ?>) { */
	<?php echo $el ?> .slide-<?php echo ($i+1); ?> {
		background-image: url(<?php echo $slide[800] ?>);
	}
	<?php } ?>

}

@media screen and (max-width: 499px ) {

	/* 400 px */

	<?php foreach( $data as $i => $slide ) { ?>
		<?php if( empty($slide[400]) ) continue; ?>
	/*<?php echo $el ?> .slide:nth-child(<?php echo ($i+1); ?>) { */
	<?php echo $el ?> .slide-<?php echo ($i+1); ?> {
		background-image: url(<?php echo $slide[400] ?>);
	}
	<?php } ?>

}
