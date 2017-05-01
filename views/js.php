/* dynamically generated stylesheet */
<?php
// variables
?>
jQuery(document).ready(function(){
	var view = new Backbone.UI.Slideshow(<?php echo json_encode($options, JSON_NUMERIC_CHECK) ?>);
	view.render();
});
