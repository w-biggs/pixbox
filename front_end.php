<?php
/**
 * Sets and loads the Pixbox front-end.
 * 
 * @package pixbox
 * @since 0.3.0
 */

// Register the necessary scripts
add_action('wp_enqueue_scripts', function(){
	wp_register_script('pixbox_front_js', plugins_url('js/front_end.js', __FILE__), array('jquery'));
});

// Adds "Pixbox Page" state to Pixbox page.
add_filter('display_post_states', 'pixbox_post_states', 10, 2);

function pixbox_post_states($post_states, $post){
  $pixbox_page = get_option('pixbox_page', 0);

  if($post->ID == $pixbox_page){
    $post_states[] = __('Pixbox Page', 'pixbox');
  }
  return $post_states;
}

// Replaces the Pixbox page's template
add_filter( 'template_include', 'pixbox_page_template', 99 );

function pixbox_page_template($template) {

  $pixbox_page = get_option('pixbox_page', 0);

	if(!empty($pixbox_page) && is_page($pixbox_page)){
		$new_template = plugin_dir_path( __FILE__ ) . 'templates/albums.php';
		if (!empty($new_template)) {
			return $new_template;
		}
	}

	return $template;
}