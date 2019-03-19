<?php
/**
 * Handle file downloads.
 * 
 * @package pixbox
 * @since 0.5.0
 */
function find_wordpress_base_path() {
  $dir = dirname(__FILE__);
  do {
    //it is possible to check for other files here
    if( file_exists($dir."/wp-config.php") ) {
        return $dir;
    }
  } while( $dir = realpath("$dir/..") );
  return null;
}

$error = "";

require(find_wordpress_base_path() . '/wp-load.php');

$redir = add_query_arg(array( 
  'action' => 'download'
), $redir_url);

if(!empty($_GET['album'])){
  $album = get_term($_GET['album'],'pixbox_albums');
  if(!empty($_COOKIE['album_' . $album->term_id . '_pass'])){
    $in_pass = $_COOKIE['album_' . $album->term_id . '_pass'];
  } else {
    $in_pass = false;
  }
  $album_pass = get_term_meta($album->term_id,'album_pass',true);
  if($in_pass === $album_pass){
    $upload_dir = wp_get_upload_dir()['basedir'] . "/pixbox/";
    $zip_file = $upload_dir . sanitize_file_name($album->name . '.zip');
    $zip_archive = new ZipArchive();
    if(!$zip_archive->open($zip_file, ZIPARCHIVE::CREATE || ZIPARCHIVE::OVERWRITE)){
      $error = 'Failed to create archive.';
    } else {
      $zip_archive->addGlob($upload_dir . $album->term_id . '/*', 0, array(
        'remove_path' => $upload_dir . $album->term_id . '/',
      ));
      if(!$zip_archive->status === ZIPARCHIVE::ER_OK){
        $error = 'Failed to write files to zip.';
      } else {
        $zip_archive->close();
        header('Content-Description: File Transfer');
        header('Content-Type: application/zip');
        header('Content-Disposition: attachment; filename="'.basename($zip_file).'"');
        header('Expires: 0');
        header('Cache-Control: must-revalidate');
        header('Pragma: public');
        header('Content-Length: ' . filesize($zip_file));
        ignore_user_abort(true);
        readfile($zip_file);
        unlink($zip_file);
        exit;
      }
    }
  } else {
    $error = 'Not authenticated.';
  }
} else {
  $error = 'No album specified.';
}
echo '<h1>' . $error . '</h1>';
exit;