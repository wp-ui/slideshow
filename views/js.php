/* dynamically generated stylesheet */
<?php
// variables
$key = str_replace("#ui-", "", $options['el']);
?>
// global namespace
if(typeof ui != "object") ui = {};
jQuery(document).ready(function(){
	var view = new Backbone.UI.Slideshow(<?php echo json_encode($options, JSON_NUMERIC_CHECK) ?>);
	view.render();
	// save in the global namespace
	ui['<?=$key?>'] = view;
});
