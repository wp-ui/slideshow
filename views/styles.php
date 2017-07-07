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
	/*<?php echo $el ?> .slide:nth-child(<?php echo ($i+1); ?>) { */
	<?php echo $el ?> .slide-<?php echo ($i+1); ?> {
		background-image: url(<?php echo $slide[1600] ?>);
	}
	<?php } ?>

}

@media screen and (min-width: 1025px) and (max-width: 1200px) {

	/* 1024 px */

	<?php foreach( $data as $i => $slide ) { ?>
	/*<?php echo $el ?> .slide:nth-child(<?php echo ($i+1); ?>) { */
	<?php echo $el ?> .slide-<?php echo ($i+1); ?> {
		background-image: url(<?php echo $slide[1024] ?>);
	}
	<?php } ?>

}

@media screen and (min-width: 800px) and (max-width: 1024px) {

	/* 768 px */

	<?php foreach( $data as $i => $slide ) { ?>
	/*<?php echo $el ?> .slide:nth-child(<?php echo ($i+1); ?>) { */
	<?php echo $el ?> .slide-<?php echo ($i+1); ?> {
		background-image: url(<?php echo $slide[1024] ?>);
	}
	<?php } ?>

}

@media screen and (min-width: 500px ) and (max-width: 799px ) {

	/* 768 px */

	<?php foreach( $data as $i => $slide ) { ?>
	/*<?php echo $el ?> .slide:nth-child(<?php echo ($i+1); ?>) { */
	<?php echo $el ?> .slide-<?php echo ($i+1); ?> {
		background-image: url(<?php echo $slide[768] ?>);
	}
	<?php } ?>

}

@media screen and (max-width: 499px ) {

	/* 400 px */

	<?php foreach( $data as $i => $slide ) { ?>
	/*<?php echo $el ?> .slide:nth-child(<?php echo ($i+1); ?>) { */
	<?php echo $el ?> .slide-<?php echo ($i+1); ?> {
		background-image: url(<?php echo $slide[400] ?>);
	}
	<?php } ?>

}
