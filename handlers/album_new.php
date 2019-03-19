<?php
/**
 * Handle the Add/Edit Pixbox Album forms.
 * 
 * @package pixbox
 * @since 0.1.0
 */
add_action('admin_post_pxbx_album_new', function(){
  $redir = add_query_arg(array( 
    'page' => get_pxbx_dir() . '%2Falbums.php',
    'action' => 'add'
  ), 'admin.php');
  if(!empty($_POST['title'])){
    $title = $_POST['title'];
    $parent = 0;
    $pass = null;
    if(!empty($_POST['parent'])){
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
        'error' => urlencode($term->get_error_message()),
      ), $redir);
    } else {
      $redir = add_query_arg(array(
        'termid' => $term['term_id'],
      ), $redir);
      if(!empty($_POST['passcheck']) && !empty($_POST['album_pass'])){
        update_term_meta($term['term_id'], 'album_pass', $_POST['album_pass']);
        update_term_meta($term['term_id'], 'pass_date', time());
      }
    }
  } else {
    $redir = add_query_arg(array(
      'error' => urlencode('No title was given'),
    ), $redir);
  }
  wp_safe_redirect($redir);
  exit;
});