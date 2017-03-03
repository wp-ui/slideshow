/* dynamically generated stylesheet */
<?php
// variables
$id = $params['id'];
$el = "#ui-slideshow-$id";

?>

/* Media queries */

/* everything above wide screen */
@media screen and (min-width: 1200px) {

	/* 1600 px */

	<?php foreach($params['data'] as $i => $slide ) { ?>
	/*<?php echo $el ?> .slide:nth-child(<?php echo ($i+1); ?>) { */
	<?php echo $el ?> .slide-<?php echo ($i+1); ?> {
		background-image: url(<?php echo $slide[1600] ?>);
	}
	<?php } ?>

}

@media screen and (min-width: 800px) and (max-width: 1200px) {

	/* 1024 px */

	<?php foreach($params['data'] as $i => $slide ) { ?>
	/*<?php echo $el ?> .slide:nth-child(<?php echo ($i+1); ?>) { */
	<?php echo $el ?> .slide-<?php echo ($i+1); ?> {
		background-image: url(<?php echo $slide[1024] ?>);
	}
	<?php } ?>

}


@media screen and (max-width: 800px ) {

	/* 768 px */

	<?php foreach($params['data'] as $i => $slide ) { ?>
	/*<?php echo $el ?> .slide:nth-child(<?php echo ($i+1); ?>) { */
	<?php echo $el ?> .slide-<?php echo ($i+1); ?> {
		background-image: url(<?php echo $slide[768] ?>);
	}
	<?php } ?>

}
