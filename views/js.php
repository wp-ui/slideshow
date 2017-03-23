/* dynamically generated stylesheet */
<?php
// variables
?>
console.log("LOAAA");
jQuery(document).ready(function(){
	var view = new Backbone.UI.Slideshow(<?php echo json_encode($options, JSON_NUMERIC_CHECK) ?>);
	view.render();
	console.log(view);
});
