<?php
/**
 * Add pages to the menu.
 * 
 * @package pixbox
 * @since 0.1.0
 */

add_action('admin_menu', 'pixbox_create_menu');

function pixbox_create_menu(){
  pixbox_add_separator();
  add_menu_page(
    'Pixbox',
    'Pixbox',
    'manage_options',
    'pixbox/albums.php',
    '',
    'dashicons-grid-view',
    31
  );
  add_submenu_page(
    'pixbox/albums.php',
    'Pixbox Albums',
    'Albums',
    'manage_options',
    'pixbox/albums.php',
    ''
  );
  add_submenu_page(
    'pixbox/albums.php',
    'Add New Pixbox Album',
    'Add New Album',
    'edit_posts',
    'pixbox/album.php',
    ''
  );
  add_submenu_page(
    'pixbox/albums.php',
    'Upload Photos',
    'Upload Photos',
    'edit_posts',
    'pixbox/upload.php',
    ''
  );
}

// add separator above Pixbox album in admin menu
function pixbox_add_separator(){
  global $menu;
  $position = 30;
  $index = 0;
  foreach($menu as $offset => $section) {
    if (substr($section[2],0,9)=='separator')
      $index++;
    if ($offset>=$position) {
      $menu[$position] = array('','read',"separator{$index}",'','wp-menu-separator');
      break;
    }
  }
  ksort( $menu );
}

function load_pixbox_albums_style($hook) {
  wp_register_style('pixbox_albums_css', plugins_url('pixbox/css/albums.css'), array(), "0.1.0");
}
add_action('admin_enqueue_scripts', 'load_pixbox_albums_style');
add_action('wp_enqueue_scripts', 'load_pixbox_albums_style');