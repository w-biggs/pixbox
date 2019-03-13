<?php
/**
 * Handle the Add/Edit Pixbox Album forms.
 * 
 * @package pixbox
 * @since 0.1.0
 */
add_action('admin_post_pxbx_album', function() use ($pxbx_hasher){
  if(isset($_POST['title'])){
    $title = $_POST['title'];
    $parent = 0;
    $pass = null;
    $metaid = null;
    if(isset($_POST['parent'])){
      $parent = $_POST['parent'];
    }
    $term = wp_insert_term($title, 'pixbox_albums', array(
      'parent' => $parent
    ));
    if(is_wp_error($term)){
      trigger_error($term->get_error_message());
    } else {
      if(isset($_POST['passcheck']) && isset($_POST['album_pass'])){
        $pass = $_POST['album_pass'];
        $hashedpass = $pxbx_hasher->HashPassword(trim($pass));
        $metaid = update_term_meta($term['term_id'], 'album_pass', $hashedpass);
      }
      $redir = add_query_arg(array( 
        'page' => 'pixbox%2Falbums.php',
        'termid' => $term['term_id'],
        'metaid' => $metaid
      ), 'admin.php');
      wp_redirect($redir);
      exit;
    }
  }
});