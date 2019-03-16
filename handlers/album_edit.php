<?php
/**
 * Handle the Add/Edit Pixbox Album forms.
 * 
 * @package pixbox
 * @since 0.1.0
 */
add_action('admin_post_pxbx_album_edit', function(){
  $redir = add_query_arg(array( 
    'page' => get_pxbx_dir() . '%2Falbums.php',
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
        $current_pass = get_term_meta($term->term_id, 'album_pass', true);
        if(isset($_POST['album_pass']) && !empty($_POST['album_pass']) && ($_POST['album_pass'] !== $current_pass)){
          update_term_meta($term->term_id, 'album_pass', $_POST['album_pass']);
          update_term_meta($term->term_id, 'pass_date', time());
        }
      } else {
        delete_term_meta($term->term_id, 'album_pass');
        delete_term_meta($term->term_id, 'pass_date');
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