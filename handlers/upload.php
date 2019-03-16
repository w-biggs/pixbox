<?php
/**
 * Handle photo uploads.
 * 
 * @package pixbox
 * @since 0.1.0
 */

add_action('admin_post_pxbx_upload', function(){
  $redir = add_query_arg(array( 
    'page' => 'pixbox%2Falbums.php',
    'action' => 'upload'
  ), 'admin.php');
  if(isset($_REQUEST['album'])){
    $album = get_term($_REQUEST['album'], 'pixbox_albums');
    $upload_dir = wp_get_upload_dir()['basedir'] . "/pixbox/" . $album->term_id;
    $redir = add_query_arg(array( 
      'album_ID' => $album->term_id
    ), $redir);
    if(isset($_FILES['upload'])){
      $files = $_FILES['upload'];
      $mkdir = wp_mkdir_p($upload_dir);
      if($mkdir){
        foreach ($files['name'] as $key => $value) {
          $file = array(
            'name'     => $files['name'][$key],
            'type'     => $files['type'][$key],
            'tmp_name' => $files['tmp_name'][$key],
            'error'    => $files['error'][$key],
            'size'     => $files['size'][$key]
          );
          $name = basename($file['name']);
          $move = move_uploaded_file($file['tmp_name'], "$upload_dir/$name");
          if($move){
            $post = wp_insert_post(array(
              'post_type'  => 'pixbox_photo',
              'post_title' => $file['name'],
              'tax_input' => array(
                'pixbox_albums' => $album->term_id
              )
            ), true);
            var_dump($post);
            if(!is_wp_error($post)){
              $meta = update_post_meta(
                $post,
                'fullres',
                str_replace($_SERVER['DOCUMENT_ROOT'],'',"$upload_dir/$name")
              );
            } else {
              $redir = add_query_arg(array(
                'error' => $post->get_error_message(),
              ), $redir);
              break;
            }
          } else {
            $redir = add_query_arg(array(
              'error' => "move failed - invalid filename",
            ), $redir);
            break;
          }
        }
      } else {
        $redir = add_query_arg(array( 
          'error' => 'failed to create directory'
        ), $redir);
      }
    }
  }
  wp_redirect($redir);
  exit;
});