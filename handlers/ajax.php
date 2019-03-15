<?php
/**
 * Handles Pixbox AJAX requests.
 * 
 * @package pixbox
 * @since 0.3.0
 */

add_action('wp_ajax_pxbx_get_items', 'pxbx_get_items');
add_action('wp_ajax_nopriv_pxbx_get_items', 'pxbx_get_items');
add_action('wp_ajax_pxbx_check_password', 'pxbx_check_password');
add_action('wp_ajax_nopriv_pxbx_check_password', 'pxbx_check_password');

function pxbx_get_items(){
  check_ajax_referer('pixbox', 'nonce');
  $this_album = 0;
  $album_obj = null;
  $title = "Albums";
  $parent = 0;
  $parent_name = "Albums";
  if(isset($_POST['album']) && $_POST['album'] > 0){
    $this_album = $_POST['album'];
    $album_obj = get_term($this_album, 'pixbox_albums');
    $title = $album_obj->name;
    $parent = $album_obj->parent;
    if($parent){
      $parent_name = get_term($parent, 'pixbox_albums')->name;
    }
  } else {
    
  }
  $albums = get_terms(array(
    'taxonomy' => 'pixbox_albums',
    'hide_empty' => false,
    'parent' => $this_album
  ));
  $photos = get_posts(array(
    'posts_per_page'  => -1,
    'orderby'         => 'title',
    'post_type'       => 'pixbox_photo',
    'post_status'     => 'any',
    'tax_query'       => array(
      array(
        'taxonomy'         => 'pixbox_albums',
        'terms'            => $this_album,
        'include_children' => false,
      )
    )
  ));
  $items = array(
    'id' => $this_album,
    'title' => $title,
    'parent' => $parent,
    'parentName' => $parent_name,
    'albums' => array(),
    'photos' => array()
  );
  foreach ($albums as $album) {
    $password = false;
    if(!empty(get_term_meta($album->term_id,'album_pass'))){
      $password = true;
    }
    $items['albums'][] = array(
      'name'     => $album->name,
      'id'       => $album->term_id,
      'password' => $password
    );
  }
  foreach ($photos as $photo) {
    $items['photos'][] = array(
      'name'    => $photo->post_title,
      'id'      => $photo->ID,
      'fullres' => get_post_meta($photo->ID, 'fullres', true)
    );
  }
  echo json_encode($items);
  wp_die();
}

function pxbx_check_password(){
  check_ajax_referer('pixbox', 'nonce');
  if(!isset($_POST['album']) || !isset($_POST['password'])){
    wp_send_json_error();
    wp_die();
  }
  $album = $_POST['album'];
  $in_pass = $_POST['password'];
  $album_pass = get_term_meta($album,'album_pass',true);
  if(empty($album_pass)){
    wp_send_json_error();
    wp_die();
  }
  $return = array(
    'success'  => true,
    'matches' => ($album_pass === $in_pass)
  );
  wp_send_json($return);
  wp_die();
}