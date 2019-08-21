/* dynamically generated stylesheet */
<?php
// variables
$el = $options['el'];
?>

/* Media queries */

/* everything above wide screen */
@media screen and (min-width: 1200px) {

	/* 1600 px */

	<?php foreach( $data as $i => $slide ) { ?>
		<?php if( empty($slide[1600]) ) continue; ?>
	/*<?php echo $el ?> .slide:nth-child(<?php echo ($i+1); ?>) { */
	<?php echo $el ?> .slide-<?php echo ($i+1); ?> {
		background-image: url(<?php echo $slide[1600] ?>);
	}
	<?php } ?>

}

@media screen and (min-width: 1025px) and (max-width: 1200px) {

	/* 1200 px */

	<?php foreach( $data as $i => $slide ) { ?>
		<?php if( empty($slide[1024]) ) continue; ?>
	/*<?php echo $el ?> .slide:nth-child(<?php echo ($i+1); ?>) { */
	<?php echo $el ?> .slide-<?php echo ($i+1); ?> {
		background-image: url(<?php echo $slide[1024] ?>);
	}
	<?php } ?>

}

@media screen and (min-width: 800px) and (max-width: 1024px) {

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
		<?php if( empty($slide[1024]) ) continue; ?>
	/*<?php echo $el ?> .slide:nth-child(<?php echo ($i+1); ?>) { */
	<?php echo $el ?> .slide-<?php echo ($i+1); ?> {
		background-image: url(<?php echo $slide[1024] ?>);
	}
	<?php } ?>

}

@media screen and (max-width: 499px ) {

	/* 500 px */

	<?php foreach( $data as $i => $slide ) { ?>
		<?php if( empty($slide[768]) ) continue; ?>
	/*<?php echo $el ?> .slide:nth-child(<?php echo ($i+1); ?>) { */
	<?php echo $el ?> .slide-<?php echo ($i+1); ?> {
		background-image: url(<?php echo $slide[768] ?>);
	}
	<?php } ?>

}
