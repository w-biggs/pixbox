<?php
/**
 * Handle the Pixbox Album delete buttons.
 * 
 * @package pixbox
 * @since 0.1.0
 */
add_action('admin_post_pxbx_album_delete', function(){
  $redir = add_query_arg(array( 
    'page' => get_pxbx_dir() . '%2Falbums.php',
    'action' => 'delete'
  ), 'admin.php');
  if(isset($_POST['album_id'])){
    $album_id = $_POST['album_id'];
    $album = get_term($album_id, 'pixbox_albums');
    $redir = add_query_arg(array( 
      'termid' => $album_id,
      'album_ID' => $album->parent
    ), $redir);
    $deletion = wp_delete_term($album_id, 'pixbox_albums');
    if(is_wp_error($deletion)){
      $redir = add_query_arg(array(
        'error' => $term->get_error_message(),
      ), $redir);
    }
  } else {
    $redir = add_query_arg(array(
      'error' => 'No album ID was given.',
    ), $redir);
  }
  wp_redirect($redir);
  exit;
});