<?php

/*
  Plugin Name: UI: Slideshow
  Plugin URI: https://github.com/backbone-ui/slideshow
  Description: Create a slideshow from your gallery images
  Version: 1.0
  Author: Makesites
  Author URI: http://makesites.org
 */

$ui_slideshow = new WP_UI_Slideshow();

class WP_UI_Slideshow {

	protected $version = "1.2"; // pickup version from package.json
	protected $imgSizes = array(768, 1024, 1600);

	function __construct() {

		// hooks
		add_action( 'wp_enqueue_scripts', array($this, 'loadAssets') );
		//add_action( 'plugins_loaded', array($this, 'db_check') );
		add_shortcode( 'ui-slideshow', array($this, 'shortcode') );
		// Use init to act on $_POST data:
		//add_action( 'init', array($this, 'process_post') );
		// session
		add_action('init', array($this, 'sessionStart'), 1);
		//add_action('wp_loaded', array($this, 'redirect') );
		add_action('wp_logout', array($this, 'sessionEnd') );
		add_action('wp_login', array($this, 'sessionEnd') );
		// admin
		//add_action('admin_menu', array($this, 'admin_menu') );
		add_action('wp_ajax_ui_slideshow_styles', array($this, 'dynamicStyles') );
		add_action('wp_ajax_nopriv_ui_slideshow_styles', array($this, 'dynamicStyles') );
	}

	// generate dynamic styles
	function dynamicStyles(){
		header('Content-type: text/css');
		$params = json_decode( base64_decode( urldecode( $_GET['params'] ) ), true); // error control?
		// split data based on size
		$params['data'] = $this->_orderSrcset( $params['data'] );
		$view = dirname(__FILE__) ."/views/styles.php";
		include( $view );
		wp_die();
		//exit;
	}

	// setup methods
	function loadAssets(){
		// styles
		wp_enqueue_style( 'ui-slideshow', plugins_url( 'assets/css/backbone.ui.slideshow.css', __FILE__ ), array(), $this->version );
		// deps
		if( !wp_script_is('underscore') && !wp_script_is('underscorejs') ) wp_enqueue_script( 'underscore', "//cdnjs.cloudflare.com/ajax/libs/underscore.js/1.8.3/underscore-min.js", array(), "1.8.3", true );
		if( !wp_script_is('backbone') && !wp_script_is('backbonejs')) wp_enqueue_script( 'backbone', "//cdnjs.cloudflare.com/ajax/libs/backbone.js/1.1.2/backbone-min.js", array('underscore'), "1.1.2", true );
		if( !wp_script_is('jquery') ) wp_enqueue_script( 'jquery', "//cdnjs.cloudflare.com/ajax/libs/jquery/3.1.0/jquery.min.js", array(), "3.1.0", true );
		// script
		wp_enqueue_script( 'ui-slideshow-js', plugins_url( 'assets/js/backbone.ui.slideshow.js', __FILE__ ), array('backbone', 'jquery', 'underscore'), $this->version, true );
		// register backbone.input.touch optionally (with an option)
	}
	// data methods

	function getImages( $ids="" ){
		// convert to array
		if( !is_array($ids) ) $ids = explode(",", $ids);
		// variables
		$images = array();

		foreach( $ids as $image_id ){
			$attachment = get_post( $image_id, 'ARRAY_A' );
			// $src = wp_get_attachment_image_srcset( $image_id, ['768px', '1024px', '1600px'] );
			$src = wp_get_attachment_image_srcset( $image_id, array(768, 1024, 1600) );
			$excerpt = htmlspecialchars($attachment['post_excerpt']);
			array_push( $images, array(
				'src' => $src,
				'excerpt' => $excerpt,
				'ratio' => $this->ratio( $src )
			));
		}

		return $images;
	}

	function error( $key ) {
		$text = "";
		// translate key to a phrase
		switch( $key ){
			case 'empty_fields':
				$text = "Please complete all fields";
			break;
		}
		// save in the session for later
		$_SESSION['ui_slideshow_error'] = $text;
	}

	// finds the aspect ratio of an image
	function ratio( $src ){
		// regular expression
		$pattern = '/-[0-9]*x[0-9]*./';
		preg_match($pattern, $src, $matches, PREG_OFFSET_CAPTURE);
		if( !isset( $matches[0][0] ) ) return 0;
		$dimensions = substr($matches[0][0], 1, -1);
		$sizes = explode("x", $dimensions); // assume "x" exists?
		return $sizes[0]/$sizes[1];
	}

	function sessionStart() {
		if(!session_id()) {
			session_start();
		}
		// reset error
		unset( $_SESSION['ui_slideshow_error'] );
	}

	function sessionEnd() {
		session_destroy ();
	}

	// [ui-slideshow view="form" postcode="XX12345"]
	function shortcode( $atts ) {
		// prerequisite
		if( !array_key_exists('ids', $atts) ) return;
		//
		$attr = shortcode_atts( array(
			'id' => rand(1000, 9999), // unique identifier
			'view' => "slideshow",
		), $atts );
		// get images
		$data = $this->getImages( $atts['ids'] );
		// options
		$options = $this->setOptions( $atts );
		// queue styles
		// dynamic styles (use optionally?)
		$params = urlencode ( base64_encode( json_encode(array(
			"id" => $attr['id'],
			"options" => $options,
			"data" => $data
		), JSON_NUMERIC_CHECK) ));
		$styles = admin_url('admin-ajax.php').'?action=ui_slideshow_styles&params='. $params;
		//wp_enqueue_style('ui-slideshow-styles', admin_url('admin-ajax.php').'?action=ui_slideshow_styles$params='. $params, array(), $this->version, true );
		// load view
		// lookup in theme folder (for an override)
		$view = get_template_directory() ."/views/". $attr['view'] .".php";
		if( !file_exists( $view ) ){
			$view = plugin_dir_path( __FILE__ ) ."views/". $attr['view'] .".php"; // assume it exists?
		}

		ob_start();
		include( $view );
		return ob_get_clean();
	}

	// pass the shortcode attributes as client-side options
	function setOptions( $atts=array() ){
		$options = array();
		// supporting:
		$keys = array('autoplay', 'autoloop', 'timeout', 'width', 'height');
		//
		foreach($keys as $option){
			if( array_key_exists($option, $atts) )
				$options[$option] = $atts[$option];
		}
		return $options;
	}
	// hidden

	function _orderSrcset( $data ){
		// variables
		$slides = array();
		$sizes = $this->imgSizes;
		//
		foreach( $data as $row){
			$slide = array();
			$images = array();
			$src = explode(", ", $row['src']);
			foreach($src as $img){
				$img = explode(" ", $img);
				// create container
				$size = (int)str_replace("w", "", $img[1]);
				// prerequisite
				if( in_array($size, $sizes) ) $slide[$size] = $img[0];
				// all images bucket
				$images[$size] = $img[0];
			}
			// make sure we have images for all sizes
			if( count($slide) !== count($sizes) ){
				foreach($sizes as $size ){
					if( array_key_exists($size, $slide)  ) continue; // no problem
					// get the image that's closest in dimensions
					$select = array("image"=>"", "size"=>0, "size"=>0 );
					foreach($images as $i => $image ){
						if( abs($i - $size) > abs($i - $select['size']) ) continue;
						$select = array(
							"image" => $image,
							"size" => $i
						);
					}
					// definitely exists?
					$slide[ $size ] = $select['image'];
				}
			}
			array_push( $slides, $slide );
		}
		// second loop to co
		return $slides;
	}

}

?>
