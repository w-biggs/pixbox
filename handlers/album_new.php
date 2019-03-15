<?php
/**
 * Handle the Add/Edit Pixbox Album forms.
 * 
 * @package pixbox
 * @since 0.1.0
 */
add_action('admin_post_pxbx_album_new', function(){
  $redir = add_query_arg(array( 
    'page' => 'pixbox%2Falbums.php',
    'action' => 'add'
  ), 'admin.php');
  if(isset($_POST['title'])){
    $title = $_POST['title'];
    $parent = 0;
    $pass = null;
    if(isset($_POST['parent'])){
      $parent = $_POST['parent'];
      $redir = add_query_arg(array(
        'album_ID' => $parent,
      ), $redir);
    }
    $term = wp_insert_term($title, 'pixbox_albums', array(
      'parent' => $parent
    ));
    if(is_wp_error($term)){
      $redir = add_query_arg(array(
        'error' => $term->get_error_message(),
      ), $redir);
    } else {
      $redir = add_query_arg(array(
        'termid' => $term['term_id'],
      ), $redir);
      if(isset($_POST['passcheck']) && isset($_POST['album_pass'])){
        update_term_meta($term['term_id'], 'album_pass', $_POST['album_pass']);
      }
    }
  } else {
    $redir = add_query_arg(array(
      'error' => 'No title was given.',
    ), $redir);
  }
  wp_redirect($redir);
  exit;
});