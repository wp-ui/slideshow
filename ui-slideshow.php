<?php

/*
  Plugin Name: UI: Slideshow
  Plugin URI: https://github.com/backbone-ui/slideshow
  Description: Create a slideshow from your gallery images
  Version: 1.0
  Author: Makesites
  Author URI: http://makesites.org
 */

// Constants
// plugin folder url
if(!defined('UI_SLIDESHOW_URL')) define('UI_SLIDESHOW_URL', plugin_dir_url( __FILE__ ));
// plugin folder path
if(!defined('UI_SLIDESHOW_DIR')) define('UI_SLIDESHOW_DIR', plugin_dir_path( __FILE__ ));
// plugin root file
if(!defined('UI_SLIDESHOW_FILE')) define('UI_SLIDESHOW_FILE', __FILE__);

 define( 'UI_SLIDESHOW_SETTINGS', 'ui-slideshow' );

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
		add_action('wp_ajax_ui_slideshow_js', array($this, 'dynamicJS') );
		add_action('wp_ajax_nopriv_ui_slideshow_js', array($this, 'dynamicJS') );

		if ( is_admin() ){ // admin actions
			add_action( 'admin_menu', array($this, 'settingsPage') );
		}

	}

	// generate dynamic styles
	function dynamicStyles(){
		header('Content-type: text/css');
		// get data
		$data = array();
		//$data = json_decode( base64_decode( urldecode( $_GET['data'] ) ), true); // error control?
		$params = json_decode( base64_decode( urldecode( $_GET['params'] ) ), true); // error control?
		if( array_key_exists('acf', $params) ){
			// get the slides from the ACF (default: acf='slides')
			$data = $this->getImagesACF($params['acf'], $params['post']);
		} else {
			// assume ids exist?
			$data = $this->getImages( $params['ids'] );
		}
		//$options = json_decode( base64_decode( urldecode( $_GET['options'] ) ), true); // error control?
		// split data based on size
		$data = $this->_orderSrcset( $data );
		// render
		$view = dirname(__FILE__) ."/views/styles.php";
		include( $view );
		wp_die();
		//exit;
	}

	// generate dynamic logic
	function dynamicJS(){
		header('Content-type: application/javascript');
		$options = json_decode( base64_decode( urldecode( $_GET['options'] ) ), true); // error control?
		// render
		$view = dirname(__FILE__) ."/views/js.php";
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

	function getImagesACF( $key="", $post=false ){
		// variables
		$ids = array();
		$field = ($post) ? get_field($key, $post) : get_field($key);
		// convert urls to ids
		foreach($field as $slide){
			$ids[] = $this->attachmentID( $slide['image'] );
		}
		// normalize data
		return $this->getImages( $ids );
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
		global $post;
		// prerequisite(s)
		//if( !array_key_exists('ids', $atts) ) return;
		if( !isset($post->ID) ) return;
		// variables
		$data = array();
		//$atts = shortcode_atts( array(
		$atts = array_merge( array(
			'id' => rand(1000, 9999), // unique identifier
			'post' => $post->ID, // assume it exists?
			'view' => "slideshow",
		), $atts );
		// load data
		if( array_key_exists('acf', $atts)  ){
			// get the slides from the ACF (default: acf='slides')
			$data = $this->getImagesACF($atts['acf']);
		} else {
			// assume ids exist?
			$data = $this->getImages( $atts['ids'] );
		}
		// soft slide attributes
		$params = $this->setParams( $atts );
		// options
		$options = $this->setOptions( $atts );
		$options['el'] = "#ui-slideshow-". $atts['id'];
		// add touch option if dependency met
		if( wp_script_is('backbone-input-touch') ) $options['monitor'] = array("touch");
		// queue styles - dynamic styles (use optionally?)
		$styles = admin_url('admin-ajax.php').'?action=ui_slideshow_styles&params='. $this->queryParam( $params ) .'&options='. $this->queryParam( $options );
		$js = admin_url('admin-ajax.php').'?action=ui_slideshow_js&options='. $this->queryParam( $options );
		// enqueue styles/logic
		wp_enqueue_style('ui-slideshow-img', $styles, array(), $this->version, 'all' );
		wp_enqueue_script('ui-slideshow-js-custom', $js, array('jquery'), $this->version, true );
		// load view
		// lookup in theme folder (for an override)
		$view_uri = ( false !== strpos($atts['view'], "/") )
			? $atts['view'] .".php"
			: "/views/". $atts['view'] .".php";
		// FIX: we need a leading slash
		if( substr($view_uri, 0,1) !== "/" ) $view_uri = "/". $view_uri;
		$view = get_template_directory() .$view_uri;
		if( !file_exists( $view ) ){
			$view = plugin_dir_path( __FILE__ ) .$view_uri; // assume it exists?
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
		// FIX for timeout passed in seconds
		if( array_key_exists('timeout', $options) && 100 > $options['timeout'] ){
			$options['timeout'] = $options['timeout'] * 1000;
		}

		return $options;
	}

	function setParams( $atts=array() ){
		// variables
		$params = array();
		// supporting:
		$keys = array('ids', 'acf', 'post');
		//
		foreach($keys as $param){
			if( array_key_exists($param, $atts) )
				$params[$param] = $atts[$param];
		}

		return $params;
	}

	function settingsPage() {
		add_options_page( 'Slideshow', 'Slideshow', 'manage_options', UI_SLIDESHOW_SETTINGS, array($this, 'settingsPageHTML') );
	}

	function settingsPageHTML(){
		//$options = get_option( WEBCAL_OPTION_GROUP );
		ob_start();
		include( UI_SLIDESHOW_DIR ."views/settings.php");
		$output = ob_get_clean();
		echo $output;
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

	// Helpers
	function queryParam( $str="" ){
		return urlencode ( base64_encode( json_encode( $str ) ) );
	}

	// Get the Attachment ID from an Image URL in WordPress
	// Source: https://philipnewcomer.net/2012/11/get-the-attachment-id-from-an-image-url-in-wordpress/
	function attachmentID( $url = '' ) {
		global $wpdb;
		// prerequisite
		if ( '' == $url ) return;
		// variables
		$id = false;
		// Get the upload directory paths
		$upload_dir = wp_upload_dir();

		// Make sure the upload path base directory exists in the attachment URL, to verify that we're working with a media library image
		if ( false !== strpos( $url, $upload_dir['baseurl'] ) ) {

			// If this is the URL of an auto-generated thumbnail, get the URL of the original image
			$url = preg_replace( '/-\d+x\d+(?=\.(jpg|jpeg|png|gif)$)/i', '', $url );

			// Remove the upload path base directory from the attachment URL
			$url = str_replace( $upload_dir['baseurl'] . '/', '', $url );

			// Finally, run a custom database query to get the attachment ID from the modified attachment URL
			$id = $wpdb->get_var( $wpdb->prepare( "SELECT wposts.ID FROM $wpdb->posts wposts, $wpdb->postmeta wpostmeta WHERE wposts.ID = wpostmeta.post_id AND wpostmeta.meta_key = '_wp_attached_file' AND wpostmeta.meta_value = '%s' AND wposts.post_type = 'attachment'", $url ) );

		}

		return $id;
	}
}

?>
