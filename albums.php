<?php
/**
 * Pixbox Album administration screen.
 * 
 * @package pixbox
 * @since 0.1.0
 */

$albums = get_terms(array(
  'taxonomy' => 'pixbox_albums',
  'hide_empty' => false
));
?>

<div class="wrap">
  <h1 class="wp-heading-inline">Pixbox Albums</h1>
  <a href="<?= admin_url("admin.php?page=pixbox%2Falbum-new.php") ?>" class="page-title-action">Add New</a>
  <hr class="wp-header-end">
  <ul class="pxbx-grid">
    <?php if(empty($albums)): ?>
      <h2 class="pxbx-no-albums">No albums found.</h2>
    <?php else: ?>
      <?php foreach ($albums as $album): ?>
        <li class="pxbx-album">
          <a href="#" class="pxbx-album-anchor">
            <span class="pxbx-album-title">
              <?= $album->name ?>
            </span>
          </a>
          <div class="pxbx-album-tools">
            <a class="pxbx-tool-link" href="#">Edit</a>
            <a class="pxbx-tool-link pxbx-delete-link" href="#">Delete</a>
          </div>
        </li>
      <?php endforeach; ?>
    <?php endif; ?>
  </ul>
</div>