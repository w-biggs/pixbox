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
    $sub_albums = get_terms(array(
      'taxonomy' => 'pixbox_albums',
      'hide_empty' => false,
      'parent' => $album_id
    ));
    $photos = get_posts(array(
      'posts_per_page'  => -1,
      'orderby'         => 'title',
      'post_type'       => 'pixbox_photo',
      'post_status'     => 'any',
      'tax_query'       => array(
        array(
          'taxonomy'         => 'pixbox_albums',
          'terms'            => $album_id,
          'include_children' => false,
        )
      )
    ));
    $deletion = wp_delete_term($album_id, 'pixbox_albums');
    if(is_wp_error($deletion)){
      $redir = add_query_arg(array(
        'error' => $deletion->get_error_message(),
      ), $redir);
    } else {
      $album_del_error = false;
      foreach ($sub_albums as $sub_album) {
        if(!$album_del_error){
          $album_deletion = wp_delete_term($sub_album->term_id, 'pixbox_albums');
          if(is_wp_error($album_deletion)){
            $redir = add_query_arg(array(
              'error' => $album_deletion->get_error_message(),
            ), $redir);
            $album_del_error = true;
          } else {
            recursiveDelete(wp_get_upload_dir()['basedir'] . "/pixbox/" . $sub_album->id);
          }
        }
      }
      $photo_del_error = false;
      foreach ($photos as $photo) {
        if(!$photo_del_error){
          $photo_deletion = wp_delete_post($photo, true);
          if(is_wp_error($photo_deletion)){
            $redir = add_query_arg(array(
              'error' => $photo_deletion->get_error_message(),
            ), $redir);
            $photo_del_error = true;
          }
        }
      }
      if(!$photo_del_error){
        recursiveDelete(wp_get_upload_dir()['basedir'] . "/pixbox/" . $album_id);
      }
    }
  } else {
    $redir = add_query_arg(array(
      'error' => 'No album ID was given.',
    ), $redir);
  }
  wp_redirect($redir);
  exit;
});

function recursiveDelete($dir_path){
  var_dump($dir_path);
  if(file_exists($dir_path)){
    $rdi = new RecursiveDirectoryIterator($dir_path, FilesystemIterator::SKIP_DOTS | FilesystemIterator::UNIX_PATHS);
    $rii = new RecursiveIteratorIterator($rdi, RecursiveIteratorIterator::CHILD_FIRST); 
    foreach($rii as $value) {
      $value->isFile() ? unlink($value) : rmdir($value);
    }
    rmdir($dir_path);
  }
}