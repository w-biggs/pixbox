<?php
add_action('admin_menu', 'pixbox_create_menu');

function pixbox_create_menu(){
  pixbox_add_separator();
  add_menu_page(
    'Pixbox',
    'Pixbox',
    'administrator',
    'pixbox',
    'pixbox_albums_page',
    plugins_url('pixbox/images/icon.png'),
    31
  );
}

function pixbox_albums_page(){
  echo 'hello.';
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