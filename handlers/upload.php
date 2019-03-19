<?php
/**
 * Handle photo uploads.
 * 
 * @package pixbox
 * @since 0.1.0
 */

add_action('admin_post_pxbx_upload', function(){
  $redir = add_query_arg(array( 
    'page' => get_pxbx_dir() . '%2Falbums.php',
    'action' => 'upload'
  ), 'admin.php');
  if(!empty($_REQUEST['album'])){
    $album = get_term($_REQUEST['album'], 'pixbox_albums');
    $upload_dir = wp_get_upload_dir()['basedir'] . "/pixbox/" . $album->term_id;
    $redir = add_query_arg(array( 
      'album_ID' => $album->term_id
    ), $redir);
    if(!empty($_FILES['upload']) && $_FILES['upload']['error'][0] === 0){
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
            if(!is_wp_error($post)){
              $meta = update_post_meta(
                $post,
                'fullres',
                str_replace($_SERVER['DOCUMENT_ROOT'],'',"$upload_dir/$name")
              );
            } else {
              $redir = add_query_arg(array(
                'error' => urlencode($post->get_error_message()),
              ), $redir);
              break;
            }
          } else {
            $redir = add_query_arg(array(
              'error' => urlencode('Move failed - invalid filename.'),
            ), $redir);
            break;
          }
        }
      } else {
        $redir = add_query_arg(array( 
          'error' => urlencode('Failed to create directory.')
        ), $redir);
      }
    } else {
      $errmsg = "";
      switch($_FILES['upload']['error'][0]){
        case 1:
          $errmsg = "The uploaded file exceeds the upload_max_filesize directive in php.ini.";
          break;
        case 2:
          $errmsg = "The uploaded file exceeds the MAX_FILE_SIZE directive that was specified in the HTML form.";
          break;
        case 3:
          $errmsg = "The uploaded file was only partially uploaded.";
          break;
        case 4:
          $errmsg = "No file was uploaded.";
          break;
        case 6:
          $errmsg = "Missing a temporary folder.";
          break;
        case 7:
          $errmsg = "Failed to write file to disk.";
          break;
        case 8:
          $errmsg = "File upload stopped by extension.";
          break;
        default:
          $errmsg = "Unknown upload error.";
          break; 
      }
      $redir = add_query_arg(array( 
        'error' => urlencode($errmsg)
      ), $redir);
    }
  }
  wp_safe_redirect($redir);
  exit;
});