<?php
/**
 * Pixbox photo upload screen.
 * 
 * @package pixbox
 * @since 0.1.0
 */

$album = 0;

if(!empty($_REQUEST['album']) && $_REQUEST['album'] > 0){
  $album = $_REQUEST['album'];
}

?>

<div class="wrap">
  <h1 class="wp-heading-inline">Upload Photos</h1>
  <form action="<?= admin_url('admin-post.php') ?>" method="post" enctype="multipart/form-data">
    <input type="hidden" name="action" value="pxbx_upload">
    <table class="form-table">
      <tr class="form-field">
        <th scope="row"><label for="album"><?= __('Album', 'pixbox') ?></label></th>
        <td>
          <?php
          $dropdown_args = array(
            'hide_empty'       => 0,
            'hide_if_empty'    => false,
            'taxonomy'         => 'pixbox_albums',
            'name'             => 'album',
            'orderby'          => 'name',
            'selected'         => $album,
            'hierarchical'     => true
          );
          wp_dropdown_categories( $dropdown_args );
          ?>
          <p class="description">The album you want to upload to.</p>
        </td>
      </tr>
      <tr class="form-field">
        <th scope="row"><label for="upload"><?= __('Choose Files') ?></label></th>
        <td>
          <input type="file" name="upload[]" id="upload" accept="image/*,video/*" multiple>
        </td>
      </tr>
    </table>
    <?php submit_button(__('Upload','picbox')); ?>
  </form>
</div>