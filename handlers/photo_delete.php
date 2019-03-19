<?php
/**
 * Handle the Pixbox Photo delete buttons.
 * 
 * @package pixbox
 * @since 0.4.0
 */
add_action('admin_post_pxbx_photo_delete', function(){
  $redir = add_query_arg(array( 
    'page' => get_pxbx_dir() . '%2Falbums.php',
    'action' => 'delete_photo'
  ), 'admin.php');
  if(!empty($_POST['photo_id'])){
    $photo_id = $_POST['photo_id'];
    $photo = get_post($photo_id);
    if(!is_null($photo)){
      $album = wp_get_post_terms($photo_id, 'pixbox_albums')[0];
      $redir = add_query_arg(array( 
        'photo_ID' => $photo_id,
        'album_ID' => $album->term_id
      ), $redir);
      $deletion = wp_delete_post($photo_id, true);
      if(!$deletion){
        $redir = add_query_arg(array(
          'error' => urlencode('Could not delete photo.'),
        ), $redir);
      }
    } else {
      $redir = add_query_arg(array(
        'error' => urlencode('No photo was found with the given ID.'),
      ), $redir);
    }
  } else {
    $redir = add_query_arg(array(
      'error' => urlencode('No photo ID was given.'),
    ), $redir);
  }
  wp_safe_redirect($redir);
  exit;
});