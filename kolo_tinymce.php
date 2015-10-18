<?php
/**
 * Plugin Name: Koloit image widget
 * Description: Adds support for both the Kolo image widget and Kolo posting in Wordpres
 * Plugin URI:  blog.kolo.it
 * Version:     1.0.0
 * Author:      Koloit
 * Author URI:  http://kolo.it
 * License:     GPLv2
 * License URI: 
 * Text Domain: 
 * Domain Path: /languages
 * Network:     false
 */
function wpb_adding_scripts() {
	wp_register_script('kolo_script', '//mbeta.kolo.it:3000/widget.js', array(),'1.0', true);
	wp_enqueue_script('kolo_script');
}

add_action( 'admin_head', 'kolo_tinymce' );
add_action( 'wp_enqueue_scripts', 'wpb_adding_scripts', 999 ); 
add_action(	'admin_enqueue_scripts', 'kolo_add_css');
function kolo_tinymce() {
	global $typenow;
	
	if( ! in_array( $typenow, array( 'post', 'page' ) ) )
		return ;
	?>
  <script type="text/javascript">
  var post_id = '<?php global $post; echo $post->ID; ?>';
  var post_url = '<?php echo wp_get_shortlink(); ?>';
  </script>
  <?php
	add_filter( 'mce_external_plugins', 'kolo_tinymce_plugin' );
	add_filter( 'mce_buttons', 'kolo_tinymce_button' );
}

function kolo_tinymce_plugin( $plugin_array ) {
	
	$plugin_array['kolo_mce'] = plugins_url( '/example.js', __FILE__ );
	return $plugin_array;
}

function kolo_tinymce_button( $buttons ) {

	array_push( $buttons, 'kolo_mce_button_key' );
	array_push( $buttons, 'kolo_mce_button_lists' );
	array_push( $buttons, 'kolo_mce_button_select' );
	return $buttons;
}


function kolo_add_css() {
    wp_enqueue_style('kolo-tinymce', plugins_url('/kolo.css', __FILE__));
    
}
