<?php
/**
 * The page template for the Pixbox front-end.
 * 
 * @package pixbox
 * @since 0.3.0
 */

get_header('pixbox');
wp_enqueue_style('pixbox_albums_css');

// Load the root unless there's a specific ID provided
$this_album = 0;
if(!empty($_REQUEST['album'])){
  $this_album = $_REQUEST['album'];
}

// nonce for ajax requests
$ajax_nonce = wp_create_nonce('pixbox');

// Load the necessary scripts
wp_localize_script('pixbox_front_js', 'phpdata', array(
  'ajaxurl' => admin_url('admin-ajax.php'),
  'nonce' => $ajax_nonce,
  'notFoundText' => __('No albums or photos found.','pixbox'),
  'thisAlbum' => $this_album
));
wp_enqueue_script('pixbox_front_js');
?>
<div class="wrap">
  <div class="pixbox-front">
    <h1 class="pixbox-title">Albums</h1>
    <ul class="pxbx-grid">
      <span class="pxbx-nojs">JavaScript is required to load image albums.</span>
    </ul>
  </div>
</div>
<?php
get_footer('pixbox');