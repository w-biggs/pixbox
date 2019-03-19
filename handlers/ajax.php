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
    'password' => !empty(get_term_meta($this_album,'album_pass')),
    'albums' => array(),
    'photos' => array()
  );
  foreach ($albums as $album) {
    $password = !empty(get_term_meta($album->term_id,'album_pass'));
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
  wp_send_json_success($items);
  wp_die();
}

function pxbx_check_password(){
  check_ajax_referer('pixbox', 'nonce');
  if(!isset($_POST['album'])){
    wp_send_json_error(__("You must supply an album.", 'pixbox'));
    wp_die();
  }
  $album = $_POST['album'];
  $in_pass = false;
  if(isset($_POST['password'])){
    $in_pass = $_POST['password'];
  } elseif(isset($_COOKIE['album_' . $album . '_pass'])){
    $in_pass = $_COOKIE['album_' . $album . '_pass'];
  }
  if(!isset($in_pass)){
    wp_send_json_error(__("You must supply a password.", 'pixbox'));
    wp_die();
  }
  $album_pass = get_term_meta($album,'album_pass',true);
  $pass_date = intval(get_term_meta($album,'pass_date',true));
  if(empty($album_pass)){
    wp_send_json_error(__("Given album doesn't have a password!", 'pixbox'));
    wp_die();
  }
  if($album_pass === $in_pass){
    // 86400 seconds in a day
    $max_pass_age = intval(get_option('password_expiry_age')) * 86400;
    if((time() - $pass_date) > $max_pass_age){
      wp_send_json_error(__("Password expired.", 'pixbox'));
      wp_die();
    }
    setcookie("album_" . $album . "_pass", $in_pass, strtotime('+1 hour'), '/');
    wp_send_json_success();
  } else {
    wp_send_json_error(__("Incorrect password.", 'pixbox'));
  }
  wp_die();
}