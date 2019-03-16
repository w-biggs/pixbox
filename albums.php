<?php
/**
 * Pixbox Album administration screen.
 * 
 * @package pixbox
 * @since 0.1.0
 */

$tax = get_taxonomy('pixbox_albums');
$this_album = 0;
$album_obj = null;
$parent = 0;
$parent_name = "Pixbox Albums";
$title = "Pixbox Albums";
$add_new = __('Add New', 'pixbox');

if(!empty($_REQUEST['album_ID']) && $_REQUEST['album_ID'] > 0){
  $this_album = $_REQUEST['album_ID'];
  $album_obj = get_term($this_album, 'pixbox_albums');
  $title = "Pixbox: " . $album_obj->name;
  $parent = $album_obj->parent;
  if($parent){
    $parent_name = get_term($parent, 'pixbox_albums')->name;
  }
  $add_new = $tax->labels->add_new_item;
}

$albums = get_terms(array(
  'taxonomy' => 'pixbox_albums',
  'hide_empty' => false,
  'parent' => $this_album
));

$photos = get_posts(array(
  'posts_per_page'  => -1,
  'orderby'         => 'title',
  'post_type'       => 'pixbox_photo',
  'post_status'     => 'any',
  'tax_query'       => array(
    array(
      'taxonomy'         => 'pixbox_albums',
      'terms'            => $this_album,
      'include_children' => false,
    )
  )
));

wp_enqueue_style('pixbox_albums_css');
?>

<div class="wrap">
  <h1 class="wp-heading-inline"><?= $title ?></h1>
  <a href="<?=
    esc_url(add_query_arg(array(
      'action' => 'new',
      'parent_ID' => $this_album
    ), admin_url("admin.php?page=" . get_pxbx_dir() . "%2Falbum.php"))) ?>"
    class="page-title-action"><?= $add_new ?></a>
  <?php if(!is_null($album_obj)): ?>
    <a href="<?=
        esc_url(add_query_arg(array(
          'album' => $this_album
        ), admin_url('admin.php?page=' . get_pxbx_dir() . '%2Fupload.php')))
      ?>" class="page-title-action"><?= __('Upload Photos', 'pixbox'); ?></a>
    <a class="pxbx-parent-link" href="<?=
        esc_url(add_query_arg(array(
          'album_ID' => $parent
        ), admin_url('admin.php?page=' . get_pxbx_dir() . '%2Falbums.php')))
      ?>">&lt; <?= $parent_name ?></a>
  <?php endif; ?>
  <hr class="wp-header-end">
  <ul class="pxbx-grid">
    <?php if(empty($albums) && empty($photos)): ?>
      <h2 class="pxbx-empty"><?= __('No albums or photos found.','pixbox') ?></h2>
    <?php else: ?>
      <?php foreach ($albums as $album): ?>
        <li class="pxbx-item pxbx-album">
          <a href="<?=
              esc_url(add_query_arg(array(
                'album_ID' => $album->term_id
              ), admin_url('admin.php?page=' . get_pxbx_dir() . '%2Falbums.php')))
            ?>" class="pxbx-item-anchor pxbx-album-anchor">
            <span class="pxbx-item-title">
              <?= $album->name ?>
            </span>
          </a>
          <div class="pxbx-album-tools">
            <input type="checkbox" autocomplete="off" name="delete_<?= $album->term_id ?>" id="delete_<?= $album->term_id ?>">
            <a class="pxbx-tool-link pxbx-edit-link"
              href="<?= esc_url(add_query_arg(array(
                  'action' => 'edit',
                  'album_ID' => $album->term_id
                ), admin_url('admin.php?page=' . get_pxbx_dir() . '%2Falbum.php'))) ?>"
              >Edit</a>
            <label class="pxbx-tool-link pxbx-delete-link" for="delete_<?= $album->term_id ?>">Delete</label>
            <span class="pxbx-delete-confirmation pxbx-tool-link">
              Are you sure?
              <button class="pxbx-tool-link pxbx-delete-link pxbx-delete-button" form="delete_form_<?= $album->term_id ?>">Yes</button>
              /
              <label class="pxbx-tool-link" for="delete_<?= $album->term_id ?>">No</label>
            </span>
            <form action="<?= admin_url('admin-post.php'); ?>" id="delete_form_<?= $album->term_id ?>" method="post">
              <input type="hidden" name="action" value="pxbx_album_delete">
              <input type="hidden" name="album_id" value="<?= $album->term_id ?>">
            </form>
          </div>
        </li>
      <?php endforeach; ?>
      <?php foreach($photos as $photo): ?>
        <li class="pxbx-item pxbx-photo">
          <a href="<?= get_post_meta($photo->ID, 'fullres', true) ?>" class="pxbx-item-anchor pxbx-photo-anchor">
            <div class="pxbx-photo-thumb-container">
              <img src="<?= get_post_meta($photo->ID, 'fullres', true) ?>" alt="<?= $photo->post_title ?>" class="pxbx-photo-thumb">
            </div>
            <span class="pxbx-item-title pxbx-photo-title">
              <?= $photo->post_title ?>
            </span>
          </a>
          <div class="pxbx-album-tools">
            <input type="checkbox" autocomplete="off" name="delete_<?= $photo->ID ?>" id="delete_<?= $photo->ID ?>">
            <label class="pxbx-tool-link pxbx-delete-link" for="delete_<?= $photo->ID ?>">Delete</label>
            <span class="pxbx-delete-confirmation pxbx-tool-link">
              Are you sure?
              <button class="pxbx-tool-link pxbx-delete-link pxbx-delete-button" form="delete_form_<?= $photo->ID ?>">Yes</button>
              /
              <label class="pxbx-tool-link" for="delete_<?= $photo->ID ?>">No</label>
            </span>
            <form action="<?= admin_url('admin-post.php'); ?>" id="delete_form_<?= $photo->ID ?>" method="post">
              <input type="hidden" name="action" value="pxbx_photo_delete">
              <input type="hidden" name="photo_id" value="<?= $photo->ID ?>">
            </form>
          </div>
        </li>
      <?php endforeach; ?>
    <?php endif; ?>
  </ul>
</div>