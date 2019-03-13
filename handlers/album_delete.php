<?php
/**
 * Handle the Pixbox Album delete buttons.
 * 
 * @package pixbox
 * @since 0.1.0
 */
add_action('admin_post_pxbx_album_delete', function(){
  if(isset($_POST['album_id'])){
    $album_id = $_POST['album_id'];
    $deletion = wp_delete_term($album_id, 'pixbox_albums');
    if(is_wp_error($deletion)){
      trigger_error($term->get_error_message());
    } else {
      $redir = add_query_arg(array( 
        'page' => 'pixbox%2Falbums.php',
        'termid' => $term['term_id'],
        'metaid' => $metaid,
        'action' => 'delete'
      ), 'admin.php');
      wp_redirect($redir);
      exit;
    }
  } else {
    var_dump($_POST);
  }
});