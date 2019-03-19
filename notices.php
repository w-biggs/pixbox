<?php
/**
 * Handles admin notices for the back-end.
 * 
 * @package pixbox
 * @since 0.5.0
 */

add_action('admin_notices', 'pixbox_admin_notices');

function pixbox_admin_notices(){
  $screen = get_current_screen();
  if($screen->id === 'pixbox/albums' && !empty($_SERVER['HTTP_REFERER'])){
    $action;
    if(isset($_GET['action'])){
      $action = $_GET['action'];
      if(isset($_GET['error'])){
        $class = "notice-error";
        switch($_GET['action']) {
          case 'delete':
            $msg = __("An error occurred while deleting the album: ", "pixbox");
            break;
          case 'delete':
            $msg = __("An error occurred while editing the album: ", "pixbox");
            break;
          case 'add':
            $msg = __("An error occurred while creating the album: ", "pixbox");
            break;
          case 'delete_photo':
            $msg = __("An error occurred while deleting the photo: ", "pixbox");
            break;
          case 'upload':
            $msg = __("An error occurred while uploading the photo(s): ", "pixbox");
            break;
        }
        $msg .= $_GET['error'];
      } else {
        $class = "notice-success";
        switch($_GET['action']) {
          case 'delete':
            $msg = __("Successfully deleted the album.", "pixbox");
            break;
          case 'edit':
            $msg = __("Successfully edited the album.", "pixbox");
            break;
          case 'add':
            $msg = __("Successfully created the album.", "pixbox");
            break;
          case 'delete_photo':
            $msg = __("Successfully deleted the photo.", "pixbox");
            break;
          case 'upload':
            $msg = __("Successfully uploaded the photo(s).", "pixbox");
            break;
        }
      }
      ?>
      <div class="notice <?= $class ?>">
        <p><?= $msg ?></p>
      </div>
      <?php
    }
  }
}