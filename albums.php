<?php
/**
 * Pixbox Album administration screen.
 * 
 * @package pixbox
 * @since 0.1.0
 */

$this_album = 0;
$album_obj = null;
$parent = 0;
$parent_name = "Pixbox Albums";
$title = "Pixbox Albums";

if(!empty($_REQUEST['album_ID']) && $_REQUEST['album_ID'] > 0){
  $this_album = $_REQUEST['album_ID'];
  $album_obj = get_term($this_album, 'pixbox_albums');
  $title = "Pixbox: " . $album_obj->name;
  $parent = $album_obj->parent;
  if($parent){
    $parent_name = get_term($parent, 'pixbox_albums')->name;
  }
}

$albums = get_terms(array(
  'taxonomy' => 'pixbox_albums',
  'hide_empty' => false,
  'parent' => $this_album
));
?>

<div class="wrap">
  <h1 class="wp-heading-inline"><?= $title ?></h1>
  <a href="<?=
    esc_url(add_query_arg(array(
      'action' => 'new',
      'parent_ID' => $this_album
    ), admin_url("admin.php?page=pixbox%2Falbum.php"))) ?>" class="page-title-action">Add New</a>
  <?php if(!is_null($album_obj)): ?>
    <a class="pxbx-parent-link" href="<?=
        esc_url(add_query_arg(array(
          'album_ID' => $parent
        ), admin_url('admin.php?page=pixbox%2Falbums.php')))
      ?>">&lt; <?= $parent_name ?></a>
  <?php endif; ?>
  <hr class="wp-header-end">
  <ul class="pxbx-grid">
    <?php if(empty($albums)): ?>
      <h2 class="pxbx-no-albums">No albums found.</h2>
    <?php else: ?>
      <?php foreach ($albums as $album): ?>
        <li class="pxbx-album">
          <a href="<?=
              esc_url(add_query_arg(array(
                'album_ID' => $album->term_id
              ), admin_url('admin.php?page=pixbox%2Falbums.php')))
            ?>" class="pxbx-album-anchor">
            <span class="pxbx-album-title">
              <?= $album->name ?>
            </span>
          </a>
          <div class="pxbx-album-tools">
            <input type="checkbox" autocomplete="off" name="delete_<?= $album->term_id ?>" id="delete_<?= $album->term_id ?>">
            <a class="pxbx-tool-link pxbx-edit-link"
              href="<?= esc_url(add_query_arg(array(
                  'action' => 'edit',
                  'album_ID' => $album->term_id
                ), admin_url('admin.php?page=pixbox%2Falbum.php'))) ?>"
              >Edit</a>
            <label class="pxbx-tool-link pxbx-delete-link" for="delete_<?= $album->term_id ?>">Delete</label>
            <span class="pxbx-delete-confirmation pxbx-tool-link">
              Are you sure?
              <button class="pxbx-tool-link pxbx-delete-link pxbx-delete-button" form="delete_form_<?= $album->term_id ?>">Yes</button>
              /
              <label class="pxbx-tool-link" for="delete_<?= $album->term_id ?>">No</label>
            </span>
            <form action="<?= admin_url('admin-post.php') ?>" id="delete_form_<?= $album->term_id ?>" method="post">
              <input type="hidden" name="action" value="pxbx_album_delete">
              <input type="hidden" name="album_id" value="<?= $album->term_id ?>">
            </form>
          </div>
        </li>
      <?php endforeach; ?>
    <?php endif; ?>
  </ul>
</div>