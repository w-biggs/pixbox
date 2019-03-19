<?php
/**
 * Utility functions and variables for the Pixbox plugin.
 * 
 * @package pixbox
 * @since 0.4.3
 */

// Register the passcheck script
add_action('admin_enqueue_scripts', function($hook){
  $screen = get_current_screen();
  if($screen->id === 'pixbox/album'){
    wp_enqueue_script('pixbox_passcheck_js', plugins_url('js/passcheck.js', __FILE__));
  }
});

function get_pxbx_dir(){
  return plugin_basename(__DIR__);
}