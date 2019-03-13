<?php
/**
 * Pixbox Album administration screen.
 * 
 * @package pixbox
 * @since 0.1.0
 */
?>
<div class="wrap">
  <h1 class="wp-heading-inline">Pixbox Albums</h1>
  <a href="<?= admin_url("admin.php?page=pixbox%2Falbum-new.php") ?>" class="page-title-action">Add New</a>
  <hr class="wp-header-end">
  <ul class="pxbx-grid">
    <li class="pxbx-album">
      <a href="#" class="pxbx-album-anchor">
        <span class="pxbx-album-title">
          Event Name
        </span>
      </a>
      <div class="pxbx-album-tools">
        <a class="pxbx-tool-link" href="#">Edit</a>
        <a class="pxbx-tool-link pxbx-delete-link" href="#">Delete</a>
      </div>
    </li>
  </ul>
</div>