<?php
/**
 * Handle the Add/Edit Pixbox Album forms.
 * 
 * @package pixbox
 * @since 0.1.0
 */
add_action('admin_post_pxbx_album_edit', function() use ($pxbx_hasher){
  $redir = add_query_arg(array( 
    'page' => 'pixbox%2Falbums.php',
    'action' => 'edit'
  ), 'admin.php');
  if(isset($_POST['album_ID'])){
    $redir = add_query_arg(array(
      'termid' => $term['term_id'],
    ), $redir);
    if(isset($_POST['title'])){
      $title = $_POST['title'];
      $term = get_term($_POST['album_ID'], 'pixbox_albums');
      $parent = 0;
      $pass = null;
      if(isset($_POST['parent'])){
        $parent = $_POST['parent'];
        $redir = add_query_arg(array(
          'album_ID' => $parent,
        ), $redir);
      }
      if(isset($_POST['passcheck'])){
        if(isset($_POST['album_pass']) && !empty($_POST['album_pass'])){
          $pass = $_POST['album_pass'];
          $hashedpass = $pxbx_hasher->HashPassword(trim($pass));
          update_term_meta($term->term_id, 'album_pass', $hashedpass);
        }
      } else {
        delete_term_meta($term->term_id, 'album_pass');
      }
    } else {
      $redir = add_query_arg(array(
        'error' => 'No title was given.',
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