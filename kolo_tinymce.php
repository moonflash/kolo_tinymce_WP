<?php
/**
 * Plugin Name: Koloit image widget
 * Description: Adds support for both the Kolo image widget and Kolo posting in Wordpress
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
	wp_register_script('kolo_script', '//beta.kolo.it/widget.js', array(),'1.0', true);
	wp_enqueue_script('kolo_script');
}

add_filter( 'attachment_fields_to_edit',  'add_kolo_image_attachment_fields_edit', null, 2);
add_filter( 'attachment_fields_to_save',  'add_kolo_image_attachment_fields_save', null, 2);
add_action( 'admin_head', 'kolo_tinymce' );
add_action( 'wp_enqueue_scripts', 'wpb_adding_scripts', 999 ); 
add_action(	'admin_enqueue_scripts', 'kolo_add_css');
add_filter( 'wp_get_attachment_image_attributes','kolo_wp_get_attachment_image_attributes',10,2);
function add_kolo_image_attachment_fields_edit($form_fields, $post){

	$form_fields["kolo_link_id"] = array(
	 "label" => __("Kolo Link Id"),
	 "input" => "text", // this is default if "input" is omitted
	 "value" => get_post_meta($post->ID, "_kolo_link_id", true),
	             "helps" => __("Paste your kolo-id here"),
	);

	$form_fields["kolo_map_zoom"] = array(
	 "label" => __("Map Zoom"),
	 "input" => "text", // this is default if "input" is omitted
	 "value" => get_post_meta($post->ID, "_kolo_map_zoom", true),
							 "helps" => __("Add map zoom ... (between 2 - 18)."),
	);

	$form_fields["kolo_map_type"] = array(
	 "label" => __("Map Type"),
	 "input" => "select", // this is default if "input" is omitted
	 "value" => get_post_meta($post->ID, "_kolo_map_type", true),
							 "helps" => __("Type \"satelite\" unless you want default map image."),
	);

	return $form_fields;
}
function add_kolo_image_attachment_fields_save($post, $attachment){

	 update_post_meta($post['ID'], '_kolo_link_id', $attachment['kolo_link_id']);
	 update_post_meta($post['ID'], '_kolo_map_zoom', $attachment['kolo_map_zoom']);
	 update_post_meta($post['ID'], '_kolo_map_type', $attachment['kolo_map_type']);

	 return $post;
}
function kolo_wp_get_attachment_image_attributes( $atts, $attachment ) {
	$id = get_post_meta($attachment->ID, "_kolo_link_id", true);
	
	if($id){
		$atts['data-kolo-link'] = $id;
		$atts['data-kolo-zoom'] = get_post_meta($attachment->ID, "_kolo_map_zoom", true);
		$atts['data-kolo-type'] = get_post_meta($attachment->ID, "_kolo_map_type", true);
		$atts['name'] = "kolo-location";
	}
  return $atts;
}

function kolo_tinymce() {
	global $typenow;
	
	if( ! in_array( $typenow, array( 'post', 'page' ) ) )
		return ;
	?>
  <script type="text/javascript">
  var post_id = '<?php global $post; echo $post->ID; ?>';
  var post_url = '<?php echo wp_get_shortlink(); ?>';
  var current_dir = '<?php echo basename(dirname( __FILE__ )); ?>';
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
